<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemporalExportReportFilter {


  function filter($request){

    $people_temp_licences = DB::table('temporal_licences')
    ->join('people', 'temporal_licences.people_id', '=', 'people.id')       
                 
         ->when($request,function($query){
             $query->when(request('month_from') && request('month_to'), function($query){
                 $query->whereBetween(DB::raw('MONTH(start_date)'),[request('month_from'), request('month_to')]);
             })
             
             ->when(request('month_from') && !request('month_to'), function ($query)  {
                 $query->whereMonth('start_date', request('month_from'));
             })
                 ->when(request('temp_licence_stages'), function ($query) {
                     $query->whereIn('temporal_licences.status',array_values(explode(",",request('temp_licence_stages'))));
                 })
                 ->when(request('activeStatus') === 'Active', function ($query) {
                     $query->where('temporal_licences.active',1);
                 })
                 ->when(request('activeStatus') === 'Inactive', function ($query) {
                     $query->where('temporal_licences.active',false);
                  })

                  ->when(!empty(request('temp_licence_region')), function ($query) {
                     $query->whereIn('address',array_values(explode(",",request('temp_licence_region'))));
                 })

                 // ->when(!empty(request('selectedDates')), function ($query) {
                 //     $query->whereIn(DB::raw('MONTH(start_date)'),array_values(explode(",",request('selectedDates'))));
                 // })
                 ->when(!empty(request('applicant')), function ($query) {
                     $query->where('belongs_to',request('applicant'));
                 })

                 ->when(request('is_licence_complete') === 'Outstanding', function ($query)  {
                     $query->where('temporal_licences.status','<', 9)
                     ->orWhereNull('temporal_licences.status');
                 })
                 ->when(request('year'), function ($query) {
                              $query->where(DB::raw('YEAR(start_date)'),request('year'));
                         })

                 ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                     $query->where('temporal_licences.status','=',9);
                 });

              })->whereNull('temporal_licences.deleted_at')
              ->get(
                 [
                 'temporal_licences.id',
                 'event_name',
                 'belongs_to',
                 'address',
                 'client_paid_at',
                 'logded_at',
                 'liquor_licence_number',
                 'latest_lodgment_date',
                 'delivered_at',
                 'issued_at',
                 'status',
                 'full_name',
                 'start_date',
                 'end_date',
             ]);

             $company_temp_licences = DB::table('temporal_licences')
             ->join('companies', 'temporal_licences.company_id', '=', 'companies.id')   
                          
                  ->when($request,function($query){
                      $query->when(request('month_from') && request('month_to'), function($query){
                          $query->whereBetween(DB::raw('MONTH(start_date)'),[request('month_from'), request('month_to')]);
                      })
                      
                      ->when(request('month_from') && !request('month_to'), function ($query)  {
                          $query->whereMonth('start_date', request('month_from'));
                      })
                          ->when(request('temp_licence_stages'), function ($query) {
                              $query->whereIn('temporal_licences.status',array_values(explode(",",request('temp_licence_stages'))));
                          })
                          ->when(request('activeStatus') === 'Active', function ($query) {
                              $query->where('temporal_licences.active',true);
                          })
                          ->when(request('activeStatus') === 'Inactive', function ($query) {
                              $query->where('temporal_licences.active',false);
                           })

                           ->when(!empty(request('temp_licence_region')), function ($query) {
                             $query->whereIn('address',array_values(explode(",",request('temp_licence_region'))));
                         })
                          ->when(!empty(request('selectedDates')), function ($query) {
                              $query->whereIn(DB::raw('MONTH(start_date)'),array_values(explode(",",request('selectedDates'))));
                          })
                          ->when(!empty(request('applicant')), function ($query) {
                              $query->where('belongs_to',request('applicant'));
                          })

                          ->when(request('is_licence_complete') === 'Pending', function ($query)  {
                             $query->where('temporal_licences.status','<', 9)
                             ->orWhereNull('temporal_licences.status');
                         })
                         
                         ->when(request('year'), function ($query) {
                              $query->where(DB::raw('YEAR(start_date)'),request('year'));
                         })
     
                         ->when(request('is_licence_complete') === 'Complete', function ($query)  {
                             $query->where('temporal_licences.status','=',9);
                         });
                       })
                       
                       ->orderBy('event_name')
                       ->get(
                          [
                          'temporal_licences.id',
                          'event_name',
                          'belongs_to',
                          'address',
                          'client_paid_at',
                          'liquor_licence_number',
                          'latest_lodgment_date',
                          'logded_at',
                          'delivered_at',
                          'start_date',
                          'end_date',
                          'issued_at',
                          'status',
                          'name',
                      ]);
                
    return $people_temp_licences->merge($company_temp_licences);

  }
}