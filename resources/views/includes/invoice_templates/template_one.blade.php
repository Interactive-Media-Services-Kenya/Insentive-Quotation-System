<?php
$admin_email = config('app.admin_email');
$admin_mobile = config('app.admin_mobile');
$shop_address = config('app.shop_address');
?>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
    body {
        width: 100% !important;
        font-size: 12px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    }

    * {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
    }

    .container {
        width: 700px;
    }

    .outer_border {
        border: 1px solid #999999 !important;
        padding: 4% !important;
        margin-bottom: 2% !important;
    }

    .top_box {
        width: 50%;
        padding: 0%
    }

    .table_pad {
        padding: 0% 2%;
    }

    .border {
        border: 1px solid #CCCCCC !important;
    }

    .small_text {
        font-size: 10px !important;
    }

    .bg_color1 {
        background: #3a5082;
        color: #fff;
    }

    .text_color1 {
        color: #3a5082;
    }

    td {
        padding: 4px;
    }
</style> <?php
$InvoiceController = new \App\Http\Controllers\InvoiceController();

?><div class="container">
    <div class="outer_border">
        <div class="row">
            <div class="col-6 top_box">
                <h4 class="text_color1" style="font-size:20px">{{ config('app.name') }}</h4>
                <p>Location: {{ $shop_address }}</p>
                <p>Phone : {{ $admin_mobile }}</p>
                <p>Email : {{ $admin_email }} </p>
                <p>Website : {{ config('app.url') }} </p>
            </div>
            <div class="col-6 top_box">
                <h4 style="color:#687cbf;font-weight: bold;font-size:20px; text-align:right; padding-right: 30px;"
                    id="invoice">INVOICE</h4>
                <table width="100%" height="70" border="0" class="table_pad">
                    <tr>
                        <td> Date</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td width="50%">Invoice #</td>
                        <td width="50%">{{ $order->id ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>{{ $order->attention_to ?? '' }} -- {{ $order->company ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="">
                <table width="100%" border="0">
                    <tr>
                        <td colspan="2">
                            <div class="bg_color1"
                                style="text-indent:10px;font-size: 14px;width: 50%;height: 26px;line-height: 24px; ">
                                BILL TO </div>
                            <table width="100%" border="0">
                                <tr>
                                    <td width="18%">Name</td>
                                    <td width="82%"> Invoice From Interactive Media Services Limited </td>
                                </tr>
                                <tr>
                                    <td>Attention To: </td>
                                    <td>{{ $order->attention_to ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td> Company</td>
                                    <td> {{ $order->company ?? '' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"> </td>
                    </tr>
                </table>
            </div>
        </div>
        <dd style="clear:both;"></dd>
        <div class="row">
            <table height="100% class="table" style=" width:100%;">
                <tr class="bg_color1">
                    <td width="45%" height="12" style="padding-left: 10px;">DESCRIPTION</td>
                    <td width="10%" height="12" style="padding-left: 10px;">QUANTITY</td>
                    <td width="20%" height="12" style="padding-left: 10px;">UNIT PRIZE</td>
                    <td style="padding-right: 10px;" width="25%" align="right">AMOUNT</td>
                </tr>
                @foreach ($order->orderItems as $order_item)
                    <tr class=" ">
                        <td> {{ $order_item->name }}</td>
                        <td align="right">{{ number_format($order_item->quantity) }}</td>
                        <td align="right">KES. {{ number_format($order_item->prize) }}</td>
                        <td align="right">KES. {{ number_format($order_item->prize * $order_item->quantity) }}</td>
                    </tr>
                @endforeach
                <tr class=" ">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> <strong>Agency Fee</strong> </td>
                    <td align="right">KES. {{ number_format($order->agency_fee) }}</td>
                </tr>
                <tr class=" ">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> <strong>Sub Total</strong> </td>
                    <td align="right">KES. {{ number_format($order->sub_total) }}</td>
                </tr>
                <tr class=" ">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> <strong>VAT Tax</strong> </td>
                    <td align="right">KES. {{ number_format($order->tax_amount) }}</td>
                </tr>
                <tr class=" ">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td> <strong>Total</strong> </td>
                    <td align="right">KES. {{ number_format($order->total_amount) }}</td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div style="text-align:center"> If you have any question about this invoice, please contact <br />
                {{ config('app.name') }}, {{ $admin_mobile }}, {{ $admin_email }}<br /> <b>Thank You For Your
                    Business!</b> </div>
        </div>
    </div>
</div>
