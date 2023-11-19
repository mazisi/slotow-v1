<?php

namespace App\Http\Controllers;

use App\Models\LicenceDocument;
use App\Models\MergedDocument;
use App\Models\Nomination;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App\Models\NominationDocument;

class MergeDocumentController extends Controller{

    public function merge($id){

       try {
        

        $nominations =  NominationDocument::where('nomination_id',$id)->get(['document','licence_id']);
      
          $merger = PDFMerger::init();

          $exist = MergedDocument::where('nomination_id',$id)->first(['file_name']);
          
          //  Check if original licence
          //  is uploaded or else merge latest renewal
         $latest_renewal = NominationDocument::where('doc_type','Latest Renewal/Licence')->where('nomination_id',$id)->first();

          if ($exist) {
            unlink(storage_path('/app/public/'.$exist->file_name));
            $exist->delete();
          }

          if($latest_renewal){

            foreach ($nominations as $nom) {
              $merger->addPDF(env('BLOB_FILE_PATH').$nom->document, 'all');
            }
          

          }else{
            $nom = Nomination::find($id);
            $original_licence = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$nom->licence_id)->first(['document_file']);
              if(!$original_licence){
                return back()->with('error','Original Licence or Latest Renewal NOT found.Please ensure at least on of them is uploaded.');
              }
            foreach ($nominations as $nom) {
              $merger->addPDF(env('BLOB_FILE_PATH').$nom->document, 'all');
            }

            if(! is_null($original_licence)){
               $merger->addPDF(env('BLOB_FILE_PATH').$original_licence->document_file, 'all');
            }
          }

            $fileName = 'merged_nomination'.time().'.pdf';
            $merger->merge();

            $store_merged_file = MergedDocument::create([
              'file_name' => $fileName,'nomination_id' => $id]);

            $merger->save(storage_path('/app/public/'.$fileName));

          if($store_merged_file){
            return back()->with('success','Document merged successfully.');
          }        
          return back()->with('error','Error uploading document.');


       } catch (\Throwable $th) {
          return back()->with('error','Error uploading document.');
       }

    }

}
