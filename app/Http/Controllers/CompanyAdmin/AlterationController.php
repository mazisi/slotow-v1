<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;
use App\Http\Controllers\Controller;

class AlterationController extends Controller
{
    public function index(Request $request){
        $licence = Licence::with('alterations')->whereSlug($request->slug)->first(['id','trading_name','slug']);
        return Inertia::render('CompanyAdminDash/Alterations/Alteration',['licence'=> $licence]);
      }

      public function show($slug){
        $alteration = Alteration::with('licence')->whereSlug($slug)->first();
        $client_quoted = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Quoted')->first();
        $client_invoiced = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Invoiced')->first();
        $alteration_letter = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alteration Letter')->first();
        $site_map_file = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','SiteMap File')->first();
        $application_form = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Application Form')->first(['id','path']);
        $dimensional_plans = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Fully Dimensional Plans')->first(['id','path','document_name']);
        $poa_res = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','POA & RES')->first(['id','path','document_name']);
        $smoking_affidavict = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Smoking Affidavit')->first(['id','path','document_name']);
        $payment_to_liquor_board = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment To The Liquor Board')->first(['id','path','document_name']);
        $liqour_board = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment to the Liquor Board-2')->first(['id','path','document_name']);
        $alteration_logded = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Lodged')->first(['id','path','document_name']);
        $certification_issued = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Certificate Issued')->first(['id','path','document_name']);
        $alteration_delivered = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Delivered')->first(['id','path','document_name']);
       
        return Inertia::render('CompanyAdminDash/Alterations/ViewAlteration',[
          'alteration' => $alteration,
          'client_quoted' => $client_quoted,
          'client_invoiced' => $client_invoiced,
          'alteration_letter' => $alteration_letter,
          'site_map_file' => $site_map_file,
          'application_form' => $application_form,
          'dimensional_plans' => $dimensional_plans,
          'poa_res' => $poa_res,
          'smoking_affidavict'  => $smoking_affidavict,
          'payment_to_liquor_board' => $payment_to_liquor_board,
          'liqour_board'  => $liqour_board,
          'alteration_logded' => $alteration_logded,
          'certification_issued'  => $certification_issued,
          'alteration_delivered' => $alteration_delivered
        ]);
      }
}
