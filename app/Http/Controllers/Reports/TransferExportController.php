<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\TransferReportFilter;
use App\Models\TransferDocument;

class TransferExportController extends Controller implements ReportShouldHaveStatusInterface
{
public static function export($request){
    $arrayData = array(
        array(
            'CURRENT TRADING NAME',
            'GAU/GLB No',
            'PROVINCE/REGION',
            'DEPOSIT INVOICE',
            'DEPOSIT PAID',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'FINALISATION INVOICE',
            'FINALISATION PAYMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'COMMENTS'
        )
    );
            $arr_of_transfers = [];
    
            $arr_of_transfers = (new TransferReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_transfers); $i++ ){
                $status = (new TransferExportController)->getStatus($arr_of_transfers[$i]->status);
                       
  
             $data = [         
                       $arr_of_transfers[$i]->trading_name, 
                       $arr_of_transfers[$i]->province == 'Gauteng' ? $arr_of_transfers[$i]->licence_number : '',
                       request('boardRegion') ? $arr_of_transfers[$i]->province.' - '.$arr_of_transfers[$i]->board_region : $arr_of_transfers[$i]->province,
                       '',
                       '',
                       $arr_of_transfers[$i]->lodged_at,
                       (new TransferExportController)->getProofOfLodgiment($arr_of_transfers[$i]->id) ? 'TRUE' : 'FALSE',
                       '',
                       $arr_of_transfers[$i]->payment_to_liquor_board_at,
                       $arr_of_transfers[$i]->issued_at,
                       $status,
                       ExportNotes::getNoteExports($arr_of_transfers[$i]->id, 'Transfer')
                    ];

            $arrayData[] = $data;

                }
                
                (new ExportToSpreadsheet)->exportToExcel('A1:L1', 'Transfers_', $arrayData);
       

            

}



function getStatus($number) : string {
    switch ($number) {
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
            $status = 'Prepare Transfer Application';
            break;
        case '5':
            $status = 'Payment To The Liquor Board';
            break;
        case '6':
            $status = 'Scanned Application';
            break;
        case '7':
            $status = 'Application Logded';
            break;
        case '8':
            $status = 'Activation Fee Paid';
            break;
        case '9':
            $status = 'Transfer Issued';
            break;
        case '10':
            $status = 'Transfer Delivered';
            break;
        default:
            $status = '';
            break;
    }
    return $status;
}         


function getProofOfLodgiment($licence_transfer_id){
    $proof_of_lodgiment = TransferDocument::where('licence_transfer_id',$licence_transfer_id)
                                            ->where('doc_type','Transfer Logded')->first(['id']);
    return $proof_of_lodgiment;

}

}