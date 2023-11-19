<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alteration;
use App\Models\Licence;

class AlterationEmailCommsController extends Controller
{

    public function getAlterationTemplate(Request $request){

        $alterations = Alteration::with("licence")->when($request, function($query) use($request){
            $query->when(request('month'), function($query) {                    
                $query->whereHas('licence', function($query) {
                    $query->whereMonth('licence_date', request('month'))
                    ->whereNull('deleted_at');
                });
            })
            ->when(request('province'), function ($query) use ($request) {
                $query->whereHas('licence', function($query) use($request){
                    $query->where('province',$request->province);
                });
               
            })
          ->when(!empty(request('stage')), function ($query) use ($request) {
            $query->where('status',$request->stage);
        });
        })
        ->where(function($query){
            $query->where('status','1')
                    ->orWhere('status','2')
                    ->orWhere('status','4')
                    ->orWhere('status','5')
                    ->orWhere('status','6')
                    ->orWhere('status','7');   
                     })
    ->whereNull('deleted_at')
        ->orderBy('status','asc')
           ->paginate(20)->withQueryString();


    return Inertia::render('EmailComms/Alteration',['alterations' => $alterations]);
}


//Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered



}
