<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Report;
use App\Mail\ReportMailer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Reports\AllReportsController;

class ExportAllData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $report = Report::where('variation','All')->where('status','0')->latest()->first();
            if(!is_null($report)){
            AllReportsController::exportAll(request(), $report);
            Mail::to('info@goverify.co.za')->send(new ReportMailer($report));
            Report::whereId($report->id)->where('status','0')->update(['status' => '1']);
            }
    }
}
