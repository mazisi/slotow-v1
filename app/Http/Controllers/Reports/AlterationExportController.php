<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Models\AlterationDocument;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\AlterationFilter;

class AlterationExportController extends Controller implements ReportShouldHaveStatusInterface
{
    public static function export($request){
        $arrayData = array(
            array(                
                'TRADING NAME',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGEMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
               $arr_of_alterations = (new AlterationFilter)->filter($request)->toArray(); 

                for($i = 0; $i < count($arr_of_alterations); $i++ ){
                    $status = (new AlterationExportController)->getStatus($arr_of_alterations[$i]->status);
                    $proof_of_lodgiment = (new AlterationExportController)->getProofOfLodgiment($arr_of_alterations[$i]->id);

                              
                        
                        $data = [
                        $arr_of_alterations[$i]->trading_name, 
                        $arr_of_alterations[$i]->licence_number, 
                        request('boardRegion') ? $arr_of_alterations[$i]->province.'-'.$arr_of_alterations[$i]->board_region : $arr_of_alterations[$i]->province,
                        $arr_of_alterations[$i]->logded_at,
                        $proof_of_lodgiment ? 'TRUE' : 'FALSE',
                        $arr_of_alterations[$i]->certification_issued_at,
                        $status, 
                        ExportNotes::getNoteExports($arr_of_alterations[$i]->id, 'Alteration'),
                        ];
                        $arrayData[] = $data;

                }
                  (new ExportToSpreadsheet)->exportToExcel('A1:H1', 'Alterations_', $arrayData);            
   
   
        }

        function getProofOfLodgiment($alteration_id){
            $proof_of_lodgiment = AlterationDocument::where('alteration_id',$alteration_id)
                                                    ->where('doc_type','Alterations Lodged')->first(['id']);
            return $proof_of_lodgiment;

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
                    $status = 'Prepare Alterations Application';
                    break;
                case '5':
                    $status = 'Payment to the Liquor Board';
                    break;
                case '6':
                    $status = 'Alterations Lodged';
                    break;
                case '7':
                    $status = 'Alterations Certificate Issued';
                    break;
                case '8':
                    $status = 'Alterations Delivered';
                    break;
                default:
                    $status = '';
                    break;
                }
            return $status;
        }


}