<?php

namespace App\Http\Controllers;


use App\Models\Licence;
use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
//use Illuminate\Http\File;
use App\Models\LicenceDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class LicenceDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document_file"=> "required|mimes:pdf"
            ]);
            
            
            $removeSpace = str_replace(' ', '_',$request->document_file->getClientOriginalName());
            $fileName = Str::limit(sha1(now()),4).str_replace('-', '_',$removeSpace); 
            $request->file('document_file')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

            if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
              $fileModel = new LicenceDocument;
              $fileModel->document_name = $request->document_file->getClientOriginalName();
              $fileModel->licence_id = $request->licence_id;
              $fileModel->document_type = $request->doc_type;
              $fileModel->num = $request->num;
              $fileModel->document_file = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
              $fileModel->save();


                if($request->stage && intval($request->stage) >= 15){
                  Licence::whereId($fileModel->licence_id)->update(['is_new_app' => false]);
                }

                if($request->stage){
                  Licence::whereId($fileModel->licence_id)->update(['status' => $request->stage]);
                 }
               

            }else{
              return back()->with('error','Azure storage could not be reached.Please try again.');
            }

           

            $updateLicence = Licence::find($request->licence_id);
            switch ($request->doc_type) {
              case 'Client Quoted':
                $updateLicence->update(['is_client_invoiced' => true]);
                break;
              case 'Application Lodged':
                $updateLicence->update(['is_application_logded_doc_uploaded' => true]);
                break;
              case 'Client Finalisation Invoiced':
                $updateLicence->update(['is_finalisation_doc_uploaded' => true]);
                break;                
              default:
                # code...
                break;
            }

       
      return back()->with('success','Document uploaded successfully.');
    
    }

    public function destroy($id){
        $model = LicenceDocument::find($id);
        $updateLicence = Licence::find($model->licence_id);
        switch ($model->document_type) {
          case 'Client Quoted':
            $updateLicence->update(['is_client_invoiced' => null]);
            break;
          case 'Application Lodged':
            $updateLicence->update(['is_application_logded_doc_uploaded' => null]);
            break;
          case 'Client Finalisation Invoiced':
            $updateLicence->update(['is_finalisation_doc_uploaded' => null]);
            break;               
          default:
            # code...
            break;
        }
        if(!is_null($model->document_file)){
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
        
             return back()->with('error','Document not successfully.');
    }

    public function merge($licence_id){

      try {
        
        $exist =  Licence::whereId($licence_id)->first(['id','merged_document']); 
        $merger = PDFMerger::init();           
          if (! is_null($exist->merged_document)) {
            unlink(storage_path().'app/public/'.$exist->merged_document);
            $exist->update(['merged_document' => null]);
          }
                  
         $all_docs = LicenceDocument::where('licence_id',$exist->id)->whereNotNull('num')->orderBy('num','ASC')
         ->get(['id','document_file','licence_id','document_type']);
         
         $liquor_board_doc = LicenceDocument::where('licence_id',$exist->id)->where('document_type','Payment To The Liquor Board')->get(['id','document_file']);
         $merged_collections = $all_docs->merge($liquor_board_doc);
         foreach ($merged_collections as $doc) {
            $merger->addPDF(env('BLOB_FILE_PATH').$doc->document_file, 'all');
          }
          $fileName = 'licence'.'_'.time().'.pdf';
          $merger->merge();

          $exist->update(['merged_document' => $fileName]);

          $merger->save(storage_path('/app/public/'.$fileName));
          return back()->with('success','Documents merged successfully.');

      } catch (\Throwable $th) {
        throw $th;
        //return back()->with('error','Error merging documents.');
      }
          
    }
}
