<?php

namespace App\Http\Controllers\Reports\ReportFilters;

use Illuminate\Support\Facades\DB;

class RenewalReportFilter {

  function filter($request){
   return DB::table('licence_renewals')
   ->selectRaw("licence_renewals.id, is_licence_active, trading_name, board_region,licence_number, licences.licence_date, 
                licence_renewals.client_paid_at,licence_renewals.status, payment_to_liquor_board_at, renewal_issued_at, renewal_delivered_at,
                is_quote_sent, licence_renewals.date")

   ->join('licences', 'licences.id' , '=', 'licence_renewals.licence_id')

       ->when($request,function($query){
           $query->when(request('month_from') && request('month_to'), function($query){
               $query->whereBetween(DB::raw('MONTH(licence_date)'),[request('month_from'), request('month_to')]);
           })
 
           ->when(request('month_from') && !request('month_to'), function ($query)  {
               $query->whereMonth('licence_date', request('month_from'));
           })

           ->when(request('province'), function ($query)  {
               $query->whereIn('province',array_values(explode(",",request('province'))));
           })

           ->when(request('activeStatus') === 'Inactive', function ($query) {
               $query->where('is_licence_active',false);
           })

           ->when(request('activeStatus') == 'Active', function ($query) {
               $query->where('is_licence_active', 1);
           })

           ->when(request('boardRegion'), function ($query)  {
               // $query->whereIn(DB::raw('licences.board_region'),array_values(explode(",",request('boardRegion'))));
               $query->whereIn('board_region',array_values(explode(",",request('boardRegion'))));
           })
           ->when(request('applicant'), function ($query)  {
               $query->where('belongs_to',request('applicant'));
           })
           ->when(request('year'), function ($query) {
              $query->where('date',request('year'));
           })

           ->when(request('renewal_stages'), function ($query) {
               $query->whereIn('licence_renewals.status',array_values(explode(",",request('renewal_stages'))));
           })
           ->when(request('licence_types'), function ($query)  {
               $query->whereIn('licence_type_id',array_values(explode(",",request('licence_types'))));
           })
           ->when(request('is_licence_complete') === 'Pending', function ($query)  {
               $query->where('licence_renewals.status','<', 5)
               ->orWhereNull('licence_renewals.status');
           })

           ->when(request('is_licence_complete') === 'Complete', function ($query)  {
               $query->where('licence_renewals.status','>=', 5);
           });

           })->whereNull('licences.deleted_at')->whereNull('licence_renewals.deleted_at')
           ->orderBy('trading_name')
           ->get([
               'id',
               'board_region',
               'is_licence_active',
               'trading_name',
               'licence_date',
               'licence_number',
               'licence_renewals.date',
               'licence_renewals.status',
               'is_quote_sent',
               'client_paid_at',
               'payment_to_liquor_board_at',
               'renewal_issued_at',
               'renewal_delivered_at',
           ]);
  }
}