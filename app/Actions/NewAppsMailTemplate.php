<?php

namespace App\Actions;

use Carbon\Carbon;

class NewAppsMailTemplate implements HasEmailTemplateInterface  {

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


  function getMailTemplate($licence){
    $template = '';

     if($licence->status == '1'){//quoted
      $template = 'Good day,<br><br>                   
      I hope you are well.<br><br>
      Please find basic application requirements together with a quote for a tavern
      and a liquor store. Two quotes have been attached, the first quote is for the
      deposit, the second quote is the finalisation quote which you pay when your
      licence is ready.<br><br>
      Kindly note that we will first have to ensure that the premises are correctly
      zoned for a pub liquor licence to be applied for. In the interim, send us your
      complete address in order for us to establish what you are zoned for.<br><br>

      Please note that the time frame from the Liquor Board in issuing a licence
      prior to Covid-19 was 4-6 months.<br><br>

      The Liquor Board is currently running at approximately a year to 16 months
      behind for all new application and unfortunately trade cannot commence
      until the licence has been granted and issued.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($licence->status == '2') {//Client Invoiced
    $template = 'Good day,<br><br>                   
    I hope you are well.<br><br>
    Please see attached invoice in respect of the new licence application.<br><br>
                       
    Many thanks,
    '; 
      
  }elseif ($licence->status == '4') {//Payment to the Liquor Board
    $template = 'Good day,<br><br>                   
    I hope you are well.<br><br>
    Please see attached proof of payment to the Liquor Board in respect of the
    new application. Please ensure that this document is on display until you are
    furnished with the proof of lodgement.<br><br>
                       
    Many thanks,
    '; 
      
  }elseif ($licence->status == '7') {//Lodged
     
    $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please see attached proof of lodgement in respect of the new application.
                Please ensure that this is on display until the certificate has been issued.<br><br>
                       
                Many thanks,
                '; 
  }elseif ($licence->status == '8') {//Initial Inspection

    $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please note that we have received notice from the Liquor Board that initial
                inspection will be conducted for the new application.<br><br>
                Please ensure you have that no liquor is on the premises or on display as this
                will negatively impact the application.<br><br>
                        
                Many thanks,
                '; 

  }elseif ($licence->status == '10') {//Final Inspection

      $template = 'Good day,<br><br>                   
                I hope you are well.<br><br>
                Please note that the Liquor Board is looking to finalise the new licence
                application and will be conducting final inspection.<br><br>
                Please ensure that no liquor is on the premises or on display as this will
                negatively impact the application.<br><br>
                        
                Many thanks,
                '; 
  }elseif ($licence->status == '12') {//Client Finalisation Invoice

    $template = 'Good day,<br><br>                   
              I hope you are well.<br><br>
              We are happy to advise you that the Liquor Board has granted this licence
              and has advised us to pay the activation fee.<br><br>
              Please see attached finalisation invoice, once payment or proof of has been
              received the activation fee for the licence will be paid and submitted to the
              Liquor Board.<br><br>
                      
              Many thanks,
              '; 
}elseif ($licence->status == '14') {//Activation Fee Paid

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            Please see attached proof of payment to the Liquor Board for the activation
            fee.<br><br>
            Please ensure this is on display until the licence has been issued.<br><br>
                    
            Many thanks,
            '; 
}elseif ($licence->status == '15') {//Issued

  $template = 'Good day,<br><br>                   
            I hope you are well.<br><br>
            We are happy to advise you that the new application has been approved and
            issued! Please see attached copy of the original licence.<br><br>
            The original will be delivered in due course. Please ensure you have this
            certificate on display once received.<br><br>
                    
            Many thanks,
            '; 
      
  }else{
      return back()->with('error','Error! Please check licence stages');
  }  

  return $template;
  }


}

