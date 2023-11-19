<?php

namespace App\Actions;

use Carbon\Carbon;

class TemporalLicenceEmailTemplate implements HasEmailTemplateInterface  {


//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Temporary Licence Documents 
// 5 => Payment To The Liquor Board 
// 6 => Scanned Application
// 7 => Temporary Licence Lodged 
// 8 => Temporary Licence Issued 
// 9 => Temporary Licence Delivered

  function getMailTemplate($temp_licence){
    $template = '';

     if($temp_licence->status == '1'){//quoted
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached quotation along with the basic requirements for a temporary licence application.</p>
      <p>Note that this application needs to be submitted at least two weeks prior to the event allowing the Liquor Board sufficient time to have this processed and approved.</p>
      <p>Many thanks,</p>'; 

  }elseif ($temp_licence->status == '2') {//Client Invoiced
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached invoice in respect of the temporary licence application.</p>
      <p>Many thanks,</p>';

      
  }elseif ($temp_licence->status == '5') {//Liquor Board
     
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached proof of payment in respect of the temporary licence
      application. We will advise as soon as this application has been lodged and
      furnish you with the necessary documents.</p>
      <p>Many thanks,</p>';

    }elseif ($temp_licence->status == '7') {//Lodged
     
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached proof of lodgement in respect of the temporary licence application. We will advise immediately once the certificate is issued.</p>
      <p>Many thanks,</p>';

    }elseif ($temp_licence->status == '8') {//Issued
     
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>We are happy to inform you that the temporary licence application has been approved! Please see attached copy of the certificate.</p>
      <p>The original will be delivered in due course.</p>
      <p>Many thanks,</p>';

  }else{
    return back()->with('error','No template found for this stage.');
  }  

  return $template;
  }


}

