<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\People;
use App\Models\Company;
use App\Models\Licence;
use Illuminate\Http\Request;
use App\Models\TemporalLicence;
use Illuminate\Support\Facades\DB;

class SlotowDashboardController extends Controller
{
    public function index(){
        $licences = Licence::count();
        $companies = Company::count();
        $people = People::count();
        $temp_licences = TemporalLicence::count();

        $jan = Licence::whereMonth('created_at','01')->count();
        $feb = Licence::whereMonth('created_at','02')->count();
        $mar = Licence::whereMonth('created_at','03')->count();
        $apr = Licence::whereMonth('created_at','04')->count();
        $may = Licence::whereMonth('created_at','05')->count();
        $jun = Licence::whereMonth('created_at','06')->count();
        $jul = Licence::whereMonth('created_at','07')->count();
        $aug = Licence::whereMonth('created_at','08')->count();
        $sep = Licence::whereMonth('created_at','09')->count();
        $oct = Licence::whereMonth('created_at','10')->count();
        $nov = Licence::whereMonth('created_at','11')->count();
        $dec = Licence::whereMonth('created_at','12')->count();

        // $count_group_licences = DB::table('licences')
        //                         ->select(DB::raw('MONTH(licence_date) as month, count(*) as number'))
        //                         ->whereNotNull('licence_date')
        //                         ->groupByRaw('MONTH(licence_date)')
        //                         ->orderByRaw('MONTH(licence_date) asc')
        //                         ->get();

                              


        return Inertia::render('Dashboard',[
        'licences' => $licences,
        'companies' => $companies,
        'people' => $people,
        'temp_licences' => $temp_licences,
        'jan' => $jan,
        'feb' => $feb,
        'mar' => $mar,
        'apr' => $apr,
        'may'=> $may,
        'jun'=> $jun,
        'jul'=> $jul,
        'aug'=> $aug,
        'sep'=> $sep,
        'oct'=> $oct,
        'nov'=> $nov,
        'dec' => $dec,
        ]);
    }
}
