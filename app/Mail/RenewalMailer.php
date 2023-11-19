<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class renewalMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $renewal,$template, $doc_path;

    public function __construct($licence,$template, $doc_path){
       $this->renewal = $licence;
       $this->doc_path=$doc_path;
       $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {
            
        return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                    ->replyTo('info@slotow.co.za')
                    ->subject('Liquor Licence Renewal '. $this->renewal->date.' - '.strtoupper($this->renewal->licence->trading_name).' – '.strtoupper($this->renewal->licence->licence_number))
                    ->markdown('emails.ecomms.mail_base_template')
                   ->attach($this->doc_path);
                    
                } catch (\Throwable $th) {
                    return back()->with('error','Error sending mail.');
                }
    }

    
}
