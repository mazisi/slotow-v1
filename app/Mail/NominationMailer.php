<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NominationMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $nomination,$template, $doc_path;
    public function __construct($nomination,$template, $doc_path){
       $this->nomination = $nomination;
       $this->template = $template;
       $this->doc_path = $doc_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {
                        
                 return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                 ->markdown('emails.ecomms.mailBaseTemplate')
                ->subject($this->nomination->licence->trading_name.' Appointment Of Managers ')
                ->attach($this->doc_path);

            
        } catch (\Throwable $th) {
            return to_route('get_licence_transfers')->with('error','Error sending mail.');
        }
        
    }
}
