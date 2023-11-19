<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewAppsMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $licence,$template, $doc_path;
    public function __construct($licence,$template, $doc_path){
       $this->licence = $licence;
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
                    ->subject('NEW APPLICATION - '.$this->licence->trading_name)
                    ->markdown('emails.ecomms.mail_base_template')
                    ->attach($this->doc_path);
               
    }

    
}
