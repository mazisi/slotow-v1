<?php

namespace App\Http\Controllers\CompanyAdmin;

use Inertia\Inertia;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\TemporalLicence;
use App\Http\Controllers\Controller;

class TemporalLicenceController extends Controller
{
    public function index(){
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 

        $licences = TemporalLicence::with('company','people')

        ->when(request('term'), function ($query) {
            return $query->where('liquor_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('start_date','LIKE','%'.request('term').'%')
                ->orWhere('end_date','LIKE','%'.request('term').'%')
                ->orWhere('event_name', 'like', '%'.request('term').'%');

        })->when(request('term') && request('active_status') == 'Active', 
            function ($query){ 
                return $query->where('liquor_licence_number','LIKE','%'.request('term').'%')
                ->orWhere('start_date','LIKE','%'.request('term').'%')
                ->orWhere('end_date','LIKE','%'.request('term').'%')
                ->orWhere('event_name', 'like', '%'.request('term').'%')
                ->whereActive(1);
            
            })
            ->when(request('term') && request('active_status') == 'Inactive', 
                function ($query){ 
                    return $query->where('liquor_licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('start_date','LIKE','%'.request('term').'%')
                    ->orWhere('end_date','LIKE','%'.request('term').'%')
                    ->orWhere('event_name', 'like', '%'.request('term').'%')
                    ->whereNull('active');            
                    })

            ->where('people_id',auth()->user()->people_id)
            ->orWhereIn('company_id',$get_company_ids)
            ->get();

        return Inertia::render('CompanyAdminDash/Licences/TemporalLicence',['licences' => $licences]);
    }

    public function create() {
        $companies = Company::pluck('name','id');
        $people = Consultant::pluck('first_name','id');
        return Inertia::render('TemporalLicences/CreateTemporalLicence',['companies' => $companies,'people' => $people]);
    }

   
        public function show($slug){
            $licence = TemporalLicence::with('company','people')->whereSlug($slug)->first();
    
            return Inertia::render('CompanyAdminDash/ViewTemporalLicence',['licence' => $licence]);
     }

  
}
