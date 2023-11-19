<?php

namespace App\Actions;

class LicenceStatus {

  static function getLicenceStatus($num_status) : string {
  
    switch ($num_status) {
        case '1':
           $status = 'Client Quoted';
            break;
        case '2':
            $status = 'Deposit Paid';
            break;
        case '3':
            $status = 'Client Invoiced';
            break;
        case '4':
            $status = 'Prepare New Application';
            break;
        case '5':
            $status = 'Payment To The Liquor Board';
            break;
        case '6':
            $status = 'Scanned Application';
            break;
        case '7':
            $status = 'Application Lodged';
            break;
        case '8':
            $status = 'Initial Inspection';
            break;
        case '9':
            $status = 'Liquor Board Requests';
            break;
        case '10':
            $status = 'Final Inspection';
            break;
        case '11':
            $status = 'Activation Fee Requested';
            break;
        case '12':
            $status = 'Client Finalisation Invoice';
            break;
        case '13':
            $status = 'Client Paid';
            break;
        case '14':
            $status = 'Activation Fee Paid';
            break;
        case '15':
            $status = 'Licence Issued';
            break;
        case '16':
            $status = 'Licence Delivered';
            break;
        default:
            $status='';
            break;
        }
      return $status;

  
  }
}