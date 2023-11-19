<?php

namespace App\Actions;

use Carbon\Carbon;

class AlterationEmailTemplate implements HasEmailTemplateInterface  {

     //Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered

  function getMailTemplate($renewal){
    $template = '';

     if($renewal->status == '1'){//quoted
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached quotation and below basic requirements for an&nbsp;alteration&rsquo;s application:</p>
      <p>&nbsp; &nbsp; &nbsp; &nbsp;- Fully dimensioned plans of the alterations to the premises</p>
      <p>&nbsp; &nbsp; &nbsp; &nbsp;- Photographs of the premises</p>
      <p>&nbsp; &nbsp; &nbsp; &nbsp;- A brief description depicting what alterations are being made.</p>
      <p>Note that the Liquor Board is currently running at a massive delay due to and applications may take anywhere from 8 to 16 months to be approved.</p>
      <p>Once the application has been lodged, we will furnish you with the necessary documents to have on display in the interim to avoid any problems with inspectors.</p>
      <p><br>Many thanks,</p>'; 

  }elseif ($renewal->status == '2') {//Client Invoiced
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached invoice in respect of the alteration’s application.</p>
      <p><br>Many thanks,</p>';
      
  }elseif ($renewal->status == '5') {//Payment To the liquour board
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached proof of payment to the Liquor Board in respect of the alteration’s application. Please ensure that this document is on display until
       you are furnished with the proof of lodgement.</p>
      <p><br>Many thanks,</p>';

      
  }elseif ($renewal->status == '6') {//Lodged
     
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>Please see attached proof of lodgement in respect of the alteration’s application. Please ensure that this is on display until the certificate has been
       issued.</p>
      <p><br>Many thanks,</p>';

    }elseif ($renewal->status == '7') {//Issued
     
      $template = '<p>Good day,</p>
      <p>I hope you are well.</p>
      <p>We are happy to advise you that the alteration’s application has been
      approved! Please see attached copy of the alteration’s certificate.</p>
      <p>The original will be delivered in due course. Please ensure you have this
      certificate on display once received.</p>
      <p>Many thanks,</p>'; 

  }else{
    return back()->with('error','No template found for this stage.');      
  }  

  return $template;
  }


}

