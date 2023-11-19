<?php

namespace App\Http\Controllers\CompanyAdmin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id',auth()->id());
        })->get();

        $get_company_ids = [];

        foreach($companies as $company){
            $get_company_ids[] = $company->id;
        } 
        
        $user = User::with('company')->whereId(auth()->id())->first();

        $licences = Licence::whereIn('company_id',$get_company_ids)
                            ->orWhere('people_id',auth()->id())->count();

        $temp_licences = TemporalLicence::whereIn('company_id',$get_company_ids)
                                        ->orWhere('people_id',auth()->id())->count();
        
        return Inertia::render('CompanyAdminDash/Dashboard',
        [
            'licences' => $licences,
            'user' => $user,
            'temp_licences' => $temp_licences
        ]);
    }
}
