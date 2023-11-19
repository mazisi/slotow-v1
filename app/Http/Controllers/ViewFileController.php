<?php

namespace App\Http\Controllers;

use App\Models\AlterationDocument;
use App\Models\CompanyDocument;
use Inertia\Inertia;
use App\Models\PeopleDocument;
use App\Models\LicenceDocument;
use App\Models\TransferDocument;
use App\Models\NominationDocument;
use App\Models\RenewalDocument;
use App\Models\TemporalLicenceDocument;

class ViewFileController extends Controller
{
    public function view_file($model, $model_id){
        switch ($model) {
            case 'CompanyDocument':
                $company_doc = CompanyDocument::whereId($model_id)->first(['document_file']);               
                    
                if($company_doc){
                    //if(fileExist(env('AZURE_STORAGE_URL').'/'.$company_doc->document_file)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$company_doc->document_file);
                   // }
                   // return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'PeopleDocument':
                $people_doc = PeopleDocument::whereId($model_id)->first(['path']);               
                    
                if($people_doc){
                    //if(fileExist(env('AZURE_STORAGE_URL').'/'.$people_doc->path)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$people_doc->path);
                   // }
                    //return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;


             case 'LicenceDocument':
                    $lic_doc = LicenceDocument::whereId($model_id)->first(['document_file']);      
                        
                    if($lic_doc){
                        //if(fileExist(env('AZURE_STORAGE_URL').'/'.$lic_doc->document_file)){
                            return Inertia::location(env('AZURE_STORAGE_URL').'/'.$lic_doc->document_file);
                    //}
                       // return Inertia::render('ErrorPages/FileNotFound');
                    }
                    return back()->with('error', 'Fatal Error. Please contact administrator.');
                    break;

            case 'TransferDocument':
                $transfer_doc = TransferDocument::whereId($model_id)->first(['document']);               
                    
                if($transfer_doc){
                    //if(fileExist(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$transfer_doc->document);
                   // }
                   // return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'NominationDocument':
                $nom_doc = NominationDocument::whereId($model_id)->first(['document']);               
                    
                if($nom_doc){
                    //if(fileExist(env('AZURE_STORAGE_URL').'/'.$nom_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$nom_doc->document);
                    //}
                    //return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'AlterationDocument':
                $alteration_doc = AlterationDocument::whereId($model_id)->first(['document']);               
                    
                if($alteration_doc){
                    //if(fileExist(env('AZURE_STORAGE_URL').'/'.$alteration_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$alteration_doc->document);
                    //}
                    //return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'RenewalDocument':
                $renewal_doc = RenewalDocument::whereId($model_id)->first(['document']);               
                    
                if($renewal_doc){
                   // if(fileExist(env('AZURE_STORAGE_URL').'/'.$renewal_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$renewal_doc->document);
                  //  }
                   // return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;

            case 'TemporalLicenceDocument':
                $temp_doc = TemporalLicenceDocument::whereId($model_id)->first(['document']);               
                    
                if($temp_doc){
                   // if(fileExist(env('AZURE_STORAGE_URL').'/'.$temp_doc->document)){
                        return Inertia::location(env('AZURE_STORAGE_URL').'/'.$temp_doc->document);
                   // }
                    //return Inertia::render('ErrorPages/FileNotFound');
                }
                return back()->with('error', 'Fatal Error. Please contact administrator.');
                break;
            
            default:
                # code...
                break;
        }
         
     }
}
