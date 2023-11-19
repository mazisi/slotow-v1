<?php

namespace App\Http\Controllers\Reports;

use App\Actions\ExportNotes;
use App\Actions\ExportToSpreadsheet;
use App\Http\Controllers\Controller;
use App\Models\TemporalLicenceDocument;
use App\Actions\ReportShouldHaveStatusInterface;
use App\Http\Controllers\Reports\ReportFilters\TemporalExportReportFilter;

class TemporaLExportController extends Controller implements ReportShouldHaveStatusInterface
{
    public static function export($request){
     
        $arrayData = array(
            array(
            'EVENT NAME',
            'APPLICANT',
            'EVENT DATES',
            'INVOICE NUMBER',
            'PROVINCE/REGION',
            'PAYMENT DATE',
            'LICENCE NUMBER',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'COMMENTS'
            )
        );
   

    $arr_of_licences = (new TemporalExportReportFilter)->filter($request)->toArray(); 

    for($i = 0; $i < count($arr_of_licences); $i++ ){
             
       $data = [ 

               $arr_of_licences[$i]->event_name, 
               self::getApplicant($arr_of_licences[$i]->belongs_to, $arr_of_licences[$i]),
               date('d-m-Y', strtotime($arr_of_licences[$i]->start_date)). ' - '.date('d-m-Y', strtotime($arr_of_licences[$i]->end_date)),
               ' ',
               $arr_of_licences[$i]->address,
               $arr_of_licences[$i]->client_paid_at,
               $arr_of_licences[$i]->liquor_licence_number,
               $arr_of_licences[$i]->logded_at ? date('Y/m/d', strtotime($arr_of_licences[$i]->logded_at)) : '',
               self::getProofOfLodgiment($arr_of_licences[$i]->id) ? 'TRUE': 'FALSE',
               $arr_of_licences[$i]->issued_at ? date('d M Y', strtotime($arr_of_licences[$i]->issued_at)) : '',
               (new TemporaLExportController)->getStatus($arr_of_licences[$i]->status),
               ExportNotes::getNoteExports($arr_of_licences[$i]->id, 'Temporal Licence')
            ];

              $arrayData[] = $data;

        }

        (new ExportToSpreadsheet)->exportToExcel('A1:L1', 'Temporary_licences_', $arrayData);

   
}

    static function getProofOfLodgiment($licence_id){
        $licence_logded = TemporalLicenceDocument::where('temporal_licence_id',$licence_id)
                                                ->where('doc_type','Licence Lodged')->first(['id']);

        return $licence_logded;

    }

    static function getApplicant($belongs_to, $model){
        switch ($belongs_to) {
            case 'Company':
                $applicant = $model->name;
                break;
            case 'Person':
                $applicant = $model->full_name;
                break;                
            default:
                $applicant = "";
                break;
        }
        return $applicant;
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
            $status = 'Prepare Temporary Application';
            break;
        case '5':
            $status = 'Payment To The Liquor Board';
            break;
        case '6':
            $status = 'Scanned Application';
            break;
        case '7':
            $status = 'Temporary Licence Lodged ';
            break;
        case '8':
            $status = 'Temporary Licence Issued ';
            break;
        case '9':
            $status = 'Temporary Licence Delivered';
            break;
       
        default:
            $status = '';
            break;
        }
    return $status;
}


}