<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;

class PeopleDocumentController extends Controller
{
    public function uploadDocument(Request $request){
        $request->validate([
            'document' => 'mimes:jpeg,jpg,png,pdf',
            "people_id" => "required|exists:people,id",
            "doc_type" => "required|in:Work Permit,Passport,Police Clearance,ID Document,Fingerprints"
            ]);


            $removeSpace = str_replace(' ', '_',$request->document->getClientOriginalName());
            $fileName = Str::limit(sha1(now()),4).str_replace('-', '_',$removeSpace); 
            $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));

    if(fileExist(env('AZURE_STORAGE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileName)){
                    $fileModel = new PeopleDocument;
                    $fileModel->document_name = $fileName;
                    $fileModel->document = $fileName;
                    $fileModel->people_id = $request->people_id;
                    $fileModel->doc_type = $request->doc_type;
                    $fileModel->expiry_date = $request->doc_expiry;
                    $fileModel->path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                    $fileModel->slug = sha1(time());
                    
            if($fileModel->save()){
                return back()->with('success','Document uploaded successfully.');
            }
                return back()->with('error','Error uploading document.');
    }else{
        return back()->with('error','Azure storage could not be reached.Please try again.');
      }
    }

    public function deleteDocument($slug){
             PeopleDocument::whereSlug($slug)->delete();
            return back()->with('success','Document removed successfully.');
      
        return back()->with('error','An error occurred while deleting document');
    }
}
