<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use App\Models\Email;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Mail\AlterationMailer;
use App\Models\AlterationDocument;
use Illuminate\Support\Facades\Mail;


class HandleAlterationMail {

    //Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered

  public function dispatchAlterationMail(Request $request){
    try {
        $alteration = Alteration::with('licence.company')->whereSlug($request->alteration_slug)->firstOrFail();
        
        if($alteration->licence->belongs_to == 'Company'){

            if(is_null($alteration->licence->company->email)){
              return back()->with('error','Mail not sent. This company does not have primary email.');
             }
 
         }else{
            if(is_null($alteration->licence->people->email_address_1)){
                return back()->with('error','Mail not sent. This person does not have primary email.');
            }
         }

        
        switch ($alteration->status) {      
            case '1':                
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Quoted')->first();
               break;
            case '2':
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Invoiced')->first();
                break;
            case '4':
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment To The Liquor Board')->first();
                break;
            case '5':
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment to the Liquor Board-2')->first();
                break;
            case '6':
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Lodged')->first();
                break;
            case '7':
                $get_doc = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Certificate Issued')->first();
                break;
            default:
               return to_route('get_alterations')->with('error','Could not locate stage.');
            
            break;
        }

  
        if(is_null($get_doc)){
            return back()->with('error','Mail NOT SENT!. Document not uploaded.');
         }        
        
         $this->handle($alteration, $get_doc->path);
         return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($alteration, $error_message, $alteration_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handle($alteration, $document) {

    $full_document_path = env('BLOB_FILE_PATH').$document;

    if($alteration->licence->belongs_to == 'Company'){
        $this->emailCompany($alteration, $full_document_path);
    }else{
        $this->emailPerson($alteration, $full_document_path);
    }
    
   
  }


  function emailPerson($alteration, $full_document_path) {
    $email = $alteration->licence->people->email_address_1; //primary email
    $email1 = $alteration->licence->people->email_address_2;
   
    
    if(! is_null($email) && $email1){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new AlterationMailer($alteration, request('mail_body'), $full_document_path)); 
    }

    elseif($email && !$email1){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new AlterationMailer($alteration, request('mail_body'), $full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }

    
}


function emailCompany($alteration,$full_document_path) {

    $email = $alteration->licence->company->email; //primary email
    $email1 = $alteration->licence->company->email1;
    $email2 = $alteration->licence->company->email2;;
    

    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }


    if(! is_null($email) && $email1 && $email2){
        Mail::to($email)
        ->cc([$email1,'info@slotow.co.za'])
        ->bcc([$email2,'sales@slotow.co.za','info@goverify.co.za'])
        ->send(new AlterationMailer($alteration, request('mail_body'),$full_document_path)); 
    }

    elseif($email && $email1 && !$email2){
        Mail::to($email)
        ->cc([$email1, 'info@slotow.co.za'])
        ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
        ->send(new AlterationMailer($alteration, request('mail_body'), $full_document_path));
    }
    
    elseif($email && !$email1 && !$email2){
            Mail::to($email)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new AlterationMailer($alteration, request('mail_body'),$full_document_path));
    }else{
        return back()->with('error','Mail NOT sent. Company does not have email addresses.');
    }
    
}
}