<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\LicenceDocument;
use App\Models\LicenceTransfer;
use App\Models\TransferDocument;
use App\Models\LiquorBoardRequest;
use App\Models\People;

class TransferLicenceController extends Controller
{
    public function index(Request $request){
        $licence = Licence::with('company','people')->whereSlug($request->slug)->first();
        $companies_dropdown = Company::where('id','!=',$licence->company_id)->pluck('name','id');//get companies list
        $people_dropdown = People::where('id','!=',$licence->people_id)->pluck('full_name','id');
        return Inertia::render('Licences/TransferLicence',
                                                ['licence' => $licence,
                                               'companies_dropdown' => $companies_dropdown,
                                               'people_dropdown'  => $people_dropdown
                                                ]);
    }

    public function store(Request $request,$slug){
      if($request->belongs_to === 'Person'){
          $request->validate([
            "new_person" => "required|exists:people,id",
            "belongs_to" => "required|in:Person,Company",
            "transfered_from" => "required|in:Person,Company"
        ]);
      }else{
            $request->validate([
              "new_company" => "required|exists:companies,id",
              "belongs_to" => "required|in:Person,Company",
              "transfered_from" => "required|in:Person,Company"
          ]);
      }
       
  
      $sorted_statuses = Arr::sort($request->status);
      $transfer = LicenceTransfer::create([
        'transfered_to' => $request->belongs_to,
        'transfered_from' => $request->transfered_from,
        'licence_id'=> $request->licence_id,
        'old_people_id'=> $request->transfered_from ==='Person' ? $request->old_company_id : NULL,
         'old_company_id' => $request->transfered_from === 'Company' ? $request->old_company_id : NULL,
        'company_id'=> $request->new_company,
        'people_id'=> $request->new_person,
        'status' => last($sorted_statuses),
        'slug' => sha1(time())
       ]);

       if($transfer){
                if($transfer->transfered_to === 'Company'){
                  Licence::whereId($transfer->licence_id)->update([
                    'company_id' => $request->new_company,
                    'people_id' => NULL,
                    'belongs_to' => 'Company'
                  ]);
                }else{
                  Licence::whereId($transfer->licence_id)->update([
                    'people_id' => $transfer->people_id,
                    'company_id' => NULL,
                    'belongs_to' => 'Person'
                  ]);
                }
         
         return to_route('view_transfered_licence',['slug' => $transfer->slug])->with('success','Licence transfered successfully.');
       }
       return back()->with('errror','Oopps!!! An error occured while attempting licence transfer.');

      }
/**
       * View all licence transfer history for a certain licence
       */
      public function transferHistory(Request $request){
      $licence = Licence::whereSlug($request->slug)->first(['trading_name','slug']);
      
      $transfers = LicenceTransfer::with('old_person','new_person','old_company','new_company')
          ->whereHas('licence', function ($query) use($request) {
            $query->where('slug',$request->slug);
          })->paginate(15)->withQueryString();
        return Inertia::render('Licences/TransferHistory',['licence' => $licence, 'transfers' => $transfers]);
      }

      /**
       * View licence transfer individually.
       */
      public function viewTransferedLicence($slug){
        $view_transfer = LicenceTransfer::with('old_person','new_person','old_company','new_company','licence')->whereSlug($slug)->first();
        
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Licence Transfer')->where('model_id',$view_transfer->id)->get();
      
        // $companies_dropdown = Company::pluck('name','id');
        $tasks = Task::where('model_type','Transfer')->where('model_id',$view_transfer->id)->latest()->paginate(4)->withQueryString();

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
        $latest_renewal = TransferDocument::where('doc_type','Latest Renewal')->where('licence_transfer_id',$view_transfer->id)->first();
        $scanned_application = TransferDocument::where('doc_type','Scanned Application')->where('licence_transfer_id',$view_transfer->id)->first();
        
        return Inertia::render('Licences/ViewTransferedLicence',
        ['view_transfer' => $view_transfer,
                 'tasks' => $tasks,
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
          'liqour_board_requests' => $liqour_board_requests
        ]);
      }

      public function update(Request $request) {
      
        if($request->status){
          if($request->unChecked){
              $status = intval($request->status[0]) - 1;
          }else{
              $status = $request->status[0];
          }
      }
        $update = LicenceTransfer::whereSlug($request->slug)->update([
          'status' => $status <= 0 ? NULL : $status,
          'date' => $request->transfer_date
        ]);
        if ($update) {
          return back()->with('success','Licence transfer updated successfully.');
        }
        return back()->with('error','Error updating transfered licence.');
      }

      public function updateDates(Request $request, $slug){
        LicenceTransfer::whereSlug($slug)->update([
          'lodged_at' => $request->lodged_at,
          'activation_fee_paid_at' => $request->activation_fee_paid_at,
          'issued_at' => $request->issued_at,
          'delivered_at' => $request->delivered_at,
          'payment_to_liquor_board_at' => $request->payment_to_liquor_board_at,
            
        ]);
         return back()->with('success','Date updated successfully.');
      }

      /**
       * Remove licence transfer
       */
      public function destroy($slug,$licence_slug){
      try {
        $licence = LicenceTransfer::whereSlug($slug)->first();
        if ($licence->delete()) {
          return to_route('transfer_history',['slug' => $licence_slug])->with('success','Licence transfer deleted successfully.');
        }
        
      } catch (\Throwable $th) {
        //throw $th;
        return back()->with('error','Error deleting  licence transfer.');
      }
      }
}
