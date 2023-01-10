<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    //
    public function downloadInvoice($order_id)
    {
        $order = Order::whereid($order_id)->with('orderItems')->first();

        $invoice_date = date('jS F Y', strtotime($order->created_at));

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true, 'isHtmlParserEnabled' => true])->loadView('includes.invoice_templates.template_one', compact('order'));
        return $pdf->download('Invoice_' . config('app.name') . '_Order_No # ' . $order_id . ' Date_' . $invoice_date . '.pdf');
    }
}
