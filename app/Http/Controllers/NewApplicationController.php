<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\Nomination;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LiquorBoardRequest;

class NewApplicationController extends Controller
{
    public $get_reg_num_or_id_number = '';

    public function create(){
        
        if(request('variation') && request('variation') == 'Company'){
            $comp = Company::whereId(request('id'))->first(['reg_number']);
            $this->get_reg_num_or_id_number = $comp->reg_number;
          }elseif(request('variation') && request('variation') == 'Person'){
           $person = People::whereId(request('id'))->first(['id_or_passport']);
           $this->get_reg_num_or_id_number = $person->id_or_passport;
          }

        $persons = People::pluck('full_name','id');
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::orderBy('licence_type','ASC')->get();

        return Inertia::render('New Applications/Index',[
            'persons' => $persons,
             'companies' => $companies,
             'licence_dropdowns' => $licence_dropdowns,
             'get_reg_num_or_id_number' => $this->get_reg_num_or_id_number
        ]);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'trading_name' => 'required',
                'licence_type' => 'required|exists:licence_types,id',
                'belongs_to' => 'required|in:Company,Person',
                'board_region' => 'required' 
               ]);
        
               if($request->belongs_to === 'Company'){
                $request->validate(['company' => 'required|exists:companies,id']);
               }else{
                $request->validate(['person' => 'required|exists:people,id']);
               }
        
               $licence = Licence::create([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->belongs_to === 'Company' ? $request->company : NULL,
                'people_id' => $request->belongs_to === 'Person' ? $request->person: NULL,
                'board_region' => $request->board_region,
                'is_new_app' => true,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'slug' => sha1(now())
               ]);
               if($licence){
                return to_route('view_registration',['slug' => $licence->slug])->with('success','New App created successfully.');
               }
               
        } catch (\Throwable $th) {
            //throw $th;
           return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function update(Request $request,$slug){
        
      
        try {
            $request->validate([
                'trading_name' => 'required'
               ]);             
        
               $licence = Licence::whereSlug($slug)->update([
                'trading_name' => $request->trading_name,
                'licence_type_id' => $request->licence_type,
                'belongs_to' => $request->belongs_to,
                'company_id' => $request->company_id,
                'people_id' => $request->person_id,
                'board_region' => $request->board_region,
                'address' => $request->address,
                'address2' => $request->address2,
                'address3' => $request->address3,
                'province' => $request->province,
                'licence_number' => $request->licence_number,
                'client_number' => $request->client_number,
                'latest_renewal' => $request->latest_renewal,
                'licence_date' => $request->licence_date,
                'licence_issued_at' => $request->licence_date,
                'postal_code' => $request->postal_code,
               ]);
               if($licence){
                return back()->with('success','Licence updated successfully.');
               }
               
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','An error occured.Please try again.');
        }
       
    }

    public function view_registration(Request $request){
        $licence = Licence::with('company')->whereSlug($request->slug)->first();
        $client_quoted = LicenceDocument::where('document_type','Client Quoted')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Licence')->where('model_id',$licence->id)->get();

        $gba_application_form = LicenceDocument::where('document_type','GLB Application Forms')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $client_invoiced = LicenceDocument::where('document_type','Client Invoiced')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $application_forms = LicenceDocument::where('document_type','Application Forms')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $company_docs = LicenceDocument::where('document_type','Company Documents')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $cipc_docs = LicenceDocument::where('document_type','CIPC Documents')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $id_docs = LicenceDocument::where('document_type','ID Documents')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $police_clearance = LicenceDocument::where('document_type','Police Clearance')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']); 
        $tax_clearance = LicenceDocument::where('document_type','Tax Clearance')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']); 
        $lta_certificate = LicenceDocument::where('document_type','LTA Certificate')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']); 
        $shareholding_info = LicenceDocument::where('document_type','Shareholding Info')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $financial_interests = LicenceDocument::where('document_type','Financial Interests')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $_500m_affidavict  = LicenceDocument::where('document_type','500m Affidavit')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $government_gazette  = LicenceDocument::where('document_type','Government Gazette Adverts')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $advert_affidavict  = LicenceDocument::where('document_type','Advert Affidavit')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $proof_of_occupation  = LicenceDocument::where('document_type','Proof of Occupation')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $menu  = LicenceDocument::where('document_type','Menu')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $representations  = LicenceDocument::where('document_type','Representations')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $photographs  = LicenceDocument::where('document_type','Photographs')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $consent_letter  = LicenceDocument::where('document_type','Municipal Consent Ltr')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $zoning_certificate  = LicenceDocument::where('document_type','Zoning Certificate')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $local_authority = LicenceDocument::where('document_type','Local Authority Letter')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $mapbook_plans  = LicenceDocument::where('document_type','Mapbook Plans')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $google_map_plans  = LicenceDocument::where('document_type','Google Map Plans')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $description  = LicenceDocument::where('document_type','Description')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $site_plans  = LicenceDocument::where('document_type','Site Plans')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $dimensional_plans = LicenceDocument::where('document_type','Full Dimensioned Plans')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $advert_photographs  = LicenceDocument::where('document_type','Advert Photographs')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $newspaper_adverts  = LicenceDocument::where('document_type','Newspaper Adverts')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        
        
        $payment_to_liqour_board  = LicenceDocument::where('document_type','Payment To The Liquor Board')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $scanned_application  = LicenceDocument::where('document_type','Scanned Application')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $application_logded  = LicenceDocument::where('document_type','Application Lodged')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $initial_inspection_doc  = LicenceDocument::where('document_type','Initial Inspection')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $final_inspection_doc  = LicenceDocument::where('document_type','Final Inspection')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $client_finalisation  = LicenceDocument::where('document_type','Client Finalisation Invoiced')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $activation_fee_paid  = LicenceDocument::where('document_type','Activation Fee Paid')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $licence_issued_doc  = LicenceDocument::where('document_type','Licence Issued')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $licence_delivered  = LicenceDocument::where('document_type','Licence Delivered')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString();
        
        
        return Inertia::render('New Applications/Registration',[
            'licence' => $licence,
            'client_quoted' => $client_quoted,
             'gba_application_form' => $gba_application_form,
             'client_invoiced' => $client_invoiced,
            'application_forms' => $application_forms,
            'company_docs' => $company_docs,
            'cipc_docs' => $cipc_docs,
             'id_docs' => $id_docs,
            'police_clearance' => $police_clearance,
            'tax_clearance' => $tax_clearance,
            'lta_certificate' => $lta_certificate,
            'shareholding_info' => $shareholding_info,
            'financial_interests' => $financial_interests,
            '_500m_affidavict' => $_500m_affidavict,
            'government_gazette' => $government_gazette,
            'advert_affidavict' => $advert_affidavict,
            'proof_of_occupation' => $proof_of_occupation,
            'representations' => $representations,
            'photographs' => $photographs,
            'consent_letter' => $consent_letter,
            'zoning_certificate' => $zoning_certificate,
            'local_authority'  => $local_authority,
            'menu' => $menu,
            'mapbook_plans' => $mapbook_plans,
            'google_map_plans' => $google_map_plans,
            'description' => $description,
            'site_plans' => $site_plans,
            'dimensional_plans' => $dimensional_plans,
            'advert_photographs' => $advert_photographs,
            'newspaper_adverts' => $newspaper_adverts,
            'payment_to_liqour_board' => $payment_to_liqour_board,
            'scanned_application' => $scanned_application,
            'application_logded' => $application_logded,
            'initial_inspection_doc' => $initial_inspection_doc,
             'final_inspection_doc' => $final_inspection_doc,
            'client_finalisation' => $client_finalisation,
            'activation_fee_paid' => $activation_fee_paid,
            'licence_issued_doc' => $licence_issued_doc,
            'licence_delivered' => $licence_delivered,
            'liqour_board_requests' => $liqour_board_requests,
            'tasks' => $tasks]);
    }

    public function updateRegistration(Request $request, $slug){
       try {
        $licence = Licence::whereSlug($slug)->first();
        $status = '';
        if($request->status){
            if($request->unChecked){
                $status = intval($request->status[0]) - 1;
            }else{
                $status = $request->status[0];
            }
        }
        //Start new nomination
        if($status >= 15){
            $nom = Nomination::where('year',now()->format('Y'))->where('licence_id', $licence->id)->first();
            if(is_null($nom)){
                Nomination::create([//begin nomination
                    'licence_id' => $licence->id,
                    'year' => now()->format('Y'),
                    'slug' => sha1(now())
                ]);
                
            }

            //If stage is issued then its no longer a new app.
            $licence->update(['is_new_app' => false]);
        }

        $licence->update([
            'renewal_amount' => $request->renewal_amount,
            'status' => $status <= 0 ? NULL : $status,
           ]);
           
           return back()->with('success','Status Updated successfully');
       } catch (\Throwable $th) {
         //throw $th;
         return back()->with('error','An error occured while updating.');
       }
       
    }

public function updateRegistrationDate(Request $request, $slug)
{
    try {
        if($request->licence_issued_at){
              //If stage is issued then its no longer a new app.
              Licence::whereSlug($slug)->update(['is_new_app' => false, 'licence_date' => $request->licence_issued_at]);
              
        }
        Licence::whereSlug($slug)->update([
            'deposit_paid_at' => $request->deposit_paid_at,
            'liquor_board_at' => $request->liquor_board_at,
            'application_lodged_at' => $request->application_lodged_at,
            'renewal_amount' => $request->renewal_amount,
            'initial_inspection_at' => $request->initial_inspection_at,
            'final_inspection_at' => $request->final_inspection_at,
            'activation_fee_requested_at' =>$request->activation_fee_requested_at,
            'client_paid_at' => $request->client_paid_at,
            'activation_fee_paid_at' => $request->activation_fee_paid_at,
            'licence_issued_at' => $request->licence_issued_at,
            'licence_date' => $request->licence_issued_at,
            'licence_delivered_at' => $request->licence_delivered_at,
           ]);
       return back()->with('success','Date updated successfully.');
    } catch (\Throwable $th) {
        return back()->with('error','Error updating date.');
    }
}


}
