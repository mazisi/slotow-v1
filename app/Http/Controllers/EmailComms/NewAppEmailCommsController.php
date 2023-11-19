<?php

namespace App\Http\Controllers\EmailComms;

use Inertia\Inertia;
use App\Models\LicenceType;
use App\Actions\NewAppsFilterAction;
use App\Http\Controllers\Controller;

class NewAppEmailCommsController extends Controller{

  function getNewAppTemplate() {
    $licences = (new NewAppsFilterAction)->filterLicence();
    $all_licence_types = LicenceType::get();

    return Inertia::render('EmailComms/NewApps',['all_licence_types' => $all_licence_types,'licences' => $licences]);
    
  }

}