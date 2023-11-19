<?php

namespace App\Actions\EmmailCommsHandlers;

use App\Mail\NewAppsMailer;
use Throwable;
use Illuminate\Http\Request;
use App\Models\Licence;
use App\Models\LicenceDocument;
use Illuminate\Support\Facades\Mail;


class HandleNewAppMail {

//The following are status keys
  // 1. Client Quoted
  // 2. Deposit Invoice
  // 3. Deposit Paid
  // 4. Payment to the Liquor Board
  // 5. Prepare New Application
  // 6. Scanned Application
  // 7. Application Lodged
  // 8. Initial Inspection
  // 9. Liquor Board Requests
  // 10. Final Inspection
  // 11. Activation Fee Requested
  // 12. Client Finalisation Invoice
  // 13. Client Paid
  // 14. Activation Fee Paid
  // 15. Licence Issued
  // 16. Licence Delivered 

  public function dispatchNewAppMail(Request $request){
    try {
        $licence = Licence::whereSlug($request->licence_slug)->firstOrFail();

        if($licence->belongs_to == 'Company'){

           if(is_null($licence->company->email)){
             return back()->with('error','Mail NOT sent. Primary email address not found.');
            }

        }else{

            if(is_null($licence->people->email_address_1)){
              return back()->with('error','Mail NOT sent. Primary email address not found.');
            }
        }
        
      
        $licence_stage = '';  
        
        switch ($licence->status) {            
            case '1':
                $licence_stage = 'Client Quoted';                
                break;
            case '2':
                $licence_stage = 'Client Invoiced';
                break;
            case '4':
                $licence_stage = 'Payment to the Liquor Board';
                break;
            case '7':
                $licence_stage = 'Application Lodged';
                break;
            case '8':
                $licence_stage = 'Initial Inspection';
                break;
            case '10':
                $licence_stage = 'Final Inspection';
                break;
            case '12':
                $licence_stage = 'Client Finalisation Invoice';
                break;
            case '14':
                $licence_stage = 'Activation Fee Paid';
                break;
            case '15':
                $licence_stage = 'Licence Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }
        
        $get_doc = LicenceDocument::where('licence_id',$licence->id)->where('document_type',$licence_stage)->first();
        
        
        if(is_null($get_doc)){
            $error_message = 'Quote Document Not Uploaded';                     
            return back()->with('error','Mail NOT SENT!!!!.Quote Document is not yet uploaded.');
        }
        
        $this->handle($licence, $get_doc->document_file);
        return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($licence, $error_message, $licence_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}


function handle($licence, $doc_type) {

    $full_document_path = env('BLOB_FILE_PATH').$doc_type;

    if($licence->belongs_to == 'Company'){
        $this->emailCompany($licence, $full_document_path);
    }else{
        $this->emailPerson($licence, $full_document_path);
    }
        
  }


  function emailPerson($licence, $full_document_path) {
    $email = $licence->people->email_address_1; //primary email
    $email1 = $licence->people->email_address_2;
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }

    if(! is_null($email) && $email1){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new NewAppsMailer($licence, request('mail_body'), $full_document_path)); 
    }

    elseif($email && !$email1){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new NewAppsMailer($licence, request('mail_body'), $full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }

    
}


function emailCompany($licence, $full_document_path) {

    $email = $licence->company->email; //primary email
    $email1 = $licence->company->email1;
    $email2 = $licence->company->email2;
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])->send(new NewAppsMailer($licence, request('mail_body'), $full_document_path)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new NewAppsMailer($licence, request('mail_body'), $full_document_path));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new NewAppsMailer($licence, request('mail_body'), $full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }

    
}


}