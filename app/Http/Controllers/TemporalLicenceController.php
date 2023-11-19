<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\PeopleDocument;
use App\Events\LogUserActivity;
use App\Models\TemporalLicence;
use App\Models\LiquorBoardRequest;
use App\Models\TemporalLicenceDocument;

class TemporalLicenceController extends Controller
{
    public function index(){
        
        $licences = TemporalLicence::with('company','people')
        ->when(request('term'), 
            function ($query){ 
                return $query->whereHas('company', function($query){
                    $query->where('name', 'like', '%'.request('term').'%');
                })->orWhereHas('people', function($query){
                    $query->where('full_name', 'like', '%'.request('term').'%');                                      
                          })
                ->orWhere('liquor_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('start_date','LIKE','%'.request('term').'%')
                ->orWhere('end_date','LIKE','%'.request('term').'%')
                ->orWhere('event_name','LIKE','%'.request('term').'%');
            
            })
        
                ->latest()->paginate(20)->withQueryString();

        return Inertia::render('TemporalLicences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

    public function store(Request $request){
       $request->validate([
           'event_name' => 'required',
           'start_date' => 'required|date',
           'end_date' => 'required|date',
           'latest_lodgment_date'=> 'required',
           'belongs_to' => 'required|in:Person,Company'
           ]);
           if(is_null($request->person)){
            $request->validate(['company' => 'required|exists:companies,id']);
            $temp = TemporalLicence::create([
                'company_id' => $request->company,
                'event_name' => $request->event_name,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'application_type' => $request->application_type,
                'address' => $request->address,
                'belongs_to' => $request->belongs_to,
                'latest_lodgment_date'  => $request->latest_lodgment_date,
                'slug' => sha1(time()),
                ]);

           }else{
            $request->validate(['person' => 'required|exists:people,id']);
            $temp = TemporalLicence::create([
                'people_id' => $request->person,
                'event_name' => $request->event_name,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                'application_type' => $request->application_type,
                'belongs_to' => $request->belongs_to,
                'address' => $request->address,
                'latest_lodgment_date'  => Carbon::parse($request->latest_lodgment_date)->format('m-d-Y'),
                'slug' => sha1(time()),
                ]);

           }
           
           if($temp){
              return to_route('view_temp_licence',['slug' => $temp->slug])->with('success','Temporary Licence issued successfully.');
           }
           return back()->with('error','AN unknown error occured while creating Temporary Licence.');
    }

    

    public function show($slug){
        $licence = TemporalLicence::with('company','people')->whereSlug($slug)->first();

        return Inertia::render('TemporalLicences/ViewTemporalLicence',['licence' => $licence]);
      }

    public function update(Request $request){
        $temp = TemporalLicence::whereSlug($request->slug)->first();        
            $temp->update([
                'liquor_licence_number' => $request->liquor_licence_number,
                'end_date' => $request->end_date,
                'start_date' => $request->start_date,
                "application_type" => $request->application_type,
                "address" => $request->address,
                "event_name" => $request->event_name
                ]);
    
              
            if($temp){
               return back()->with('success','Temporary Licence updated successfully.');
            }
            return back()->with('error','An unknown error occured while updating Temporary Licence.');
     }

    public function processApplication(Request $request){
        $licence = TemporalLicence::with('company','people')->whereSlug($request->slug)->first();
        $liqour_board_requests = LiquorBoardRequest::where('model_type','Temporal Licence')->where('model_id',$licence->id)->get();

        $client_invoiced = TemporalLicenceDocument::where('doc_type','Client Invoiced')->where('temporal_licence_id',$licence->id)->first();
        $client_quoted = TemporalLicenceDocument::where('doc_type','Client Quoted')->where('temporal_licence_id',$licence->id)->first();
        $collate = TemporalLicenceDocument::where('doc_type','Collate Documents')->where('temporal_licence_id',$licence->id)->first();
        $liqour_board = TemporalLicenceDocument::where('doc_type','Payment To The Liquor Board')->where('temporal_licence_id',$licence->id)->first();
        $licence_logded = TemporalLicenceDocument::where('doc_type','Licence Lodged')->where('temporal_licence_id',$licence->id)->first();
        $licence_issued = TemporalLicenceDocument::where('doc_type','Licence Issued')->where('temporal_licence_id',$licence->id)->first();
        $licence_delivered = TemporalLicenceDocument::where('doc_type','Licence Delivered')->where('temporal_licence_id',$licence->id)->first();
        $tasks = Task::where('model_type','Temporal Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString();
        $scanned_app = TemporalLicenceDocument::where('doc_type','Scanned Application')->where('temporal_licence_id',$licence->id)->first();
    

        if(is_null($licence->people_id)){
            //company documents
        $company_application_form = TemporalLicenceDocument::where('doc_type','Application Form')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();  
        $company_poa = TemporalLicenceDocument::where('doc_type','POA And RES')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_annexure_b = TemporalLicenceDocument::where('doc_type','Annexure B')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_annexure_c = TemporalLicenceDocument::where('doc_type','Annexure C')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_cipc = TemporalLicenceDocument::where('doc_type','CIPC Certificate')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_id_document = TemporalLicenceDocument::where('doc_type','ID Document')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_representations = TemporalLicenceDocument::where('doc_type','Representations')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_landlord_letter = TemporalLicenceDocument::where('doc_type','Landlord Letter')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_security_letter = TemporalLicenceDocument::where('doc_type','Security Letter')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_advert =  TemporalLicenceDocument::where('doc_type','Advert/Blurb')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();
        $company_plan = TemporalLicenceDocument::where('doc_type','Plan/Maps')->where('belongs_to','Company')->where('temporal_licence_id',$licence->id)->first();


        return Inertia::render('TemporalLicences/ProcessApplication',
            ['licence' => $licence,
            // 'people' => $people,
            // 'companies' => $companies,
            'client_invoiced' => $client_invoiced,
            'client_quoted' => $client_quoted,
            'collate' => $collate,
            'liqour_board' => $liqour_board,
            'licence_logded' => $licence_logded,
            'licence_issued' => $licence_issued,
            'licence_delivered' => $licence_delivered,
            'tasks' => $tasks,
            'scanned_app' => $scanned_app,
            'company_application_form' => $company_application_form,
            'company_poa' => $company_poa,
            'company_annexure_b' => $company_annexure_b,
            'company_annexure_c' => $company_annexure_c,
            'company_cipc' => $company_cipc,
            'company_id_document' => $company_id_document,
            'company_representations' => $company_representations,
            'company_landlord_letter' => $company_landlord_letter,
            'company_security_letter' => $company_security_letter,
            'company_advert' => $company_advert,
            'company_plan' => $company_plan,
            'liqour_board_requests' => $liqour_board_requests
            ]);

        }else{

            $get_person_id_document = PeopleDocument::where('people_id',$licence->people_id)->where('doc_type','ID Document')->first();
                //individual documents
            $individual_application_form = TemporalLicenceDocument::where('doc_type','Application Form')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();  
            $power_of_attorney = TemporalLicenceDocument::where('doc_type','Power Of Attorney')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_annexure_b = TemporalLicenceDocument::where('doc_type','Annexure B')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_annexure_c = TemporalLicenceDocument::where('doc_type','Annexure C')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_representations = TemporalLicenceDocument::where('doc_type','Representations')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_landlord_letter = TemporalLicenceDocument::where('doc_type','Landlord Letter')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_security_letter = TemporalLicenceDocument::where('doc_type','Security Letter')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_advert =  TemporalLicenceDocument::where('doc_type','Advert/Blurb')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();
            $individual_plan = TemporalLicenceDocument::where('doc_type','Plan/Maps')->where('belongs_to','Individual')->where('temporal_licence_id',$licence->id)->first();

            return Inertia::render('TemporalLicences/ProcessApplication',
            [
            
            'licence' => $licence,
            'client_invoiced' => $client_invoiced,
            'client_quoted' => $client_quoted,
            'collate' => $collate,
            'liqour_board' => $liqour_board,
            'licence_logded' => $licence_logded,
            'licence_issued' => $licence_issued,
            'licence_delivered' => $licence_delivered,
            'tasks' => $tasks,
            'scanned_app' => $scanned_app,
            'individual_application_form' => $individual_application_form,
            'power_of_attorney' => $power_of_attorney,
            'individual_annexure_b' => $individual_annexure_b,
            'individual_annexure_c' => $individual_annexure_c,
            'individual_representations' => $individual_representations,
            'individual_landlord_letter' => $individual_landlord_letter,
            'individual_security_letter' => $individual_security_letter,
            'individual_advert' => $individual_advert,
            'individual_plan' => $individual_plan,
            'get_person_id_document' => $get_person_id_document,
            'liqour_board_requests' => $liqour_board_requests
            ]);
        }
     }









    public function update_prepared_temp_app(Request $request,$slug){
        try {
            $status = '';

            if($request->status){
                if($request->unChecked){
                    $status = intval($request->status[0]) - 1;
                }else{
                    $status = $request->status[0];
                }
            }
    
            TemporalLicence::whereSlug($slug)->update(["status" => $status <= 0 ? NULL : $status]);   
               
            return back()->with('success','Temporary Licence updated successfully.');
         
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error','An unknown error occured while updating Temporary Licence.');
        }
        
            
     }

     public function updateDates(Request $request, $slug){
        try {
            TemporalLicence::whereSlug($slug)->update([
                'client_paid_at' => $request->client_paid_at,
                'payment_to_liquor_board_at' => $request->payment_to_liquor_board_at,
                'logded_at' => $request->logded_at,
                'issued_at' => $request->issued_at,
                'delivered_at' => $request->delivered_at,
                ]);
                return back()->with('success','Date updated successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','An unknown error occured while updating date.');
        }
    }

     public function destroy($slug){
        try {
            $licence = TemporalLicence::whereSlug($slug)->first();
            $activity = 'Deleted Temporal Licence: ' . $licence->event_name;
            event(new LogUserActivity(auth()->user(), $activity));
            if($licence->delete()){
               return to_route('temp_licences')->with('success','Temporary Licence deleted successfully.');
            }
            
        } catch (\Throwable $th) {
            return to_route('temp_licences')->with('error','AN unknown error occured while deleteing Temporary Licence.');
        }

     }
}
