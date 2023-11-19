<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailReportController extends Controller
{
    public function index(){
        $emails = Email::when(request('type'), function ($query) {
            return $query->where('model_type', request('type'));
        })
        ->when(request('status'), fn($query) => $query->where('stage', request('status')))->latest()->get();
        return Inertia::render('EmailComms/EmmailReport',['emails' => $emails]);
    }
}
