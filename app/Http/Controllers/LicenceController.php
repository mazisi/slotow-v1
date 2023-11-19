<?php

namespace App\Http\Controllers;

use App\Actions\LicenceFilterAction;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Events\LogUserActivity;
use App\Models\LicenceDocument;

class LicenceController extends Controller
{
    public function index(){       
       $licences = (new LicenceFilterAction)->filterLicence();
       $all_licence_types = LicenceType::get();
        return Inertia::render('Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }

    public function create(){
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        $licence_dropdowns = LicenceType::get();
        return Inertia::render('Licences/CreateLicence',['licence_dropdowns' => $licence_dropdowns,
        'companies' => $companies, 'people' => $people]);
    }


    public function updateActiveStatus(Request $request,$slug){
        $lic =Licence::whereSlug($slug)->first();
        if($request->unChecked){
          $lic->update(['is_licence_active' => 0]);
        }else{
            $lic->update(['is_licence_active' => $request->status]);
        }
        return back()->with('success','Saved.');
    }

    public function store(Request $request){
       if($request->belongs_to === 'Company'){
          $request->validate(["company" => "required|exists:companies,id"]);
       }elseif($request->belongs_to === 'Person'){
           $request->validate([ "person" => "required|exists:people,id"]);
       }
       $request->validate([
        "trading_name" => "required",
        "licence_type" => "required",
        "province" => "required",
        "belongs_to" => "required",
        "licence_number" => "required|unique:licences,licence_number"
    ]);
       
   try {
    $licence = Licence::create([
        'is_licence_active' => $request->is_licence_active,
        "trading_name" => $request->trading_name,
        'belongs_to' => $request->belongs_to,
        "licence_type_id" => $request->licence_type,
        "licence_date" => $request->licence_date,
        'licence_issued_at' => $request->licence_date,
        "company_id"   => $request->company,
        "people_id"   => $request->person,
        "licence_number" => $request->licence_number,
        "old_licence_number" => $request->old_licence_number,
        "address" => $request->address,
        "address2" => $request->address2,
        "address3" => $request->address3,
        "province" => $request->province,
        "board_region" => $request->board_region,
        "postal_code" => $request->postal_code,
        'slug' => sha1(time()),
    ]);
    $activity = 'Licence created By: ' . $licence->trading_name.', '.$licence->licence_number;
    event(new LogUserActivity(auth()->user(), $activity));
    return redirect(route('licences'))->with('success','Licence created successfully.');
    
        } catch (\Throwable $th) {
            return back()->with('error','Error creating Licence');
        }
    }

   
    public function show(Request $request){
        $view = '';
        $licence = Licence::with('company','people','licence_documents')->whereSlug($request->slug)->first();

        $original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->latest()->first();
        $licence_issued = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Licence Issued')->latest()->first();

        $duplicate_original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->latest()->first();

        $original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence-Delivered')->latest()->first();
        $licence_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Licence Delivered')->latest()->first();

        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Original-Licence-Delivered')->latest()->first();
        $companies = Company::pluck('name','id');
        $people = People::pluck('full_name','id');
        $licence_dropdowns = LicenceType::orderBy('licence_type')->get();
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->latest()->paginate(4)->withQueryString();


        $view = $licence->is_new_app ? 'ViewNewApp' : 'ViewLicence';
       
      
        return Inertia::render('Licences/'.$view,[
                                             'licence' => $licence,
                                             'licence_dropdowns' => $licence_dropdowns,
                                             'tasks' => $tasks,
                                             'companies' => $companies,
                                             'people' => $people,
                                             'original_lic' => $original_lic,
                                             'licence_issued' => $licence_issued,
                                             'duplicate_original_lic' => $duplicate_original_lic,
                                             'original_lic_delivered' => $original_lic_delivered,
                                             'licence_delivered' => $licence_delivered,
                                             'duplicate_original_lic_delivered' => $duplicate_original_lic_delivered,
                                            ]);
    }

    public function update(Request $request,$slug){
       try {
        if(empty($request->change_company)){
            $company_var = $request->company_id;
        }else{
            $request->validate(['change_company'=>'required|exists:companies,id']);
            $company_var = $request->change_company;
        }

        
        $update = Licence::whereSlug($slug)->update([
            "trading_name" => $request->trading_name,
            "licence_type_id" => $request->licence_type,
            'licence_issued_at' => $request->licence_date,
            'belongs_to' => $request->belongs_to,
            'company_id' => $request->company_id,
            'people_id' => $request->person_id,
            "licence_number" => $request->licence_number,
            "old_licence_number" => $request->old_licence_number,
            "address" => $request->address,
            "address2" => $request->address2,
            "address3" => $request->address3,
            "province" => $request->province,
            "board_region" => $request->board_region,
            "postal_code" => $request->postal_code,
            "company_id" => $company_var,
            'renewal_amount' => $request->renewal_amount,
            'latest_renewal'  => $request->latest_renewal
        ]);
        
        if($update){
            return back()->with('success','Licence updated successfully.');
        }
        
       } catch (\Throwable $th) {
        return back()->with('error','Error updating licence.');
       }
    }

    public function destroy($slug){
        $licence = Licence::whereSlug($slug)->first();
        $activity = 'Deleted Licence By: ' . $licence->trading_name.', '.$licence->licence_number;
        event(new LogUserActivity(auth()->user(), $activity));
        if($licence->delete()){
           return to_route('licences')->with('success','Licences deleted successfully.');
        }
        return to_route('licences')->with('error','Error deleting licence.');
    }

    
}
