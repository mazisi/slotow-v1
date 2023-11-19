<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TemporalLicenceMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $temp_licence,$template, $doc_path;

    public function __construct($temp_licence,$template, $doc_path){
       $this->temp_licence = $temp_licence;
       $this->doc_path=$doc_path;
       $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
                      
        return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                    ->replyTo('info@slotow.co.za')
                    ->subject('TEMPORARY LICENCE - '.$this->temp_licence->event_name)
                    ->markdown('emails.ecomms.mail_base_template')
                    ->attach($this->doc_path);
               
    }

    
}
