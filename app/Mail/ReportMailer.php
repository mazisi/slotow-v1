<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $report;
    public function __construct(Report $report)
    {
        $this->report=$report;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
     
      public function build(){
        try {
            
        return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                    ->replyTo('info@slotow.co.za')
                    ->subject('Liquor Licence')
                    ->markdown('emails.report')
                    ->attach(storage_path('app/public/All_Apps.Xlsx'));
                    
                } catch (\Throwable $th) {
                    return back()->with('error','Error sending mail.');
                }
    }
    
    
  
}
