<?php

namespace App\Http\Controllers;

use App\Models\Alteration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class AlterationDocumentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf",
            "doc_type"=> "required"
            ]);
            

            $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
            $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
            $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
            

            if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
              $fileModel = new AlterationDocument;
              $fileModel->alteration_id = $request->alteration_id;
              $fileModel->doc_type = $request->doc_type;
              $fileModel->num = $request->doc_number;
              $fileModel->document_name = $request->document->getClientOriginalName();
              $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
  
              if($fileModel->save()){
                Alteration::whereId($fileModel->alteration_id)->update(['status' => $request->stage]);
                return back()->with('success','Document uploaded successfully.');
              }
            }else{
              return back()->with('error','Azure storage could not be reached.Please try again.');              
            }
           
    
    }
    public function destroy($id)
    {
        try {
           AlterationDocument::find($id)->delete();
           return back()->with('success','Document deleted successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','Error deleting document.');
        }
    }


    public function merge(Request $request){

        try {
          
          $exist =  Alteration::whereId($request->alteration_id)->whereNotNull('merged_document')->first(['id','merged_document']); 
        $merger = PDFMerger::init();           
          if (! is_null($exist)) {
            unlink(storage_path('app/public/').$exist->merged_document);
            $exist->update(['merged_document' => null]);
          }
                
         $alterations =  AlterationDocument::where('alteration_id',$request->alteration_id)->whereNotNull('num')->orderBy('num','ASC')->get(['path']);
         $model =  Alteration::whereId($request->alteration_id)->first();
          foreach ($alterations as $alteration) {
            $merger->addPDF(env('BLOB_FILE_PATH').$alteration->path, 'all');
          }
          $fileName = 'merged_alteration_'.time().'.pdf';
          $merger->merge();

          $model->update(['merged_document' => $fileName]);

          $merger->save(storage_path('/app/public/'.$fileName));
          
          return back()->with('success','Document merged successfully.');
        } catch (\Throwable $th) {
          return back()->with('error','Error merging document.');
        }
          

    }

}
