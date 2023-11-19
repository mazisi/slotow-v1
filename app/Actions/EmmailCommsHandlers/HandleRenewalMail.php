<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Mail\RenewalMailer;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\RenewalDocument;
use Illuminate\Support\Facades\Mail;


class HandleRenewalMail {

  public function dispatchRenewalMail(Request $request){
      
      
    try {
        $renewal = LicenceRenewal::with('licence.company')->whereSlug($request->renewal_slug)->firstOrFail();
        
        if($renewal->licence->belongs_to == 'Company'){

            if(is_null($renewal->licence->company->email)){
              return back()->with('error','Mail not sent. This company does not have primary email.');
             }
 
         }else{
            if(is_null($renewal->licence->people->email_address_1)){
                return back()->with('error','Mail not sent. This person does not have primary email.');
            }
         }

        $renewal_stage = '';  

        
        switch ($renewal->status) {            
            case '1':
                $renewal_stage = 'Client Quoted';                
                break;
            case '2':
                $renewal_stage = 'Client Invoiced';
                break;
            case '3':
                $renewal_stage = 'Client Paid';
                break;
            case '5':
                $renewal_stage = 'Renewal Issued';
                break;
            default:
            return back()->with('error','Could not send email.');
                break;
        }

        $get_doc = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type',$renewal_stage)->first();
       
        if(is_null($get_doc)){              
            return back()->with('error','Mail NOT SENT!.Document is not uploaded.');
        }   
        
        
        $this->handle($renewal, $get_doc->document);
        
        //if mail sent then update is quote sent for reporting purposes
        $renewal->update(['is_quote_sent' => 'true']);

       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($renewal, $error_message, $renewal_stage);  
      return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handle($renewal,$document) {

    $full_document_path = env('BLOB_FILE_PATH').$document;

    if($renewal->licence->belongs_to == 'Company'){
        $this->emailCompany($renewal, $full_document_path);
    }else{
        $this->emailPerson($renewal, $full_document_path);
    }
    
  }

  function emailPerson($renewal, $full_document_path) {
    $email = $renewal->licence->people->email_address_1; //primary email
    $email1 = $renewal->licence->people->email_address_2;
   
    
    if(! is_null($email) && $email1){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'), $full_document_path)); 
    }

    elseif($email && !$email1){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new RenewalMailer($renewal, request('mail_body'), $full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }

    
}

  function emailCompany($renewal, $full_document_path){

    $email = $renewal->licence->company->email; //primary email
    $email1 = $renewal->licence->company->email1;
    $email2 = $renewal->licence->company->email2;
  

    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new RenewalMailer($renewal, request('mail_body'),$full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
    
  }
}