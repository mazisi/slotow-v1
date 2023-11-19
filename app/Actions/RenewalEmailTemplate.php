<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;

class RenewalEmailTemplate implements HasEmailTemplateInterface  {

    public function getView(Request $request)
    {
        return LicenceRenewal::with('licence')->where(function($query) use($request){
            $query->when($request->month, function($query) {                    
                $query->whereHas('licence', function($query) {
                    $query->whereMonth('licence_date', request('month'))
                    ->whereNull('deleted_at');
                });
            })
            ->when(request('province'), function ($query) use ($request) {
                $query->whereHas('licence', function($query) use($request){
                    $query->where('province',$request->province);
                });
               
            })
          ->when(!empty(request('stage')), function ($query) use ($request) {
            $query->where('status',$request->stage);
        });
        })->where(function($query){
            $query->where('status',1)
            ->orWhere('status',2)
            ->orWhere('status',4)
            ->orWhere('status',5);
        })
        ->whereNull('deleted_at')->paginate(20)->withQueryString();
    }
 
  function getMailTemplate($renewal){
    $template = '';

     if($renewal->status == '1'){//quoted
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>                   
      Please note that your Liquor Licence is due for renewal on the '.Carbon::parse($renewal->licence->licence_date)->format('jS').' of '.Carbon::parse($renewal->licence->licence_date)->format('F Y').'-
      attached please find a quote for us to attend to this renewal on your behalf.<br><br>
      Please ensure that payment is made before this to avoid penalties being implemented by the Liquor Board.<br><br>                    
      See our banking details below:<br>
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>
      We thank you for your continued support and look forward to assisting you with this process.<br><br>
                         
      Many thanks,
      '; 
  }elseif ($renewal->status == '2') {//Client Invoiced
      $template = 'Good Day '.$renewal->licence->trading_name.'.<br><br>
      Please note that your renewal is due.<br><br>
      <u>PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:</u><br><br>                    
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br>
      Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;18250500<br><br>Many thanks,
      ';

      $template = '<p>Good Day '.$renewal->licence->trading_name.'.</p>
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</p>
      <p><br>Please note that your renewal is due.<br><br><u>
      PLEASE NOTE THAT OUR BANKING DETAILS HAVE CHANGED, SEE BELOW:
      </u><br><br>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_ACCOUNT_HOLDER').'<br>
      Bank:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.env('BANK_NAME').'<br>
      Account No:&nbsp;&nbsp;&nbsp;&nbsp;'.env('ACCOUNT_NUMBER').'<br><br>Many thanks,</p>';
      
  }elseif ($renewal->status == '3') {//Client Paid
      $template = 'Good Day '.$renewal->licence->trading_name.'.
      <p>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</p>
      <p>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</p>
      <p>Please see attached proof of payment to the Liquor Board. Note that we have not as yet received the renewal certificate from the Board.</p>
      <p>Please ensure that the attached document in on display in the interim. We will advise as soon 
      as the renewal has been issued.</p>
      <br><br>Many thanks,';

      
  }elseif ($renewal->status == '5') {//issued
     
      $template = '<div><div>Good Day '.$renewal->licence->trading_name.',<br>
      <div>Licence Number:&nbsp; '.$renewal->licence->licence_number.'.</div>
      <div>Licence Date:&nbsp; &nbsp; &nbsp; &nbsp; '.Carbon::parse($renewal->licence->licence_date)->format('d/m').'</div>
      </div><br><div>Please see attached copy of the latest renewal certificate.</div>
      <br><div>The original will be delivered in due course.</div><br><br><div>Many thanks,</div></div>';
      
  }else{
      return back()->with('error','An unknown error ocurred');
  }  

  return $template;
  }


}

