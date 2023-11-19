<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Licence;
use App\Models\Alteration;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\AlterationDocument;
use App\Http\Requests\AlterationRequest;

class AlterLicenceController extends Controller
{

  public function index(Request $request){
    $licence = Licence::whereSlug($request->slug)->first(['id','trading_name','slug']);
    $alterations = Alteration::where('licence_id',$licence->id)->latest()->paginate(10)->withQueryString();
    return Inertia::render('Alterations/Alteration',[
      'licence'=> $licence,
      'alterations' => $alterations]);
  }

    public function newAlteration(Request $request){
        $licence = Licence::with('alterations')->whereSlug($request->slug)->first(['id','trading_name','slug']);
        return Inertia::render('Alterations/AlterLicence',['licence'=> $licence]);
    }

    public function store(AlterationRequest $request,$licence_id){
          $alter = Alteration::create([
            'licence_id' => $licence_id,
            'logded_at'    => $request->alteration_date,
            'status'  => last($request->status),
            'slug' => sha1(time()),
          ]);
          if($alter){
            return to_route('view_alteration',['slug' => $alter->slug])->with('success','Licence altered successfully.');
          }
          return back()->with('error','An error occured while altering licence.');
    }


    public function show($slug){
      $alteration = Alteration::with('licence')->whereSlug($slug)->first();
      $client_quoted = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Quoted')->first();
      $client_invoiced = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Client Invoiced')->first();
      $alteration_letter = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alteration Letter')->first();
      $site_map_file = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','SiteMap File')->first();
      $tasks = Task::where('model_type','Alteration')->where('model_id',$alteration->id)->latest()->paginate(4)->withQueryString();
      $application_form = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Application Form')->first(['id','path']);
      $dimensional_plans = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Fully Dimensional Plans')->first(['id','path','document_name']);
      $poa_res = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','POA & RES')->first(['id','path','document_name']);
      $smoking_affidavict = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Smoking Affidavit')->first(['id','path','document_name']);
      $payment_to_liquor_board = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment To The Liquor Board')->first(['id','path','document_name']);
      $liqour_board = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Payment to the Liquor Board-2')->first(['id','path','document_name']);
      $alteration_logded = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Lodged')->first(['id','path','document_name']);
      $certification_issued = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Certificate Issued')->first(['id','path','document_name']);
      $alteration_delivered = AlterationDocument::where('alteration_id',$alteration->id)->where('doc_type','Alterations Delivered')->first(['id','path','document_name']);
      return Inertia::render('Alterations/ViewAlteration',[
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
        'alteration_delivered' => $alteration_delivered,
        'tasks' => $tasks
      ]);
    }


    public function update(Request $request){
      try {
       // $request->validate([
      //   'alteration_date' => 'required|date'
      // ]);
      $status = null;
      if($request->status){
        if($request->unChecked){
            $status = intval($request->status[0]) - 1;
        }else{
            $status = $request->status[0];
        }
    }
       Alteration::whereSlug($request->slug)->update([
        'status' => $status <= 0 ? NULL : $status,
       ]);
      
        return back()->with('success','Alteration stage succesfully.');
       
      } catch (\Throwable $th) {
        return back()->with('error','Error updating alteration.');
      }
    }


    public function updateAlterationDate(Request $request, $slug){
      Alteration::whereSlug($slug)->update([
          'invoiced_at' => $request->invoiced_at,
         'client_paid_at' => $request->client_paid_at,
         'liquor_board_at' => $request->liquor_board_at, 
         'logded_at' => $request->date,
         'date' => $request->date,
         'certification_issued_at' => $request->certification_issued_at,
         'delivered_at' => $request->delivered_at    
       ]);
        return back()->with('success','Date updated succesfully.');
       
    }

    public function destroy($slug, $licence_slug)
    {
       $alter = Alteration::whereSlug($slug)->first();
       if($alter->delete()){
        return to_route('alterations', ['slug' => $licence_slug])->with('success','Alteration deleted successfully.');
      }
      return to_route('alterations', ['slug' => $licence_slug])->with('error','An error occured while deleting alteration.');
    }
}
