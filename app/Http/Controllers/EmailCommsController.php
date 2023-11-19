<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceTransfer;
use App\Models\TemporalLicence;
use App\Actions\NewAppsMailTemplate;
use App\Actions\RenewalEmailTemplate;
use App\Actions\TransferMailTemplate;
use App\Actions\NominationMailTemplate;
use App\Actions\AlterationEmailTemplate;
use App\Actions\TemporalLicenceEmailTemplate;

class EmailCommsController extends Controller
{
   public function index(Request $request){

            $renewals = (new RenewalEmailTemplate)->getView($request);

        return Inertia::render('EmailComms/EmailComm',['renewals' => $renewals]);
    }


  
    public function getMailTemplate($slug,$comm_variation){         
        switch ($comm_variation) {            
            case 'renewals': 
                $licence = LicenceRenewal::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new RenewalEmailTemplate)->getMailTemplate($licence);            
                return Inertia::render('EmailComms/MailTemplate',['licence' => $licence,'template' => $template]);
                break;

            case 'alterations':
                $licence = Alteration::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new AlterationEmailTemplate)->getMailTemplate($licence); 
                return Inertia::render('EmailComms/AlterationTemplate',['licence' => $licence,'template' => $template]);
                break;

            case 'temporary-licences':
                $licence = TemporalLicence::whereSlug($slug)->firstOrFail();   
                $template = (new TemporalLicenceEmailTemplate)->getMailTemplate($licence); 
                return Inertia::render('EmailComms/TemporalLicenceTemplate',['licence' => $licence,'template' => $template]);
                break;

            case 'transfers':
                $licence = LicenceTransfer::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new TransferMailTemplate)->getMailTemplate($licence);     
                return Inertia::render('EmailComms/TransferTemplate',['licence' => $licence,'template' => $template]);
                break;
                
            case 'nominations': 
                $nomination = Nomination::with('licence')->whereSlug($slug)->firstOrFail();   
                $template = (new NominationMailTemplate)->getMailTemplate($nomination);             
                return Inertia::render('EmailComms/NominationTemplate',['licence' => $nomination,'template' => $template]);
                break;
            case 'new-apps':
                $new_app = Licence::whereSlug($slug)->firstOrFail();   
                $template = (new NewAppsMailTemplate)->getMailTemplate($new_app);             
                return Inertia::render('EmailComms/NewAppsMailTemplate',['licence' => $new_app,'template' => $template]);
                break;
    
            default:
            return back()->with('error','An error occured.Please try again.');
                break;
        }
    }


}
