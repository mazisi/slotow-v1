<?php

namespace App\Actions;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportToSpreadsheet{


  
  function exportToExcel($column_range, $file_name, $arrayData){
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()
                ->fromArray(
                $arrayData,   // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where        //    we want to set these values (default is A1)
                );

                foreach ($spreadsheet->getActiveSheet()->getColumnIterator() as $column) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
                }

                $spreadsheet->getActiveSheet()->getStyle($column_range)->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle($column_range)->getAlignment()->setWrapText(true);

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$file_name.now()->format('d_m_y').'.xlsx"');
                header('Cache-Control: max-age=0');        
                $writer = new Xlsx($spreadsheet);
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                die;
  }
}