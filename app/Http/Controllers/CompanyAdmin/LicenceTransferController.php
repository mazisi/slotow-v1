<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Http\Controllers\Controller;
use App\Models\Company;

class LicenceTransferController extends Controller
{
    public function index(Request $request){
        $licence = Licence::whereSlug($request->slug)->first(['trading_name','slug']);
      
      $transfers = LicenceTransfer::with('old_person','new_person','old_company','new_company')
          ->whereHas('licence', function ($query) use($request) {
            $query->where('slug',$request->slug);
          })->get();

        return Inertia::render('CompanyAdminDash/Transfers/MyTransfers',['licence' => $licence, 'transfers' => $transfers]);
    }

    public function view_my_transfer($slug) {
        $view_transfer = LicenceTransfer::with('old_person','new_person','old_company','new_company','licence')->whereSlug($slug)->first();
        $get_current_company = Company::whereId($view_transfer->company_id)->first(['name']);
        $old_transfer_forms = TransferDocument::where('doc_type','Transfer Forms')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_transfer_forms = TransferDocument::where('doc_type','Transfer Forms')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $smoking_affidavict = TransferDocument::where('doc_type','Smoking Affidavit')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_poa_res_docs = TransferDocument::where('doc_type','POA & RES')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_poa_res_docs = TransferDocument::where('doc_type','POA & RES')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_shareholding = TransferDocument::where('doc_type','Shareholding')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_shareholding = TransferDocument::where('doc_type','Shareholding')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $old_cipc_certificate = TransferDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Old Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $current_cipc_certificate = TransferDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $company_docs = TransferDocument::where('doc_type','Company Documents')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $id_docs = TransferDocument::where('doc_type','ID Documents')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $police_clearance = TransferDocument::where('doc_type','Police Clearances')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $tax_clearance = TransferDocument::where('doc_type','Tax Clearance')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $lta_certificate = TransferDocument::where('doc_type','LTA Certificate')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $financial_interest = TransferDocument::where('doc_type','Financial Interests')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $landloard_letter = TransferDocument::where('doc_type','Lease/Landlord Letter')->where('belongs_to','Current Licence Holder')->where('licence_transfer_id',$view_transfer->id)->first();
        $representation = TransferDocument::where('doc_type','Representation')->where('licence_transfer_id',$view_transfer->id)->first();
        $index_page = TransferDocument::where('doc_type','Index page')->where('licence_transfer_id',$view_transfer->id)->first();
        $client_quoted = TransferDocument::where('doc_type','Client Quoted')->where('licence_transfer_id',$view_transfer->id)->first();
        $client_invoiced = TransferDocument::where('doc_type','Client Invoiced')->where('licence_transfer_id',$view_transfer->id)->first();
        $payment_to_liquor_board = TransferDocument::where('doc_type','Payment To The Liquor Board')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_logded = TransferDocument::where('doc_type','Transfer Logded')->where('licence_transfer_id',$view_transfer->id)->first();
        $activation_fee =  TransferDocument::where('doc_type','Activation Fee Paid')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_issued = TransferDocument::where('doc_type','Transfer Issued')->where('licence_transfer_id',$view_transfer->id)->first();
        $transfer_delivered = TransferDocument::where('doc_type','Transfer Delivered')->where('licence_transfer_id',$view_transfer->id)->first();
        $original_licence = LicenceDocument::where('document_type','Original-Licence')->where('licence_id',$view_transfer->licence_id)->first();
        $latest_renewal = LicenceDocument::where('document_type','Latest Renewal')->where('licence_id',$view_transfer->id)->first();
        $scanned_application = TransferDocument::where('doc_type','Scanned Application')->where('licence_transfer_id',$view_transfer->id)->first();
        
        return Inertia::render('CompanyAdminDash/Transfers/ViewMyTransfers',
        ['view_transfer' => $view_transfer,
          'old_transfer_forms' =>$old_transfer_forms,
          'current_transfer_forms' => $current_transfer_forms,
          'smoking_affidavict' => $smoking_affidavict,
          'old_poa_res_docs' => $old_poa_res_docs,
          'current_poa_res_docs' => $current_poa_res_docs,
          'old_shareholding' => $old_shareholding,
          'current_shareholding' => $current_shareholding,
          'old_cipc_certificate' => $old_cipc_certificate,
          'current_cipc_certificate' => $current_cipc_certificate,
          'company_docs' => $company_docs,
          'id_docs' => $id_docs,
          'police_clearance' => $police_clearance,
          'tax_clearance' => $tax_clearance,
          'lta_certificate' => $lta_certificate,
          'financial_interest' => $financial_interest,
          'landloard_letter' => $landloard_letter,
          'representation' => $representation,
          'index_page' => $index_page,
          'client_quoted' => $client_quoted,
          'client_invoiced' => $client_invoiced,
          'payment_to_liquor_board' => $payment_to_liquor_board,
          'transfer_logded' => $transfer_logded,
          'activation_fee' => $activation_fee,
          'transfer_issued' => $transfer_issued,
          'transfer_delivered' => $transfer_delivered,
          'original_licence' => $original_licence,
          'latest_renewal' => $latest_renewal,
          'scanned_application' => $scanned_application,
          'get_current_company' => $get_current_company 
        ]);
    }
}
