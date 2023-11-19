<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Nomination;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\NominationDocument;
use App\Http\Controllers\Controller;

class NominationController extends Controller
{
    public function index() {
        $licence = Licence::with('nominations')->whereSlug(request('slug'))->first();
        return Inertia::render('CompanyAdminDash/Nominations/Nomination',['licence' => $licence]);
    }

    public function show($slug) {
        $nomination = Nomination::with('licence','people','merged_document')->whereSlug($slug)->first();
        
        $client_quoted = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Client Quoted')->first();
        $client_invoiced = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Client Invoiced')->first();
        $liquor_board = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Payment To The Liquor Board')->first();
        $nomination_forms = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Forms')->first();
        $proof_of_payment = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Proof of Payment')->first();
        $attorney_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Power of Attorney')->first();
        $certified_id_doc =  NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','ID Document')->first();
        $police_clearance_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Police Clearances')->first();
        $latest_renewal_doc = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Latest Renewal/Licence')->first();
        $nomination_logded = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Lodged')->first();
        $scanned_app = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Scanned Application')->first();
        $nomination_issued = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Issued')->first();
        $nomination_delivered = NominationDocument::where('nomination_id',$nomination->id)->where('doc_type','Nomination Delivered')->first();
        $latest_renewal_licence_doc = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$nomination->licence_id)->first(['document_file']);
        

        return Inertia::render('CompanyAdminDash/Nominations/ViewNomination',[
                'nomination' => $nomination,
                'client_quoted' => $client_quoted,
                'client_invoiced' => $client_invoiced,
                'liquor_board' => $liquor_board,
                'nomination_forms' => $nomination_forms,
                'proof_of_payment' => $proof_of_payment,
                'attorney_doc' => $attorney_doc,
                'certified_id_doc' => $certified_id_doc,
                'police_clearance_doc' => $police_clearance_doc,
                'latest_renewal_doc' => $latest_renewal_doc,
                'nomination_logded' => $nomination_logded,
                'nomination_issued' => $nomination_issued,
                'nomination_delivered' => $nomination_delivered,
                'scanned_app' => $scanned_app,
                'latest_renewal_licence_doc' => $latest_renewal_licence_doc,
                ]);
    }
}
