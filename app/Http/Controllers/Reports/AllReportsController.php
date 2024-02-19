<?php

namespace App\Http\Controllers\Reports;


use App\Actions\ExportNotes;
use App\Actions\LicenceStatus;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Reports\RenewalExportController;
use App\Http\Controllers\Reports\TransferExportController;
use App\Http\Controllers\Reports\AlterationExportController;
use App\Http\Controllers\Reports\NominationExportController;
use App\Http\Controllers\Reports\ReportFilters\AlterationFilter;
use App\Http\Controllers\Reports\ExistingLicenceExportController;
use App\Http\Controllers\Reports\ReportFilters\NewAppReportFilter;
use App\Http\Controllers\Reports\ReportFilters\RenewalReportFilter;
use App\Http\Controllers\Reports\ReportFilters\TransferReportFilter;
use App\Http\Controllers\Reports\ReportFilters\NominationReportFilter;
use App\Http\Controllers\Reports\ReportFilters\TemporalExportReportFilter;
use App\Http\Controllers\Reports\ReportFilters\ExistingLicenceReportFilter;

class AllReportsController extends Controller
{
    public static function exportAll($request){

        $alterationData = array(
            array(                
                'TRADING NAME',
                'LICENCE HOLDER',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DATE LODGED',
                'PROOF OF LODGIMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'COMMENT IF APPLICABLE'
            )
        );
      
               $arr_of_alterations = (new AlterationFilter)->filter($request)->toArray(); 

                for($i = 0; $i < count($arr_of_alterations); $i++ ){
                                            
                        $proof_of_lodgiment = (new AlterationExportController)->getProofOfLodgiment($arr_of_alterations[$i]->id);
                        //get alteration notes
                                                 
                        
                        $data = [
                            $arr_of_alterations[$i]->trading_name, 
                            getLicenceHolder($arr_of_alterations[$i]),
                            $arr_of_alterations[$i]->licence_number, 
                            request('boardRegion') ? $arr_of_alterations[$i]->province.'-'.$arr_of_alterations[$i]->board_region : $arr_of_alterations[$i]->province,
                            $arr_of_alterations[$i]->logded_at,
                            $proof_of_lodgiment ? 'TRUE' : 'FALSE',
                            $arr_of_alterations[$i]->certification_issued_at,
                            (new AlterationExportController)->getStatus($arr_of_alterations[$i]->status), 
                            ExportNotes::getNoteExports($arr_of_alterations[$i]->id, 'Alteration')
                            ];

                        $alterationData[] = $data;

                }
                
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->setTitle("Alterations")
                ->fromArray(
                $alterationData,
                NULL,
                'A1'  
                );

        foreach ($spreadsheet->getActiveSheet()->getColumnIterator() as $column) {
        $spreadsheet->getActiveSheet()->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(true);



        
        $existingLicences = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Existing');
        $spreadsheet->addSheet($existingLicences, 1);

        $existingLicencesData = array(
            array(
            'TRADING NAME',
            'LICENCE HOLDER',
            'LICENCE TYPE',
            'LICENCE NUMBER',
            'PROVINCE/REGION',
            'DEPOSIT INVOICE',
            'DEPOSIT PAID',
            'DATE LODGED',
            'PROOF OF LODGEMENT',
            'ACTIVATION FEE PAID',
            'FINAL INVOICE',
            'FULL PAYMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'FOCUS FOR THE WEEK',
            'COMMENTS'
            )
        );
            
            $arr_of_existing_licences = (new ExistingLicenceReportFilter)->filter($request)->toArray(); 

            for($i = 0; $i < count($arr_of_existing_licences); $i++ ){
                
                    $data = [ 
                        $arr_of_existing_licences[$i]->trading_name, 
                        getLicenceHolder($arr_of_existing_licences[$i]),
                        $arr_of_existing_licences[$i]->licence_type,
                        $arr_of_existing_licences[$i]->licence_number,
                        request('boardRegion') ? $arr_of_existing_licences[$i]->province.' - '.$arr_of_existing_licences[$i]->board_region : $arr_of_existing_licences[$i]->province,
                        '',
                        $arr_of_existing_licences[$i]->deposit_paid_at ? 'TRUE': 'FALSE',
                        $arr_of_existing_licences[$i]->application_lodged_at ? date('Y/m/d', strtotime($arr_of_existing_licences[$i]->application_lodged_at)) : '',
                        (new ExistingLicenceExportController)->getProofOfLodgiment($arr_of_existing_licences[$i]->id) ? 'TRUE': 'FALSE',
                        $arr_of_existing_licences[$i]->activation_fee_paid_at,
                        '',
                        $arr_of_existing_licences[$i]->client_paid_at ? date('d-m-Y', strtotime($arr_of_existing_licences[$i]->client_paid_at)) : '',
                        $arr_of_existing_licences[$i]->licence_issued_at ? date('d-m-Y', strtotime($arr_of_existing_licences[$i]->licence_issued_at)) : '',
                        LicenceStatus::getLicenceStatus($arr_of_existing_licences[$i]->status),
                        '',
                        ExportNotes::getNoteExports($arr_of_existing_licences[$i]->id, 'Licence')
                     ];

                $existingLicencesData[] = $data;

                }
                $spreadsheet->setActiveSheetIndex(1)
                ->fromArray(
                $existingLicencesData,
                NULL, 
                'A1'  
                );

                foreach ($spreadsheet->setActiveSheetIndex(1)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(1)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                $spreadsheet->setActiveSheetIndex(1)->getStyle('A1:O1')->getFont()->setBold(true);
                $spreadsheet->setActiveSheetIndex(1)->getStyle('A1:O1')->getAlignment()->setWrapText(true);

        
        
        
        $newApplications = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'New Apps');
        $spreadsheet->addSheet($newApplications, 2);
        $newAppsData = array(
            array(
                'TRADING NAME',
                'LICENCE HOLDER',
                'LICENCE TYPE',
                'LICENCE NUMBER',
                'PROVINCE/REGION',
                'DEPOSIT INVOICE',
                'DEPOSIT PAID',
                'DATE LODGED',
                'PROOF OF LODGEMENT',
                'ACTIVATION FEE PAID',
                'FINAL INVOICE',
                'FULL PAYMENT',
                'DATE GRANTED',
                'CURRENT STATUS',
                'FOCUS FOR THE WEEK',
                'COMMENTS'
                
            )
        );
        

        $arr_of_new_apps_licences = (new NewAppReportFilter)->filter($request)->toArray(); 
   
    for($i = 0; $i < count($arr_of_new_apps_licences); $i++ ){
       
            $data = [ 
                $arr_of_new_apps_licences[$i]->trading_name,
                getLicenceHolder($arr_of_new_apps_licences[$i]), 
                $arr_of_new_apps_licences[$i]->licence_type,
                $arr_of_new_apps_licences[$i]->licence_number,
                request('boardRegion') ? $arr_of_new_apps_licences[$i]->province.' - '.$arr_of_new_apps_licences[$i]->board_region : $arr_of_new_apps_licences[$i]->province,
                '',
                $arr_of_new_apps_licences[$i]->deposit_paid_at ? 'TRUE': 'FALSE',
                $arr_of_new_apps_licences[$i]->application_lodged_at ? date('Y/m/d', strtotime($arr_of_new_apps_licences[$i]->application_lodged_at)) : '',
                (new ExistingLicenceExportController)->getProofOfLodgiment($arr_of_new_apps_licences[$i]->id) ? 'TRUE': 'FALSE',
                $arr_of_new_apps_licences[$i]->activation_fee_paid_at,
                '',
                $arr_of_new_apps_licences[$i]->client_paid_at ? date('d M Y', strtotime($arr_of_new_apps_licences[$i]->client_paid_at)) : '',
                $arr_of_new_apps_licences[$i]->licence_issued_at ? date('d M Y', strtotime($arr_of_new_apps_licences[$i]->licence_issued_at)) : '',
                LicenceStatus::getLicenceStatus($arr_of_new_apps_licences[$i]->status),
                '',
                ExportNotes::getNoteExports($arr_of_new_apps_licences[$i]->id, 'Licence')
             ];

            $newAppsData[] = $data;

            }
            $spreadsheet->setActiveSheetIndex(2)
             ->fromArray(
             $newAppsData,
             NULL, 
             'A1'  
             );
 
             foreach ($spreadsheet->setActiveSheetIndex(2)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(2)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }
    
        $spreadsheet->setActiveSheetIndex(2)->getStyle('A1:O1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(2)->getStyle('A1:O1')->getAlignment()->setWrapText(true);







//  <==============================================Start OF Nomination Export===============================================================->

        $nominations = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Nominations');
        $spreadsheet->addSheet($nominations, 3);




        $arrayNominationData = array(
            array(
                'TRADING NAME',
                'LICENCE HOLDER',
                'Client Name',
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
          
          $arr_of_nominations = (new NominationReportFilter)->filter($request)->toArray(); 
    
    for($i = 0; $i < count($arr_of_nominations); $i++ ){
      
        // $is_client_paid = NominationDocument::where('nomination_id',$arr_of_nominations[$i]->id)->where('doc_type','Payment To The Liquor Board')->first();

               $data = [ 
                $arr_of_nominations[$i]->trading_name, 
                getLicenceHolder($arr_of_nominations[$i]),
                $arr_of_nominations[$i]->full_name, 
                $arr_of_nominations[$i]->licence_number, 
                request('boardRegion') ? $arr_of_nominations[$i]->province.' - '.$arr_of_nominations[$i]->board_region : $arr_of_nominations[$i]->province,
                '',
                $arr_of_nominations[$i]->payment_to_liquor_board_at,
                $arr_of_nominations[$i]->nomination_lodged_at,
                (new NominationExportController)->getProofOfLodgiment($arr_of_nominations[$i]->id) ? 'TRUE' : 'FALSE',
                $arr_of_nominations[$i]->nomination_issued_at,
                (new NominationExportController)->getStatus($arr_of_nominations[$i]->status),
                ExportNotes::getNoteExports($arr_of_nominations[$i]->id, 'Nomination') 
             ];

               $arrayNominationData[] = $data;

        }

        $spreadsheet->setActiveSheetIndex(3)
         ->fromArray(
         $arrayNominationData,
         NULL,
         'A1'
         );

         foreach ($spreadsheet->setActiveSheetIndex(3)->getColumnIterator() as $column) {
            $spreadsheet->setActiveSheetIndex(3)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:O1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(3)->getStyle('A1:O1')->getAlignment()->setWrapText(true);

        //  <=================================================End OF Nomination Export=====================================================->



        // <===================================================Renewals Export Start=====================================================>
        $renewals = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Renewals');
        $spreadsheet->addSheet($renewals, 4);



        $arrayRenewalData = array(
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

               $arrayRenewalData[] = $data;

                }
                
                $spreadsheet->setActiveSheetIndex(4)
                    ->fromArray(
                    $arrayRenewalData,
                    NULL,
                    'A1'
                    );

                foreach ($spreadsheet->setActiveSheetIndex(4)->getColumnIterator() as $column) {
                    $spreadsheet->setActiveSheetIndex(4)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                    $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getFont()->setBold(true);
                    $spreadsheet->setActiveSheetIndex(4)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Renewals Export===============================================================>



// <======================================Temporary Exports Begin=================================================================>
        $temporary_apps = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Temporary Apps');
        $spreadsheet->addSheet($temporary_apps, 5);

        $arrayTempData = array(
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

    $arr_of_temp_licences = (new TemporalExportReportFilter)->filter($request)->toArray(); 

    for($i = 0; $i < count($arr_of_temp_licences); $i++ ){
                    
            $data = [ 

                $arr_of_temp_licences[$i]->event_name, 
                (new TemporaLExportController)->getApplicant($arr_of_temp_licences[$i]->belongs_to, $arr_of_temp_licences[$i]),
                date('d-m-Y', strtotime($arr_of_temp_licences[$i]->start_date)). ' - '.date('d-m-Y', strtotime($arr_of_temp_licences[$i]->end_date)),
                '',
                $arr_of_temp_licences[$i]->address,
                //$get_invoice_number ? '' : $get_invoice_number->document_name,
                $arr_of_temp_licences[$i]->client_paid_at,
                $arr_of_temp_licences[$i]->liquor_licence_number,
                $arr_of_temp_licences[$i]->logded_at ? date('d M Y', strtotime($arr_of_temp_licences[$i]->logded_at)) : '',
                (new TemporalExportController)->getProofOfLodgiment($arr_of_temp_licences[$i]->id) ? 'TRUE': 'FALSE',
                $arr_of_temp_licences[$i]->issued_at ? date('d M Y', strtotime($arr_of_temp_licences[$i]->issued_at)) : '',
                (new TemporaLExportController)->getStatus($arr_of_temp_licences[$i]->status),
                ExportNotes::getNoteExports($arr_of_temp_licences[$i]->id, 'Temporal Licence')
             ];
 

    $arrayTempData[] = $data;

        }
        
            $spreadsheet->setActiveSheetIndex(5)
             ->fromArray(
             $arrayTempData,   // The data to set
             NULL,        // Array values with this value will not be set
             'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
             );

             foreach ($spreadsheet->setActiveSheetIndex(5)->getColumnIterator() as $column) {
                $spreadsheet->setActiveSheetIndex(5)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }

                $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')->getFont()->setBold(true);
                $spreadsheet->setActiveSheetIndex(5)->getStyle('A1:L1')->getAlignment()->setWrapText(true);
// <=================================End OF Temp Export===============================================================>
        

        $transfers = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Transfers');
        $spreadsheet->addSheet($transfers, 6);

        $arrayTransferData = array(
            array(
                'CURRENT TRADING NAME',
                'LICENCE HOLDER',
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
                
                $arr_of_transfers = (new TransferReportFilter)->filter($request)->toArray(); 
    
                for($i = 0; $i < count($arr_of_transfers); $i++ ){                               

                        $data = [         
                            $arr_of_transfers[$i]->trading_name,
                            (new TransferExportController)->getTransferHolder($arr_of_transfers[$i]->transfered_to, $arr_of_transfers[$i]),
                            $arr_of_transfers[$i]->province == 'Gauteng' ? $arr_of_transfers[$i]->licence_number : '',
                            request('boardRegion') ? $arr_of_transfers[$i]->province.' - '.$arr_of_transfers[$i]->board_region : $arr_of_transfers[$i]->province,
                            '',
                            '',
                            $arr_of_transfers[$i]->lodged_at,
                            (new TransferExportController)->getProofOfLodgiment($arr_of_transfers[$i]->id) ? 'TRUE' : 'FALSE',
                            '',
                            $arr_of_transfers[$i]->payment_to_liquor_board_at,
                            $arr_of_transfers[$i]->issued_at,
                            (new TransferExportController)->getStatus($arr_of_transfers[$i]->status),
                            ExportNotes::getNoteExports($arr_of_transfers[$i]->id, 'Transfer')
                         ];
    
                $arrayTransferData[] = $data;
    
                    }
    
           
                $spreadsheet->setActiveSheetIndex(6)
                 ->fromArray(
                 $arrayTransferData,   // The data to set
                 NULL,        // Array values with this value will not be set
                 'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
                 );
     
        foreach ($spreadsheet->setActiveSheetIndex(6)->getColumnIterator() as $column) {
                     $spreadsheet->setActiveSheetIndex(6)->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                     
                  }
         
         $spreadsheet->setActiveSheetIndex(6)->getStyle('A1:L1')->getFont()->setBold(true);
         $spreadsheet->setActiveSheetIndex(6)->getStyle('A1:L1')
         ->getAlignment()->setWrapText(true);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="All_Apps_'.now()->format('d_m_y').'.xlsx"');
        header('Cache-Control: max-age=0');        
        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die;
      
    }
}