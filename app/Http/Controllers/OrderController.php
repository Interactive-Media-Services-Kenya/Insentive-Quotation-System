<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AgencyFeeCalculator;
use App\Services\TaxCalculator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
// use App\Http\Requests\StoreOrderRequest;
// use App\Http\Requests\UpdateOrderRequest;
use PDF;
use App\Mail\SendInvoice;
use Mail;
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

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Order::withCount(['orderItems']);


            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('action', 'action');
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('order_items_count', function ($row) {
                return $row->order_items_count ? $row->order_items_count : '';
            });
            $table->editColumn('company', function ($row) {
                return $row->company ? $row->company : '';
            });
            $table->editColumn('attention_to', function ($row) {
                return $row->attention_to ? $row->attention_to : '';
            });
            $table->editColumn('total_amount', function ($row) {
                return $row->total_amount ? number_format($row->total_amount) : 0;
            });

            $table->editColumn('action', function ($row) {
                $view = 1;
                $edit = 0;
                $delete = 1;
                $print = 1;
                $routePart = 'orders';

                return view('includes.datatablesActions', compact(
                    'view',
                    'edit',
                    'delete',
                    'print',
                    'routePart',
                    'row'
                ));
            });

            $table->rawColumns(['placeholder', 'id','order_items_count','company','attention_to','action']);

            return $table->make(true);
        }
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
       $subTotal = 0.00;
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
            $subTotal += $dataItem->quantity * $dataItem->prize;
        }

        //Total Amount Calculation inclusive 16% of the total for the Tax
        $order->update([
            'total_amount' => $orderFee + $this->taxCalculator->getTax($subTotal) + $subTotal,
            'sub_total' => $subTotal + $orderFee,
            'agency_fee' => $orderFee,
            'tax_amount' => $this->taxCalculator->getTax($subTotal)
        ]);

        return response()->json([
            'message'=> 'Order added successfully',
        ],200);

    }
    public function sendMail(Request $request,$orderId){

        $order = Order::whereid($orderId)->with('orderItems')->first();
        //Mail Content
        $data["email"] = $request->email;
        $data["title"] = "From Interactive Media Services (IMS)";
        $data["body"] = $request->body;
        $data["orderId"] = $orderId;

        //Send Mail
        Mail::to($data["email"])->send(new SendInvoice($data));

        return back()->with('success', 'Email Sent Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.show', compact('order'));
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
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if($order->orderItems()->count()>0){
            foreach($order->orderItems() as $orderItem){
                $orderItem->delete();
            }
        }
        $order->delete();

        return back()->with('success','Invoice Deleted Successfully');
    }

}
