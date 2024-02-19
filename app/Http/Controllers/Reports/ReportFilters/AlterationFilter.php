<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class AlterationFilter{


 function filter($request){

  return DB::table('alterations')
        ->selectRaw("alterations.id, alterations.certification_issued_at,licences.belongs_to, licences.trading_name, licences.licence_number, licences.province, 
        licences.licence_issued_at,licences.company_id,licences.people_id, alterations.logded_at,licences.board_region,licence_type_id,alterations.date, 
        alterations.status, licence_date, is_licence_active")
        ->join('licences', 'licences.id' , '=', 'alterations.licence_id')

      ->when($request,function($query){
          $query->when(request('month_from') && request('month_to'), function($query){
              $query->whereBetween(DB::raw('MONTH(alterations.logded_at)'),[request('month_from'), request('month_to')]);
           })

          ->when(request('month_from') && !request('month_to'), function ($query)  {
              $query->whereMonth('alterations.logded_at', request('month_from'));
          })
          ->when(request('activeStatus') == 'Active', function ($query) {
              $query->where('is_licence_active',1);
          })
          ->when(request('activeStatus') == 'Inactive', function ($query) {
              $query->where('is_licence_active',false);
          })
          ->when(request('province'), function ($query) {
              $query->whereIn('province',array_values(explode(",",request('province'))));
          })
          
          ->when(request('boardRegion'), function ($query) {
              $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
          })
          
           ->when(request('alteration_stages'), function ($query) {
                  $query->whereIn('alterations.status',array_values(explode(",",request('alteration_stages'))));
              })
          ->when(request('applicant'), function ($query) {
              $query->where('belongs_to',request('applicant'));
          })
          
          ->when(request('year') && request('year') !== 'null', function ($query) {
               $query->whereYear('logded_at', request('year'));
           })
          ->when(request('licence_types'), function ($query) {
              $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
           })

          
          ->when(request('is_licence_complete') === 'Pending' , function ($query){
                  $query->where(function ($query) {
                      $query->where('alterations.status','<', 7);
                  });
              
              })

          ->when(request('is_licence_complete') === 'Complete', function ($query)  {
              $query->where('alterations.status', '>=', 7);
          });

      })
      ->whereNull('alterations.deleted_at')
      ->orderBy('trading_name')
       ->get([
          'certification_issued_at',
          'id','trading_name',
          'licence_number',
          'board_region',
          'province',
          'status',
          'board_region',
          'doc_type',
          'people_id',
          'company_id',
          'date',
          'licence_issued_at',
          'licence_type_id',
          'belongs_to',
          'is_licence_active',
          'logded_at'
  ]);
 }
}