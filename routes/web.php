<?php

use Inertia\Inertia;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

use App\Http\Controllers\IssueController;

use App\Http\Controllers\PersonController;

use App\Http\Controllers\CompanyController;

use App\Http\Controllers\ContactController;

use App\Http\Controllers\LicenceController;

use App\Http\Controllers\ViewFileController;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\EmailCommsController;

use App\Http\Controllers\NominationController;

use App\Http\Controllers\CompanyDocsController;

use App\Http\Controllers\EmailReportController;

use App\Http\Controllers\InsertTypesController;

use App\Http\Controllers\LicenceDocsController;

use App\Http\Controllers\AlterLicenceController;

use App\Http\Controllers\TransferDocsController;

use App\Http\Controllers\MergeDocumentController;

use App\Http\Controllers\LicenceRenewalController;

use App\Http\Controllers\NewApplicationController;

use App\Http\Controllers\PeopleDocumentController;

use App\Http\Controllers\Reports\ReportController;

use App\Http\Controllers\SlotowDashboardController;

use App\Http\Controllers\TemporalLicenceController;

use App\Http\Controllers\TransferLicenceController;

use App\Actions\EmmailCommsHandlers\HandleNewAppMail;

use App\Actions\EmmailCommsHandlers\HandleRenewalMail;

use App\Http\Controllers\AlterationDocumentController;

use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\Slowtowdmin\AdminsController;
use App\Actions\EmmailCommsHandlers\HandleTransferMail;
use App\Http\Controllers\TemporalLicenceDocsController;
use App\Http\Controllers\NominationEmailCommsController;
use App\Actions\EmmailCommsHandlers\HandleAlterationMail;
use App\Actions\EmmailCommsHandlers\HandleNominationMail;
use App\Actions\EmmailCommsHandlers\HandleTemporalLicenceMail;
use App\Http\Controllers\EmailComms\NewAppEmailCommsController;
use App\Http\Controllers\Slowtowdmin\AddCompanyAdminController;
use App\Http\Controllers\EmailComms\TransferEmailCommsController;
use App\Http\Controllers\EmailComms\AlterationEmailCommsController;
use App\Http\Controllers\EmailComms\TemporaryLicenceEmailCommsController;

Route::group([], __DIR__.'/company_admin.php');

Route::get('/test',function(){
    return view('emails.ecomms.mail_base_template');
});

Route::get('/insert-licence-type',[InsertTypesController::class,'insert']);
Route::get('/insert-years',[InsertTypesController::class,'insert_years']);


Route::get('/slotow-admin-dashboard',[SlotowDashboardController::class,'index'])->name('slowtow_dashboard');



Route::get('/dashboard',[LoginController::class,'redirect_to_dash'])->name('dashboard');



