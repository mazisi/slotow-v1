<?php

namespace App\Http\Controllers;

use App\Mail\NotifySlotowMail;
use App\Models\CompanyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CompanyDocsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "document"=> "required|mimes:pdf"
            ]);

            try {
                $removeSpace = str_replace('-', '_',$request->document->getClientOriginalName());
                $fileModel = new CompanyDocument;
                $fileName = Str::limit(sha1(now()),7).str_replace(' ', '_',$removeSpace);
                $filePath = $request->file('document')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
                $fileModel->document_name = $request->document->getClientOriginalName();
                $fileModel->uploaded_by = $request->user()->hasRole('company-admin') ? auth()->id() : NULL;
                $fileModel->document_file = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                $fileModel->company_id = $request->company_id;
                $fileModel->document_type = $request->doc_type;
                $fileModel->expiry_date = $request->expiry_date;
                $fileModel->file_path = env('AZURE_STORAGE_CONTAINER').'/'.$fileName;
                $fileModel->save();
               
             if($request->user()->hasRole('company-admin')){
                $mailables = [
                    'company_name' => $request->company_name,
                    'subject' => 'Company Document Uploaded.',
                    'user_name' => auth()->user()->name,
                    'doc_type' => $request->doc_type,
                    'page' => 'Company'//is it from company page
                ];
                 Mail::to('mazisimsebele18@gmail.com')->send(new NotifySlotowMail($mailables));
             }
             return back()->with('success','Document uploaded successfully.');
             
            } catch (\Throwable $th) {
                return back()->with('error','Error uploading documents. Please contact development team.');
            }
        
         
    
    }

    public function destroy($id){
        $model = CompanyDocument::find($id);
        if(!is_null($model->document_file)){
            //unlink(public_path('storage/'.$model->document_file));
            $model->delete();
            return back()->with('success','Document removed successfully.');
        }
    }
}
