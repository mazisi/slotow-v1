<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Models\Email;
use App\Mail\TransferMailer;
use Illuminate\Http\Request;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use Illuminate\Support\Facades\Mail;


class HandleTransferMail { 

    public function dispatchTransferMail(Request $request){
        try {    

            $transfer = LicenceTransfer::with('licence.company')->whereSlug($request->transfer_slug)->firstOrFail();
            
            if(is_null($transfer->licence->company->email)){
                return back()->with('error','Mail NOT sent. Primary email address not found.');
            }
            switch ($transfer->status) {            
                case '1':                
                    $stage = 'Client Quoted';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '2':
                    $stage = 'Client Invoiced';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '3':
                    $stage = 'Client Paid';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '5':
                    $stage = 'Payment To The Liquor Board';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;

                case '6':
                    $stage = 'Scanned Application';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '7':
                    $stage = 'Transfer Logded';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '8':
                    $stage = 'Activation Fee Paid';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '9':
                    $stage = 'Transfer Issued';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                case '10':
                    $stage = 'Transfer Delivered';
                    $get_doc = $this->getDocumentType($transfer,$stage);
                    break;
                default:
                   return back()->with('error','Could not send email.');
               
            }
          
            

            if(is_null($get_doc)){                        
                return back()->with('error','Please upload required document.');
            }
            
            $this->handle($transfer, $get_doc);
               

 
         return back()->with('success','Mail sent successfully.');


        } catch (\Throwable $th) {throw $th;
            $error_message = '500....Server Error.';
            //$this->insertUnsentEmails($transfer, $error_message, $stage);  
            return back()->with('error','An error occured while sending email.');
        }
       
        
    }

    function handle($transfer, $get_doc){

        $document_full_path = env('BLOB_FILE_PATH').$get_doc->document;

        if($transfer->licence->belongs_to == 'Company'){
            $this->emailCompany($transfer, $document_full_path);
        }else{
            $this->emailPerson($transfer, $document_full_path);
        }

        
    }



    function emailPerson($transfer, $document_full_path) {
        $email = $transfer->licence->people->email_address_1; //primary email
        $email1 = $transfer->licence->people->email_address_2;
       
        
        if(! is_null($email) && $email1){
            Mail::to($email)
            ->cc([$email1,'info@slotow.co.za'])
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new TransferMailer($transfer, request('mail_body'), $document_full_path)); 
        }
    
        elseif($email && !$email1){
                Mail::to($email)
                ->cc('info@slotow.co.za')
                ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
                ->send(new TransferMailer($transfer, request('mail_body'), $document_full_path));
        }else{
            return back()->with('error','Mail NOT sent. Company does not have email addresses.');
        }
    
        
    }
    
   function emailCompany($transfer, $document_full_path) {
    
    $email = $transfer->licence->company->email;
    $email1= $transfer->licence->company->email1;
    $email2 = $transfer->licence->company->email2;

    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])
        ->send(new TransferMailer($transfer, request('mail_body'), $document_full_path)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new TransferMailer($transfer, request('mail_body'), $document_full_path));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new TransferMailer($transfer, request('mail_body'), $document_full_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
   }
    function getDocumentType($transfer, $doc_type){
        $transfer_document = TransferDocument::where('licence_transfer_id',$transfer->id)->where('doc_type',$doc_type)->first();
        return $transfer_document;
    }

}