Route::group(['middleware' => ['guest']], function () { 



 Route::get('/',function(){return Inertia::render('Auth/Login');})->name('home');



  Route::post('/login',[LoginController::class,'authenticate'])->name('login');

});





    Route::get('/logout',[LoginController::class,'logout'])->name('logout');





    //Error Pages
    Route::get('server-error',function(){return Inertia::render('ErrorPages/ServerError');});
    Route::get('file-not-found',function(){return Inertia::render('ErrorPages/FileNotFound');});
    Route::get('access-denied',function(){return Inertia::render('ErrorPages/AccessDenied');});



    Route::group(['middleware' => ['auth', 'user_active','role:slowtow-admin|slowtow-user']], function () {

        


        Route::get('/slotow-admins',[AdminsController::class,'index'])->name('slotow_admins');

        Route::post('/submit-user',[AdminsController::class,'store']);

        Route::post('/update-user',[AdminsController::class,'update']);

        Route::post('/deactivate-user/{id}/{status}',[AdminsController::class,'deactivate']);

        Route::patch('/delete-user/{id}',[AdminsController::class,'destroy']);





        //update password

        Route::get('/settings',[PasswordResetController::class,'index'])->name('settings');

        Route::post('/update-my-password',[PasswordResetController::class,'updatePassword'])->name('update_my_password');



        

        Route::post('/add-company-admin',[AddCompanyAdminController::class,'store']);



        Route::get('/companies',[CompanyController::class,'index'])->name('companies');

        Route::get('/create-company',[CompanyController::class,'create'])->name('create_company');

        Route::get('/view-company/{slug}',[CompanyController::class,'show'])->name('view_company');

        Route::post('/submit-company',[CompanyController::class,'store'])->name('company.submit');

        Route::post('/update-company',[CompanyController::class,'update'])->name('company.update');

        Route::post('/submit-company-documents',[CompanyDocsController::class,'store']);

        Route::delete('/delete-company-document/{id}',[CompanyDocsController::class,'destroy']);

        Route::post('/add-people-to-company/{company_id}',[CompanyController::class,'attachPeopleToCompany']);

        Route::patch('/update-position/{id}',[CompanyController::class,'updatePeople']);

        Route::delete('/delete-company/{slug}',[CompanyController::class,'destroy']);

        Route::patch('/update-company-active-status/{slug}',[CompanyController::class,'updateActiveStatus']);



        Route::delete('/unlink-person/{id}',[CompanyController::class,'unlinkPerson'])->name('unlink_person');





        Route::get('/licences',[LicenceController::class,'index'])->name('licences');

        Route::get('/create-licence',[LicenceController::class,'create'])->name('create_licence');

        Route::get('/view-licence',[LicenceController::class,'show'])->name('view_licence');

        Route::post('/submit-licence',[LicenceController::class,'store'])->name('submit_licence');

        Route::patch('/update-licence/{slug}',[LicenceController::class,'update'])->name('update_licence');

        Route::delete('/delete-licence/{slug}',[LicenceController::class,'destroy'])->name('delete_licence');

        Route::patch('/update-licence-active-status/{slug}',[LicenceController::class,'updateActiveStatus']);



        //New Apps

        Route::get('/create-new-app',[NewApplicationController::class,'create'])->name('create_new_app');

        Route::post('/submit-new-app',[NewApplicationController::class,'store'])->name('submit_new_app');

        Route::patch('/update-new-app/{slug}',[NewApplicationController::class,'update'])->name('update_new_app');

        Route::get('/new-application',[NewApplicationController::class,'view_registration'])->name('view_registration');



        Route::post('/upload-licence-document',[LicenceDocsController::class,'store']);

        Route::delete('/delete-licence-document/{id}',[LicenceDocsController::class,'destroy'])->name('delete_licence_doc');

        Route::patch('/update-new-registration/{slug}',[NewApplicationController::class,'updateRegistration']);

        ///Update status on check

        Route::patch('/update-registration-date/{slug}',[NewApplicationController::class,'updateRegistrationDate']);

        Route::post('/merge-licence-docs/{id}',[LicenceDocsController::class,'merge']);




         // Get licence renewals.

        Route::get('/renew-licence',[LicenceRenewalController::class,'renewLicence'])->name('renew_licence');

        Route::post('/submit-licence-renewal',[LicenceRenewalController::class,'store'])->name('renew_licence.submit');

        Route::get('/view-licence-renewal/{slug}',[LicenceRenewalController::class,'show'])->name('view_licence_renewal');

        Route::patch('/update-renewal',[LicenceRenewalController::class,'update'])->name('update_licence_renewal');

        Route::post('/submit-renewal-documents',[LicenceRenewalController::class,'uploadDocuments']);

        Route::delete('/delete-renewal-document/{id}',[LicenceRenewalController::class,'deleteDocument']);

        Route::patch('/update-renewal-date/{slug}',[LicenceRenewalController::class,'updateDates']);

        Route::delete('/delete-licence-renewal/{licence_slug}/{slug}',[LicenceRenewalController::class,'destroy'])->name('delete_licence_renewal');



        



        Route::get('/transfer-licence',[TransferLicenceController::class,'index'])->name('transfer_licence');

        Route::post('/transfer-licence-submit/{slug}',[TransferLicenceController::class,'store'])->name('transfer_licence.submit');

        Route::get('/transfer-history',[TransferLicenceController::class,'transferHistory'])->name('transfer_history');

        Route::get('/view-transfered-licence/{slug}',[TransferLicenceController::class,'viewTransferedLicence'])->name('view_transfered_licence');

        Route::delete('/delete-licence-transfer/{slug}/{licence_slug}',[TransferLicenceController::class,'destroy'])->name('delete_licence_transfer');

        Route::patch('/update-licence-transfer',[TransferLicenceController::class,'update'])->name('update_licence_transfer');
        Route::patch('/update-transfer-date/{slug}',[TransferLicenceController::class,'updateDates']);





        Route::post('/submit-transfer-documents/{transfer_id}',[TransferDocsController::class,'store'])->name('transfer_licence_docs');

        Route::post('/transfer-documents-merge',[TransferDocsController::class,'merge']);

        Route::delete('/delete-transfer-document/{document_id}',[TransferDocsController::class,'destroy']);



        Route::get('/alterations',[AlterLicenceController::class,'index'])->name('alterations');

        Route::get('/new-alteration',[AlterLicenceController::class,'newAlteration'])->name('new_alteration');

        Route::post('/submit-altered-licence/{licence_id}',[AlterLicenceController::class,'store'])->name('alter_licence.submit');

        Route::delete('/delete-altered-licence/{slug}/{licence_slug}',[AlterLicenceController::class,'destroy'])->name('delete_altered_licence.submit');

        Route::get('/view-alteration/{slug}',[AlterLicenceController::class,'show'])->name('view_alteration');

        Route::patch('/update-alteration',[AlterLicenceController::class,'update'])->name('update_alteration');

        Route::post('/submit-alteration-document',[AlterationDocumentController::class,'store']);

        Route::delete('/delete-alteration-document/{id}',[AlterationDocumentController::class,'destroy']);

        Route::post('/merge-alteration-documents/{alteration_id}',[AlterationDocumentController::class,'merge']);

        Route::patch('/update-alteration-date/{slug}',[AlterLicenceController::class,'updateAlterationDate']);



        Route::get('/goverify-contacts',[ContactController::class,'index'])->name('contacts');

        Route::get('/upload-contacts',[ContactController::class,'create'])->name('upload_contacts');

        Route::post('/store-individual-contact',[ContactController::class,'storeIndividualContact'])->name('store_individual_contact');

        Route::patch('/update-individual-contact/{id}',[ContactController::class,'updateIndividualContact'])->name('update_individual_contact');

        Route::delete('/delete-individual-contact/{id}',[ContactController::class,'destroy'])->name('delete_individual_contact');

        Route::get('/create-contact',[ContactController::class,'createContact'])->name('create_contacts');

        Route::get('/view-contact/{id}',[ContactController::class,'viewContact'])->name('view_contact');

        Route::post('/submit-contacts',[ContactController::class,'store'])->name('submit_contacts');

        Route::delete('/delete-contact/{id}',[ContactController::class,'destroy'])->name('delete_contact');



        //Reports

        Route::get('/reports',[ReportController::class,'index'])->name('reports');

        Route::get('/export-report',[ReportController::class,'export'])->name('export');



        Route::get('/people', [PersonController::class,'index'])->name('people');

        Route::get('/create-person', [PersonController::class,'create'])->name('create_person');

        Route::post('/submit-person', [PersonController::class,'store'])->name('submit_person');

        Route::get('/view-person/{slug}', [PersonController::class,'show'])->name('view_person');

        Route::post('/update-person', [PersonController::class,'update'])->name('update_person');

        Route::delete('/delete-person/{slug}', [PersonController::class,'destroy'])->name('delete_person');

        Route::post('/upload-person-documents', [PeopleDocumentController::class,'uploadDocument']);

        Route::delete('/delete-person-document/{slug}', [PeopleDocumentController::class,'deleteDocument']);

        Route::patch('/update-person-active-status/{slug}',[PersonController::class,'updateActiveStatus']);

       



        Route::get('/nominate',[NominationController::class,'index'])->name('nominate');

        Route::post('/submit-nomination', [NominationController::class,'store'])->name('submit_nomination');

        Route::get('/nominations',[NominationController::class,'index'])->name('nominations');

        Route::get('/view-nomination/{slug}',[NominationController::class,'viewIndividualNomination'])->name('view_nomination');

        Route::post('/terminate-person/{id}/{slug}', [NominationController::class,'terminate'])->name('terminate_person');

        Route::patch('/update-nominee',[NominationController::class,'update'])->name('update_nominee');

        Route::post('/add-selected-nominees',[NominationController::class,'addSelectedNominees']);

        Route::post('/detach-nominee/{nomination_id}/{nominee_id}',[NominationController::class,'detachNominee']);

        Route::post('/submit-nomination-document',[NominationController::class,'uploadDocument']);

        Route::delete('/delete-nomination-document/{id}',[NominationController::class,'deleteDocument']);

        Route::delete('/delete-nomination/{licence_slug}/{slug}',[NominationController::class,'destroy']);

        Route::patch('/update-nomination-date/{slug}',[NominationController::class,'updateDate']);



        Route::get('/merge-document/{id}',[MergeDocumentController::class,'merge'])->name('merge');





        Route::get('/temp-licences', [TemporalLicenceController::class,'index'])->name('temp_licences');

        Route::get('/create-temp-licence', [TemporalLicenceController::class,'create'])->name('create_temp_licence');

        Route::post('/submit-temp-licence', [TemporalLicenceController::class,'store'])->name('store_temp_licence');

        Route::get('/view-temp-licence/{slug}', [TemporalLicenceController::class,'show'])->name('view_temp_licence');

        Route::get('/process-temp-application', [TemporalLicenceController::class,'processApplication']);

        Route::patch('/update-prepared-temp-app/{slug}', [TemporalLicenceController::class,'update_prepared_temp_app']);

        Route::delete('/delete-temporal-licence/{slug}', [TemporalLicenceController::class,'destroy']);

        Route::patch('/update-temp-licence', [TemporalLicenceController::class,'update'])->name('update_temp_licence');

        Route::patch('/update-process-app-date/{slug}', [TemporalLicenceController::class,'updateDates']);

        

        Route::post('/submit-temporal-licence-document', [TemporalLicenceDocsController::class,'store']);

        Route::delete('/delete-temporal-licence-document/{id}', [TemporalLicenceDocsController::class,'destroy']);

        Route::post('/merge-temporal-documents/{type}', [TemporalLicenceDocsController::class,'merge']);

        
        Route::get('view-file/{model}/{id}', [ViewFileController::class,'view_file'])->name('view_file');


        Route::post('/submit-task',[TaskController::class,'store'])->name('submit_task');

        Route::delete('/delete-task/{id}',[TaskController::class,'destroy'])->name('delete_task');



        Route::get('/email-comms', [EmailCommsController::class,'index'])->name('email_comms');

        Route::get('/email-comms/transfers', [TransferEmailCommsController::class,'getLicenceTransfers'])->name('get_licence_transfers');

        Route::get('/email-comms/nominations', [NominationEmailCommsController::class,'getNominations'])->name('get_nominations');

        Route::get('/email-comms/alterations', [AlterationEmailCommsController::class,'getAlterationTemplate'])->name('get_alterations');

        Route::get('/email-comms/get-mail-template/{slug}/{licence_variation}', [EmailCommsController::class,'getMailTemplate']);

        Route::get('/email-comms/temp-licences', [TemporaryLicenceEmailCommsController::class,'getTemporaryLicenceTemplate'])->name('get_temp_licences');
 
        Route::post('email-comms/filter-by-month', [EmailCommsController::class,'index']);

        Route::get('/email-comms/new-apps', [NewAppEmailCommsController::class,'getNewAppTemplate'])->name('get_new_app_template');

        

        //Emmails Not sent 

        Route::get('emails-report', [EmailReportController::class,'index']);

        Route::post('/dispatch-alteration-mail', [HandleAlterationMail::class,'dispatchAlterationMail'])->name('dispatch_alteration_mail');

        Route::post('/dispatch-temporal-licence-mail', [HandleTemporalLicenceMail::class,'dispatchTemporalMail'])->name('dispatch_temporal_mail');
        
        Route::post('/dispatch-new-app-mail', [HandleNewAppMail::class,'dispatchNewAppMail'])->name('dispatch_new_app_mail');

        //renewals mail dispatcher

        Route::post('/dispatchRenewalMail', [HandleRenewalMail::class,'dispatchRenewalMail'])->name('dispatch_renewal_mail');

        //transfers mail dispatcher

        Route::post('/dispatchTransferMail', [HandleTransferMail::class,'dispatchTransferMail'])->name('dispatch_transfer_mail');

        //nomination mail dispatcher

        Route::post('/dispatchNominationMail', [HandleNominationMail::class,'dispatchNominationMail'])->name('dispatch_nom_mail');



       
    });

    Route::get('email-template',function(){
        return view('emails.mail-template');
    });

   
    Route::group(['middleware' => ['auth']], function () {


        Route::get('issues', [IssueController::class,'index'])->name('issues');

        Route::get('create-issue', [IssueController::class,'create'])->name('create_issue');

        Route::get('view-issue/{slug}', [IssueController::class,'show'])->name('view_issue');

        Route::post('submit-issue', [IssueController::class,'store'])->name('submit_issue');

        Route::post('change-issue-status/{slug}/{status}', [IssueController::class,'changeStatus']);
        
        Route::patch('update-issue/{slug}', [IssueController::class,'update']);
});

