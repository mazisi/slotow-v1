<?php

namespace App\Actions\EmmailCommsHandlers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use App\Mail\TemporalLicenceMailer;
use Illuminate\Support\Facades\Mail;
use App\Models\TemporalLicenceDocument;


class HandleTemporalLicenceMail {

    //Status keys:
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Temporary Licence Documents 
// 5 => Payment To The Liquor Board 
// 6 => Scanned Application
// 7 => Temporary Licence Lodged 
// 8 => Temporary Licence Issued 
// 9 => Temporary Licence Delivered

  public function dispatchTemporalMail(Request $request){
    try {
        $temporal_licence = TemporalLicence::whereSlug($request->temp_licence_slug)->firstOrFail();
 
        if(is_null($temporal_licence->company->email)){
            return back()->with('error','Mail NOT sent. Primary email not found.');
        }
        
        switch ($temporal_licence->status) {      
            case '1':                
                $get_doc = TemporalLicenceDocument::where('temporal_licence_id',$temporal_licence->id)->where('doc_type','Client Quoted')->first(['document']);
               break;
            case '2':
                $get_doc = TemporalLicenceDocument::where('temporal_licence_id',$temporal_licence->id)->where('doc_type','Client Invoiced')->first(['document']);
                break;
            case '5':
                $get_doc = TemporalLicenceDocument::where('temporal_licence_id',$temporal_licence->id)->where('doc_type','Payment To The Liquor Board')->first(['document']);
                break;
            case '7':
                $get_doc = TemporalLicenceDocument::where('temporal_licence_id',$temporal_licence->id)->where('doc_type','Licence Lodged')->first(['document']);
                break;
            case '8':
                $get_doc = TemporalLicenceDocument::where('temporal_licence_id',$temporal_licence->id)->where('doc_type','Licence Issued')->first(['document']);
                break;
            default:
               return to_route('get_temp_licences')->with('error','Licence stage out of emmail comms range.');
            //   return 'Could not locate document.';
            break;
        }
        if(is_null($get_doc)){            
            return back()->with('error','Mail NOT SENT!.Could not locate document.');
        }        
        
        $this->handle($temporal_licence, $get_doc->document);
       return back()->with('success','Mail sent successfully.');

    } catch (Throwable $th) {throw $th;
        $error_message = 'Server Error.';
        echo $error_message;
      // $this->insertUnsentEmails($temporal_licence, $error_message, $temp_licence_stage);  
      //return back()->with('error','An error occured while sending email.');
    }
   
    
}

  function handle($temporal_licence, $doc_path) {
    
    $document_full_path= env('BLOB_FILE_PATH').$doc_path;

    if($temporal_licence->belongs_to == 'Company'){
        $email = $temporal_licence->company->email; //primary email for company
        $email1 = $temporal_licence->company->email1;
        $email2 = $temporal_licence->company->email2;
        $this->checkCompanyEmailAddresses($email, $email1, $email2, $temporal_licence, $document_full_path);

    }else{
        $email = $temporal_licence->people->email_address_1; //primary email for a person
        $email1 = $temporal_licence->people->email_address_2;
        $this->checkPersonEmailAddresses($email, $email1, $temporal_licence, $document_full_path);
    }
    
    //If there is no primary email
    if(! $email){
        return back()->with('error','Mail NOT sent. Primary email not found.');
    }

  }

  function checkCompanyEmailAddresses($primaryEmail=null, $secondaryEmail=null,$thirdEmail=null, $temporal_licence,$document_full_path) {
    
            if(! is_null($primaryEmail) && $secondaryEmail && $thirdEmail){
                Mail::to($primaryEmail)
                ->cc([$secondaryEmail,'info@slotow.co.za'])
                ->bcc([$thirdEmail,'sales@slotow.co.za','info@goverify.co.za'])
                ->send(new TemporalLicenceMailer($temporal_licence, request('mail_body'), $document_full_path)); 
            }

            elseif($primaryEmail && $secondaryEmail && !$thirdEmail){
                Mail::to($primaryEmail)
                ->cc([$secondaryEmail, 'info@slotow.co.za'])
                ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
                ->send(new TemporalLicenceMailer($temporal_licence, request('mail_body'), $document_full_path));
            }
            
            elseif($primaryEmail && !$secondaryEmail && !$thirdEmail){
                    Mail::to($primaryEmail)
                    ->cc('info@slotow.co.za')
                    ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
                    ->send(new TemporalLicenceMailer($temporal_licence, request('mail_body'), $document_full_path));
            }else{
                return back()->with('error','Mail NOT sent. Company does not have email addresses.');
            }
 }


  function checkPersonEmailAddresses($primaryEmail=null, $secondaryEmail=null, $temporal_licence, $document_full_path) {
    
        if(! is_null($primaryEmail) && $secondaryEmail){
            Mail::to($primaryEmail)
            ->cc([$secondaryEmail,'info@slotow.co.za'])
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new TemporalLicenceMailer($temporal_licence, request('mail_body'),$document_full_path)); 
        }

        elseif($primaryEmail && !$secondaryEmail){
            Mail::to($primaryEmail)
            ->cc('info@slotow.co.za')
            ->bcc(['sales@slotow.co.za','info@goverify.co.za'])
            ->send(new TemporalLicenceMailer($temporal_licence, request('mail_body'),$document_full_path));

        }else{
            return back()->with('error','Mail NOT sent. This company does not have email addresses.');
        }
  }


}