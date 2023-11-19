<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlterationMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $alteration,$template, $doc_path;
    public function __construct($alteration,$template, $doc_path){
       $this->alteration = $alteration;
       $this->doc_path=$doc_path;
       $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        //Status keys:
        // 1. Client Quoted
        //2 => Client Invoiced
        //3 => Client Paid
        //4 => Prepare Alterations Application
        //5 => Payment to the Liquor Board
        //6 => Alterations Lodged
        //7 => Alterations Certificate Issued
        //8 => Alterations Delivered
              
        return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                    ->replyTo('info@slotow.co.za')
                    ->subject('ALTERATIONS - '.$this->alteration->licence->trading_name)
                    ->markdown('emails.ecomms.mail_base_template')
                  ->attach($this->doc_path);
               
    }

    
}
