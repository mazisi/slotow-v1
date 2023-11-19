<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyDocsController;
use App\Http\Controllers\CompanyAdmin\CompanyController;
use App\Http\Controllers\CompanyAdmin\LicenceController;
use App\Http\Controllers\CompanyAdmin\DashboardController;
use App\Http\Controllers\CompanyAdmin\AlterationController;
use App\Http\Controllers\CompanyAdmin\NominationController;
use App\Http\Controllers\CompanyAdmin\LicenceTransferController;
use App\Http\Controllers\CompanyAdmin\TemporalLicenceController;
use App\Http\Controllers\CompanyAdmin\CompanyRegistrationController;


  Route::group(['middleware' => ['auth','role:company-admin']], function () {
      
      Route::prefix('company')->group(function () {

        Route::get('/dashboard',[DashboardController::class,'index'])->name('company_dashboard');

        Route::get('/licences',[LicenceController::class,'index'])->name('company_admin_licences');

        Route::get('/view-my-licences/{slug}',[LicenceController::class,'show'])->name('view_my_licences');




        Route::get('/my-companies',[CompanyController::class,'index'])->name('my_companies');

        Route::get('/view-my-company/{slug}',[CompanyController::class,'show'])->name('view_my_company');

        Route::post('/submit-company-documents',[CompanyDocsController::class,'store']);

        Route::delete('/delete-company-document/{id}',[CompanyDocsController::class,'destroy']);



        Route::get('/my-renewals',[LicenceController::class,'my_renewals'])->name('my_renewals');

        Route::get('/view-my-licence-renewal/{slug}',[LicenceController::class,'view_my_renewal'])->name('view_renewal');



        Route::get('/my-transfer-history',[LicenceTransferController::class,'index'])->name('my_transfer_history');

        Route::get('/view-my-transfer/{slug}',[LicenceTransferController::class,'view_my_transfer'])->name('view_my_transfer');

        

        Route::get('/my-temp-licences',[TemporalLicenceController::class,'index'])->name('my_temp_licences');

        Route::get('/view-my-temp-licences/{slug}',[TemporalLicenceController::class,'show']);


        Route::get('/nominations',[NominationController::class,'index'])->name('my_nominations');

        Route::get('/view-nomination/{slug}',[NominationController::class,'show'])->name('view_company__nomination');


        Route::get('/alterations',[AlterationController::class,'index'])->name('company_alterations');
        Route::get('/view-company-alteration/{slug}',[AlterationController::class,'show'])->name('view_company_alteration');

        Route::get('/registration',[CompanyRegistrationController::class,'registration'])->name('company_registration');
    });

  });   
