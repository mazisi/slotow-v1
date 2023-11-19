<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $transfer,$template,$doc_path;

    public function __construct($transfer,$template, $doc_path){
       $this->transfer = $transfer;
       $this->template = $template;
       $this->doc_path=$doc_path;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {           
            
            if($this->transfer->status == '1'){
                return $this->from(env("MAIL_FROM_ADDRESS"))
                ->markdown('emails.ecomms.mail_base_template')
                ->subject($this->transfer->licence->trading_name.' Transfer')
                ->attach($this->doc_path)
                //->attach(storage_path('app/public/GoVerify.pdf'))
                ->with([
                    'message_body' => $this->template
                ]);

            }else{
                return $this->from(env("MAIL_FROM_ADDRESS"), 'Leon Slotow Associates')
                ->markdown('emails.ecomms.mail_base_template')
                ->subject($this->transfer->licence->trading_name.' Transfer ')
                ->attach($this->doc_path);

            }
            
        } catch (\Throwable $th) {
             return to_route('get_licence_transfers')->with('error','Error!! Please contact support.');
        }
        
    }

  
}
