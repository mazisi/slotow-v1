<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\Task;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceType;
use Illuminate\Http\Request;
use App\Models\LicenceRenewal;
use App\Models\LicenceDocument;
use App\Models\RenewalDocument;
use App\Http\Controllers\Controller;

class LicenceController extends Controller
{
    public function index(){
        
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 


        $licences = Licence::with(["company","people","licence_type"])

        ->when(request('term'), function ($query) {
            return $query->where('licence_number','LIKE','%'.request('term').'%')
                          ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                          ->orWhere('trading_name','LIKE','%'.request('term').'%');

        })->when(request('term') && request('active_status') == 'Active', 
            function ($query){ 
                $query->whereNotNull('is_licence_active')
                ->orWhere('licence_number','LIKE','%'.request('term').'%')
                ->orWhere('old_licence_number','LIKE','%'.request('term').'%');
            
            })
            ->when(request('term') && request('licence_date') && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%');            
                })

            ->when(request('licence_type'), 
                function ($query){ 
                    $query->where('licence_type_id',request('licence_type'));                
                })

            ->when(request('licence_date'), 
                function ($query){
                    return $query->whereMonth('licence_date',request('licence_date'));                
                })

            ->when(request('licence_date') && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'));                
                })

            ->when(request('licence_date') && request('active_status') =='Inactive' && request('licence_type'), 
                function ($query){ 
                    $query->whereMonth('licence_date',request('licence_date'))
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->whereNull('is_licence_active');                
                })

            ->when(request('term') && request('active_status') =='Active' && request('licence_type'), 
                function ($query){ 
                    $query->where('licence_type_id',request('licence_type'))
                        ->where('trading_name','LIKE','%'.request('term').'%')
                        ->orWhere('licence_number','LIKE','%'.request('term').'%')
                        ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('licence_type_id',request('licence_type'))
                    ->whereNotNull('is_licence_active');                
                })

            ->when(request('term') && request('active_status') =='Inactive', 
                function ($query){ 
                   $query->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->whereNull('is_licence_active')
                    ->orWhereHas('company', function($query){
                      $query->where('name', 'like', '%'.request('term').'%');                
                });
               })

            ->when(request('active_status') =='Active', 
                function ($query){ 
                    $query->whereNotNull('is_licence_active');                
                })

                ->when(request('province'), 
                function ($query){ 
                    $query->where('province', 'LIKE','%'.request('province').'%');                
                })

            ->when(request('province') && request('term'), 
                function ($query){ 
                    $query->where('province', 'LIKE','%'.request('province').'%')
                    ->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhereHas('company', function($query){
                      $query->where('name', 'like', '%'.request('term').'%');                
                });
            }) 

            ->when(request('active_status') =='Inactive', 
                function ($query){ 
                    $query->whereNull('is_licence_active');                
                })

            ->whereIn('company_id',$get_company_ids)
            ->get();
        $all_licence_types = LicenceType::get();

        return Inertia::render('CompanyAdminDash/Licences/Licence',['licences' => $licences,'all_licence_types' => $all_licence_types]);
    }

    
    public function show($slug){
        $licence = Licence::with('company','licence_documents')->whereSlug($slug)->first();
        $original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence')->get();
        $duplicate_original_lic = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Licence')->get();
        $original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Original-Licence-Delivered')->get();
        $duplicate_original_lic_delivered = LicenceDocument::where('licence_id',$licence->id)->where('document_type','Duplicate-Original-Licence-Delivered')->get();
        $companies = Company::pluck('name','id');
        $licence_dropdowns = LicenceType::get();
        $tasks = Task::where('model_type','Licence')->where('model_id',$licence->id)->get();

        return Inertia::render('CompanyAdminDash/Licences/ViewMyLicences',[
                                            'licence' => $licence,
                                            'licence_dropdowns' => $licence_dropdowns,
                                             'tasks' => $tasks,
                                             'companies' => $companies,
                                             'original_lic' => $original_lic,
                                             'duplicate_original_lic' => $duplicate_original_lic,
                                             'original_lic_delivered' => $original_lic_delivered,
                                             'duplicate_original_lic_delivered' => $duplicate_original_lic_delivered,
                                            ]);
    }


public function my_renewals(Request $request){
    $licence = Licence::with('licence_renewals')->whereSlug($request->slug)->first();
    return Inertia::render('CompanyAdminDash/Licences/MyRenewals',['licence' => $licence]);
}

public function view_my_renewal($slug){
    $renewal = LicenceRenewal::with('licence')->whereSlug($slug)->first();

    $client_invoiced = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Invoiced')->first();
    $renewal_issued = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Renewal Issued')->first();
    $client_quoted = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Client Quoted')->first();
    $renewal_doc = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Renewal Delivered')->first();
    $liqour_board = RenewalDocument::where('licence_renewal_id',$renewal->id)->where('doc_type','Payment To The Liquor Board')->first();
    return Inertia::render('CompanyAdminDash/Licences/ViewMyRenewal',
    [

    'renewal' => $renewal,
    'client_invoiced' => $client_invoiced,
    'renewal_issued' => $renewal_issued,
    'client_quoted'  => $client_quoted,
    'renewal_doc' => $renewal_doc,
    'liqour_board' => $liqour_board
   ]);
}
    

public function registration()
{
    # code...
}
}
