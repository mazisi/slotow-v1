<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Models\RenewalDocument;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ReportFilters\ExistingLicenceReportFilter;
use App\Http\Controllers\Reports\ReportFilters\RenewalReportFilter;


class RenewalExportController extends Controller
{

    
    public static function export($request){
        $arrayData = array(
            array(
                'ACTIVE/DEACTIVE',
                'TRADING NAME',
                'LICENCE HOLDER',
                'LICENCE NUMBER',
                'RENEWAL DATE',
                'RENEWAL AMOUNT',
                'QUOTED',
                'QUOTE SENT',
                'PAYMENT DATE',
                'INVOICE NUMBER',
                'PAYMENT TO LIQUOR BOARD',
                'RENEWAL GRANTED',
                'DELIVERY DATE ',
                'PROOF OF DELIVERY',
                'REASON / NOTES'
            )
        );
        $arr_of_renewals = [];
                    
            $arr_of_renewals = (new RenewalReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_renewals); $i++ ){
                             
                
                    $data = [ 
                            $arr_of_renewals[$i]->is_licence_active ? 'A' : 'D',
                            $arr_of_renewals[$i]->trading_name,
                            getLicenceHolder($arr_of_renewals[$i]), 
                            $arr_of_renewals[$i]->licence_number,
                            $arr_of_renewals[$i]->date,
                            '',
                            (new RenewalExportController)->is_client_quoted($arr_of_renewals[$i]->id) ? 'TRUE' : 'FALSE',
                            $arr_of_renewals[$i]->is_quote_sent ? 'TRUE' : 'FALSE',
                            $arr_of_renewals[$i]->client_paid_at,
                            '',
                            $arr_of_renewals[$i]->payment_to_liquor_board_at,
                            $arr_of_renewals[$i]->renewal_issued_at,
                            $arr_of_renewals[$i]->renewal_delivered_at,
                            $arr_of_renewals[$i]->renewal_delivered_at ? 'TRUE' : 'FALSE',
                            ExportNotes::getNoteExports($arr_of_renewals[$i]->id, 'Licence Renewal')
                            ];

                    $arrayData[] = $data;

                }

           
   
        (new ExportToSpreadsheet)->exportToExcel('A1:L1', 'Renewals_', $arrayData);          
    
           
    }

  
   function is_client_quoted($renewal_id){
    return RenewalDocument::where('licence_renewal_id',$renewal_id)->where('doc_type','Client Quoted')->first(['id']);
   }

}