<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Http\Controllers\Controller;

class CompanyRegistrationController extends Controller
{
    public function registration(Request $request){
        $licence = Licence::with('company')->whereSlug($request->slug)->first();

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
        $activation_fee_requested_doc  = LicenceDocument::where('document_type','Activation Fee Requested')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $client_finalisation  = LicenceDocument::where('document_type','Client Finalisation Invoiced')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $activation_fee_paid  = LicenceDocument::where('document_type','Activation Fee Paid')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $licence_issued_doc  = LicenceDocument::where('document_type','Licence Issued')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
        $licence_delivered  = LicenceDocument::where('document_type','Licence Delivered')->where('licence_id',$licence->id)->first(['id','document_name', 'document_file']);
               
        
        return Inertia::render('CompanyAdminDash/Registration/Registration',[
            'licence' => $licence,
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
            'activation_fee_requested_doc' => $activation_fee_requested_doc,
            'client_finalisation' => $client_finalisation,
            'activation_fee_paid' => $activation_fee_paid,
            'licence_issued_doc' => $licence_issued_doc,
            'licence_delivered' => $licence_delivered]);
    }
}
