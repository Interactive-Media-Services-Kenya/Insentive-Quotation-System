<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AgencyFeeCalculator;
use App\Services\TaxCalculator;
use Illuminate\Http\Request;
// use App\Http\Requests\StoreOrderRequest;
// use App\Http\Requests\UpdateOrderRequest;
use PDF;

class OrderController extends Controller
{
    public $agencyFeeCalculator,$taxCalculator;

    public function __construct(AgencyFeeCalculator $agencyFeeCalculator, TaxCalculator $taxCalculator){
        $this->agencyFeeCalculator = $agencyFeeCalculator;
        $this->taxCalculator = $taxCalculator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $formData = $request->getContent();
       $data = json_decode($formData);
       // Create Order
        $order = Order::create([
            'company' => $data->company_info->company,
            'attention_to' => $data->company_info->attention_to,
        ]);
       //Then Save each item into the order
       $orderFee = 0.00;
        foreach ($data->postData as $dataItem) {
            OrderItem::create([
                'name'=> $dataItem->name,
                'description' => $dataItem->description,
                'quantity' => $dataItem->quantity,
                'prize' => $dataItem->prize,
                'type' => $dataItem->type,
                'order_id' => $order->id,
            ]);
            $orderFee += $this->agencyFeeCalculator->totalAmount($dataItem->quantity,($dataItem->quantity*$dataItem->prize));
        }

        //Total Amount Calculation inclusive 16% of the total for the Tax
        $order->update([
            'total_amount' => $orderFee + $this->taxCalculator->getTax($orderFee)
        ]);

        return response()->json([
            'message'=> 'Order added successfully',
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    function download_invoice($oid)
    {
        $order = Order::find($oid);

        $invoice_date = date('jS F Y', strtotime($order->invoice_date));

        $pdf = PDF::loadView('includes.invoice_template', array('order' => $order));
        return $pdf->download('Invoice_' . config('app.name') . '_Order_No # ' . $oid . ' Date_' . $invoice_date . '.pdf');
    }
}
