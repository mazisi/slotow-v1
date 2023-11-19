<?php

namespace App\Actions;

use Carbon\Carbon;


class NominationMailTemplate implements HasEmailTemplateInterface{
 
  function getMailTemplate($nomination){
    $template = '';
    if($nomination->status == '1'){//quoted
      $template = '<p dir="ltr">Good Day '.$nomination->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$nomination->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($nomination->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached quotation along with the basic requirements for an appointment of managers application.</p>
      <p dir="ltr">Note that the Liquor Board is currently running at a delay due to COVID-19 and applications may take anywhere from 6 to 12 months to be approved. Once the application has been lodged, please ensure the necessary documents are on display in the interim to avoid any problems with inspectors.&nbsp;</p>
      <p dir="ltr"><strong>&nbsp;</strong></p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>
      '; 
  }elseif ($nomination->status == '2') {//Client Invoiced
      $template = '<p dir="ltr">Good Day '.$nomination->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$nomination->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($nomination->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached invoice in respect of the appointment of managers application. Note that the application will only be lodged once payment or proof of has been received.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>';
  }elseif ($nomination->status == '4') {//Payment to the Liquor Board
      $template = '<p dir="ltr">Good Day,</p>
      <p>Licence Number:&nbsp; '.$nomination->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($nomination->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached proof of payment in respect of the appointment of managers application. Please ensure this is on display in the interim to avoid any issues with the liquor inspectors.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>';
      
  }elseif ($nomination->status == '8') {//Logded
     
      $template = '<p dir="ltr">Good Day '.$nomination->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$nomination->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($nomination->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">Please see attached proof of lodgement in respect of the appointment of managers application. Please ensure that this is on display in the interim until the application has been approved.</p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>';
  }elseif ($nomination->status == '9') {//issued
      $template = '<p dir="ltr">Good Day '.$nomination->licence->trading_name.',</p>
      <p>Licence Number:&nbsp; '.$nomination->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($nomination->licence->licence_date)->format('d/m').'</p>
      <p dir="ltr">We are happy to inform you that the appointment of managers application has been approved! Please see attached copy of the certificate.</p>
      <p dir="ltr">The original will be delivered in due course</p>
      <p dir="ltr"><strong>&nbsp;</strong></p>
      <p dir="ltr">Many thanks,</p>
      <p dir="ltr">&nbsp;</p>'; 
  
  }else{
      return back()->with('error','An unknown error ocurred');
  }   

  return $template;
  }

}