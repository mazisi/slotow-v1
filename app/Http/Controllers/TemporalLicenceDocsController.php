<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;
use App\Events\LogUserActivity;
use App\Models\CompanyDocument;
use App\Models\TemporalLicence;
use App\Models\TemporalLicenceDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class TemporalLicenceDocsController extends Controller
{
   public function store(Request $request){
    $request->validate([
        "document"=> "required|mimes:pdf"
        ]); 

        try {
          $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
          $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
          $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
          

          if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
            $fileModel = new TemporalLicenceDocument;
            $fileModel->document_name = $request->document->getClientOriginalName();
            $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
            $fileModel->temporal_licence_id = $request->temp_licence_id;
            $fileModel->doc_type = $request->doc_type;
            $fileModel->num = $request->merge_number;
            $fileModel->belongs_to = $request->person_or_company;
            $fileModel->slug = sha1(now());

            if($fileModel->save()){
              TemporalLicence::whereId($fileModel->temporal_licence_id)->update(['status' => $request->stage]);
              return back()->with('success','Document uploaded successfully.');
            }
          }else{
            return back()->with('error','Azure storage could not be reached.Please try again.');              
          }
        } catch (\Throwable $th) {
          return back()->with('error','An unknown error while uploading document.');
        }
        

}

public function destroy($id){
       try {

          $model = TemporalLicenceDocument::find($id);
          $activity = 'Deleted Temporal Document: ' . $model->document_name;
          event(new LogUserActivity(auth()->user(), $activity)); 
          $model->delete();
        return back()->with('success','Document removed successfully.');

       } catch (\Throwable $th) {
         return back()->with('error','Error removing document.');
       }

}

public function merge(Request $request,$type){

  // $get_proof_of_payment = TemporalLicenceDocument::where('temporal_licence_id',$request->temporal_licence_id)
  //                                     ->where('doc_type','Payment To The Liquor Board')->first();
  // $get_proof_of_payment->update(['num' => 2]);

  if($type == 'Individual'){
    $person_id_document = PeopleDocument::where('people_id',$request->person_id)->where('doc_type','ID Document')->first();
   if(!is_null($person_id_document)){
    File::copy(env('BLOB_FILE_PATH').$person_id_document->path, env('BLOB_FILE_PATH').$person_id_document->path);
          $fileModel = new TemporalLicenceDocument;       
            $fileModel->document_name = $person_id_document->document_name;
            $fileModel->document = $person_id_document->document;
            $fileModel->temporal_licence_id = $request->temporal_licence_id;
            $fileModel->doc_type = 'Individual ID Document';
            $fileModel->num = 2;
            $fileModel->belongs_to = 'Individual';
            $fileModel->slug = sha1(now());
            $fileModel->save();

  }else{
    return back()->with('error','ID document is not yet uploaded.');
  }
}

  $temporals = TemporalLicenceDocument::where('temporal_licence_id',$request->temporal_licence_id)
                                      ->where('belongs_to',$type)
                                      ->whereNotNull('num')
                                      ->orderBy('num','ASC')->get();

     $merger = PDFMerger::init();

     foreach ($temporals as $temp) {
       $merger->addPDF(env('BLOB_FILE_PATH').$temp->document, 'all');
     }
     $fileName = time().'.pdf';
     $merger->merge();

     $merger->save(storage_path('/app/public/'.$fileName));
     $updateModel = TemporalLicence::whereId($request->temporal_licence_id)->update(['merged_document' => $fileName]);
     if($updateModel){
       return back()->with('success','Document merged successfully.');
     }        
     return back()->with('error','Error uploading document.');

}
   
}
