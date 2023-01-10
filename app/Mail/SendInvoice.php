<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class SendInvoice extends Mailable
{

    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = Order::whereid($this->data['orderId'])->with('orderItems')->first();

        $invoice_date = date('jS F Y', strtotime($order->created_at));
        $invoiceName = 'Invoice_' . config('app.name') . '_Order_No # ' . $this->data['orderId'] . ' Date_' . $invoice_date . '.pdf';
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'isHtmlParserEnabled' => true])->loadView('includes.invoice_templates.template_one', compact('order'));
        return $this->subject('Invoice From Interactive Media Services')->markdown('emails.sendInvoice')
            ->attachData($pdf->output(), 'Invoice_'.date('Y-m-d H:i:s').'.pdf')
            ->with('details', $this->data);
    }

}
