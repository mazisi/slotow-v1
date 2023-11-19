<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class TransferDocsController extends Controller
{
    public function store(Request $request,$transfer_id){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);
           
            
            try {
                $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
                $fileName = Str::limit(sha1(now()),3).str_replace('-', '_',$removeSpace);
                $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
                

                if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
                  $fileModel = new TransferDocument;
                  $fileModel->document_name = $request->document->getClientOriginalName();
                  $fileModel->document = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                  $fileModel->licence_transfer_id = $transfer_id;
                  $fileModel->doc_type = $request->document_type;
                  $fileModel->num = $request->document_number;
                  $fileModel->belongs_to = $request->belong_to;
                  $fileModel->slug = sha1(now());
      
                  if($fileModel->save()){
                    LicenceTransfer::whereId($fileModel->licence_transfer_id)->update(['status' => $request->stage]);
                    return back()->with('success','Document uploaded successfully.');
                  }
                }else{
                  return back()->with('error','Azure storage could not be reached.Please try again.');
                }
            } catch (\Throwable $th) {
              return back()->with('error','An unknown error occured.Please try again.');
            }
      
    
    }

    public function merge(Request $request){

        try {
             
          $exist =  LicenceTransfer::whereId($request->transfer_id)->whereNotNull('merged_document')->first(); 
          $merger = PDFMerger::init();           
             if (! is_null($exist)) {
               unlink(storage_path('app/public/').$exist->merged_document);
               $exist->update(['merged_document' => null]);
             }
         
            if(!is_null($request->latest_renewal)){
                $original_licence = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$request->original_licence['licence_id'])->first();
               if(!is_null($original_licence)){        
                
              //File::copy(env('BLOB_FILE_PATH').$original_licence->document_file, env('BLOB_FILE_PATH').$original_licence->document_file);
              $fileModel = new TransferDocument;
            
              $fileModel->document_name = $original_licence->document_name;
              $fileModel->document = sha1(now()).$original_licence->document_file;
              $fileModel->licence_transfer_id = $request->transfer_id;
              $fileModel->doc_type = 'Original-Licence';
              $fileModel->num = 9;
              $fileModel->slug = sha1(now());
              $fileModel->save();
              
              }
            }
            //get proof of payment to merge
            $merge_proof_of_payment = TransferDocument::where('licence_transfer_id',$request->transfer_id)->where('doc_type','Payment To The Liquor Board')->first(['id','num']);
            $merge_proof_of_payment->update(['num' => 2]);
            
            $transfers =  TransferDocument::where('licence_transfer_id',$request->transfer_id)->whereNotNull('num')->orderBy('num','ASC')->get();
            $model =  LicenceTransfer::whereId($request->transfer_id)->first();
             foreach ($transfers as $transfer) {
               $merger->addPDF(env('BLOB_FILE_PATH').$transfer->document, 'all');
             }
             $fileName = time().'.pdf';
             $merger->merge();
   
             $model->update(['merged_document' => $fileName]);
   
             $merger->save(storage_path('/app/public/'.$fileName));
             return back()->with('success','Document merged successfully.');

        } catch (\Throwable $th) {
          return back()->with('error', 'Error merging files.');
        }
           
 
     }

    public function destroy($id){
           try {
            $model = TransferDocument::find($id);
            $model->delete();
            return back()->with('success','Document removed successfully.');
           } catch (\Throwable $th) {
            return back()->with('error', 'Error deleting file.');
           }
      
    }

}
