<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use App\Http\Controllers\Controller;
use App\Models\User;

class CompanyController extends Controller
{
    
    public function index(){   
        $user = User::with('company')->whereId(auth()->id())->first();
        return Inertia::render('CompanyAdminDash/MyCompanies',['user'=> $user]);
    }

    public function show($slug){
        $company = Company::with('users','licences','people')->whereSlug($slug)->first();
        $contrib_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','Contribution-Certificate')->first();
        $bee_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','BEE-Certificate')->first();
        $cipc_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','CIPC-Certificate')->first();
        $lta_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','LTA-Certificate')->first();
        $company_doc = CompanyDocument::where('company_id',$company->id)->where('document_type','Company-Document')->first();
        $sars_cert = CompanyDocument::where('company_id',$company->id)->where('document_type','SARS-Certificate')->first();
        $tasks = Task::where('model_type','Company')->where('model_id',$company->id)->first();
        $people = People::pluck('full_name','id');
        
        return Inertia::render('CompanyAdminDash/ViewCompany',[
            'company'=> $company,
            'people' => $people,
             'tasks' => $tasks,
             'contrib_cert' => $contrib_cert,
             'bee_cert' => $bee_cert,
             'cipc_cert' => $cipc_cert,
             'lta_cert' => $lta_cert,
             'sars_cert'  => $sars_cert,
             'company_doc' => $company_doc
            ]);
    }

   

}
