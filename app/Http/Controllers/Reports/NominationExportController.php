<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\NominationReportFilter;
use App\Models\NominationDocument;

class NominationExportController extends Controller implements ReportShouldHaveStatusInterface
{
    public static function export($request){
          
            $arrayData = array(
                array(
                    'TRADING NAME',
                    'LICENCE HOLDER',
                    'YEAR',
                    'LICENCE NUMBER',
                    'PROVINCE/REGION',
                    'INVOICE NUMBER',
                    'PAYMENT DATE',
                    'DATE LODGED',
                    'PROOF OF LODGEMENT',
                    'DATE GRANTED',
                    'CURRENT STATUS',
                    'COMMENTS'
                )
            );
            $arr_of_nominations = [];                  
                
        $arr_of_nominations = (new NominationReportFilter)->filter($request)->toArray(); 
        
        for($i = 0; $i < count($arr_of_nominations); $i++ ){           // $is_client_paid = NominationDocument::where('nomination_id',$arr_of_nominations[$i]->id)->where('doc_type','Payment To The Liquor Board')->first();
            $notes = ExportNotes::getNoteExports($arr_of_nominations[$i]->id, 'Nomination');  

       $data = [ 
                   $arr_of_nominations[$i]->trading_name, 
                   getLicenceHolder($arr_of_nominations[$i]),
                   $arr_of_nominations[$i]->year, 
                   $arr_of_nominations[$i]->licence_number, 
                   request('boardRegion') ? $arr_of_nominations[$i]->province.' - '.$arr_of_nominations[$i]->board_region : $arr_of_nominations[$i]->province,
                   '',
                   $arr_of_nominations[$i]->payment_to_liquor_board_at,
                   $arr_of_nominations[$i]->nomination_lodged_at,
                   (new NominationExportController)->getProofOfLodgiment($arr_of_nominations[$i]->id) ? 'TRUE' : 'FALSE',
                   $arr_of_nominations[$i]->nomination_issued_at,
                   (new NominationExportController)->getStatus($arr_of_nominations[$i]->status),
                   $notes
                ];

        $arrayData[] = $data;

            }
            (new ExportToSpreadsheet)->exportToExcel('A1:K1', 'Nominations_', $arrayData);

        
}

function getProofOfLodgiment($nomination_id){
    $proof_of_lodgiment = NominationDocument::where('nomination_id',$nomination_id)
                                            ->where('doc_type','Nomination Lodged')->first(['id']);
    return $proof_of_lodgiment;

}

function getStatus($num_status) : string {
    switch ($num_status) {
        case '1':
            $status = 'Client Quoted';
             break;
         case '2':
             $status = 'Client Invoiced';
             break;
         case '3':
             $status = 'Client Paid';
             break;
         case '4':
             $status = 'Payment To The Liquor Board';
             break;
         case '5':
             $status = 'Select Nominees';
             break;
         case '6':
             $status = 'Prepare Nomination Application';
             break;
         case '7':
             $status = 'Scanned Application';
             break;
         case '8':
             $status = 'Nomination Lodged';
             break;
         case '9':
             $status = 'Nomination Issued';
             break;
         case '10':
             $status = 'Nomination Delivered';
             break;
         default:
             $status = '';
             break;
    }

    return $status;
}

}