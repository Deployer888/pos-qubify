<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * InvoiceSend
 */
class InvoiceReturnStatusSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $status;

    /**
     * __construct
     *
     * @param  mixed $data
     * @return void
     */
    public function __construct($data, $status)
    {
        $this->data = $data;
        $this->status = $status;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Request Invoice ' . $this->status)->view('emails.invoices.invoice_return_status');
    }
}
