@extends('master')

@section('title', 'Delivery Details')

@section('place')

    {{-- <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Sale Page</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">Back to Dashborad</a></li>
            <li class="breadcrumb-item active">Sale Page</li>
        </ol>
    </div> --}}

@endsection

@section('content')

    <style>
        td {

            text-align: left;
            font-size: 20px;
            font-weight: bold;
            overflow: hidden;
            white-space: nowrap;
        }

        th {
            text-align: left;
            font-size: 15px;
        }

        h6 {
            font-size: 15px;
            font-weight: 600;
        }

        .btn {
            width: 130px;
            overflow: hidden;
            white-space: nowrap;
        }

    </style>



    @php
    $from_id = session()->get('from');
    @endphp

    <input type="hidden" id="from_id" value="{{ $from_id }}">


    <div class="row justify-content-center pl-4 mt-5" style="font-weight: 500">
        <div class="col-md-8 text-left font14">
            <div class="row mb-2">
                <div class="col-md-6">
                    Delivery Name :
                    {{ $delivery_order->delivery }}
                </div>
                <div class="col-md-6">
                    Total Orders :
                    {{ $delivery_order->total_order_count }}
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><span id="header_collect_amt">
                    Total Collect Amount : {{ $delivery_order->total_collect_amount }}</span>

                </div>
                <div class="col-md-6"><span>
                    Total Delivery Expense : {{ $delivery_order->total_delivery_expense }}
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    Date : {{ $delivery_order->from }} | {{ $delivery_order->to }}
                </div>
                <div class="col-md-6"><span id="header_receive_amt">
                    Total Receive Amount : {{ $delivery_order->total_collect_amount - $delivery_order->total_delivery_expense }}
                    </span>
                </div>
            </div>
        </div>
    </div>





    <div class="row justify-content-center">
        <div class="col-md-12">
            <ul class="nav nav-pills m-t-30 m-b-30">
                <li class=" nav-item">
                    <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Orders</a>
                </li>
                <li class="nav-item">
                    <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Return Orders</a>
                </li>
            </ul><br/>
            <!-- Begin navpill -->
            <div class="tab-content br-n pn">
                <div id="navpills-1" class="tab-pane active">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive text-black " style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-black">
                                                <th>No.</th>
                                                <th>Order Code</th>
                                                <th>Customer Name</th>
                                                <th>Page Name</th>
                                                <th>Address</th>
                                                <th>Order Date</th>
                                                <th>Total Amount</th>
                                                <th>Delivery Expense</th>
                                                <th>Collect Amount</th>
                                                <th>Receive Amount</th>
                                                <th class="printableAction">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black">

                                            @php
                                                $j = 1;
                                                $total_outofstock_amount = 0;
                                            @endphp
                                            @foreach ($delivery_order_lists as $voucher)
                                                @php
                                    $out_of_stock_amount = 0;
                                    $change_date = "2022-06-16";
                                    $change_time = strtotime($change_date);
                                    $status_change_date = $voucher->status_change_date;
                                    $status_change_time = strtotime($status_change_date);
                                    @endphp
                                    @foreach ($voucher->items as  $key => $countingunit)
                                    @php
                                            if($countingunit->pivot->status == 5 && $status_change_time <= $change_time){
                                            $out_of_stock_amount += ($countingunit->pivot->price*$countingunit->pivot->quantity);
                                            }
                                            $total_outofstock_amount += $out_of_stock_amount;
                                    @endphp
                                    @endforeach
                                                    <tr>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $j++ }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->voucher_code }}</td>

                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_name }}</td>

                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->fbpage->name }}</td>

                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_address }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->order_date }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->total_charges}}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->delivery_expense }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount - $voucher->delivery_expense }}</td>
                                                        <td class="text-center printableAction ">
                                                            <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Details</a>
                                                            <button
                                                            data-deliveredorder="{{$voucher->pivot->deliveredorder_id}}" data-voucher="{{$voucher->id}}" class="btn btn-sm btn-outline-danger returnOrder">Return</button>

                                                            {{-- <form action="{{route('orderReturn')}}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="deliveredorder_id" value="{{$delivery_order->delivery->id}}">
                                                                <input type="hidden" name="voucher_id" value="{{$voucher->id}}">
                                                                      <button  class="btn btn-sm btn-outline-danger">Return</button>
                                                            </form> --}}

                                                        </td>
                                                    </tr>
                                            @endforeach
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Delivery Expense</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">{{ $delivery_order->total_delivery_expense }}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Collect Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px" id="table_collect_amt">{{ $delivery_order->total_collect_amount}}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Receive Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px" id="table_receive_amt">{{ $delivery_order->total_collect_amount- $delivery_order->total_delivery_expense}}</td>
                                                    </tr>

                                        </tbody>
                                    </table>
                                    <div class="col-md-12 offset-md-2 mb-4 printableBlock d-none">
                                        sign
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 printableArea d-none">
                        <div style="text-align: center;">
                         <img src="{{asset('image/brilliant_logo.png')}}" class="m-l-120 m-b-10" height="150px">
                        </div>
                         <div class="col-md-6 ml-3">
                            <p class="font-weight-bold mt-2" style="font-size: 28px">Delivery Report</p>
                        </div>
                        <div class="col-md-6 ml-3">
                            <p class="mt-2">Delivery Name  :     {{ $delivery_order->delivery }}</p>
                        </div>
                        <div class="col-md-6  ml-3">
                            <p class="">Date  :   {{ $delivery_order->from }} | {{$delivery_order->to}}</p>
                        </div>
                        <div class="table-responsive text-black">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>

                                        <th>Customer Name</th>
                                        <th>Page Name</th>
                                        <th>Address</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Delivery Expense</th>
                                        <th>Collect Amount</th>
                                        <th>Receive Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black">
                                    @php
                                    $j = 1;
                                @endphp
                                    @foreach ($delivery_order_lists as $voucher)
                                        <tr class="text-center">
                                            <td>{{ $j++ }}</td>


                                            <td>      {{ $voucher->customer_name }}</td>
                                            <td>      {{ $voucher->fbpage->name }}</td>
                                            <td>   {{ $voucher->customer_address }}</td>
                                            <td>{{ $voucher->order_date }}</td>
                                            <td> {{ $voucher->total_charges }}</td>
                                            <td>{{ $voucher->delivery_expense }}</td>
                                            <td> {{ $voucher->collect_amount }}</td>
                                            <td> {{ $voucher->collect_amount - $voucher->delivery_expense }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row offset-3">

                            <div class="col-md-2 ml-3 " >
                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Delivery Expense :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $delivery_order->total_delivery_expense }}</span></p>
                            </div>
                            <br/>
                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Collect Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $delivery_order->total_collect_amount}}</span></p>
                            </div>
                            <br/>

                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Receive Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $delivery_order->total_collect_amount- $delivery_order->total_delivery_expense}}</span></p>
                            </div>
                            <br/>

                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b>CEO Sign:</b>  </p>
                            </div>
                            </div>

                        </div>
                    </div>

                </div><!-- end navpil -1 -->
                <div id="navpills-2" class="tab-pane">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive text-black " style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-black">
                                                <th>No.</th>
                                                <th>Order Code</th>
                                                <th>Customer Name</th>
                                                <th>Page Name</th>
                                                <th>Address</th>
                                                <th>Return Date</th>
                                                <th>Remark</th>
                                                <th>Total Amount</th>
                                                <th>Delivery Expense</th>
                                                <th>Collect Amount</th>
                                                <th>Receive Amount</th>
                                                <th class="printableAction">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black">

                                            @php
                                                $j = 1;
                                                $total = 0;
                                            @endphp
                                            @foreach ($delivery_return_lists as $voucher)
                                                    <tr>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $j++ }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->voucher_code }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_name }}</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">      {{ $voucher->fbpage->name }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_address }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->pivot->date }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->pivot->return_remark }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->total_charges }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->delivery_expense }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount - $voucher->delivery_expense }}</td>
                                                            @php
                                                                $total += $voucher->collect_amount - $voucher->delivery_expense;
                                                            @endphp
                                                        <td class="text-center printableAction ">
                                                            <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Details</a>

                                        <button data-toggle="modal" data-target="#reDeliveryModal{{$voucher->id}}"
                                                             class="btn btn-sm btn-outline-info">ReDeliver</button>

                                        <div class="modal fade" id="reDeliveryModal{{$voucher->id}}" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header bg-success">
                                                    <h4 class="modal-title text-white">Delivery Order @lang('lang.form')</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                                </div>

                                                <div class="modal-body">
                                                    <form class="form-horizontal m-t-20">
                                                    <input type="hidden" name="delivered_order_ids" id="delivered_order_ids">
                                                    <div class="form-group row">
                                                    <label class="control-label text-right col-md-3 text-black" style="font-size:15px;">Delivery Name</label>
                                                    <div class="col-md-6">
                                                        <select class="form-control" name="delivery_id" id="delivery_id{{$voucher->id}}">
                                         @foreach ($deliveries as $delivery)
                                                            <option class="form-control" value="{{$delivery->id}}">{{$delivery->name}}</option>
                                        @endforeach
                                                        </select>
                                    {{-- <input type="text" class="form-control" id="delivery_name" name="delivery_name">  --}}
                                                    </div>
                                <!--<div class="col-md-1">-->
                                <!--    <button class="btn btn-sm btn-warning" id="add_delivery"><i class="fas fa-plus-circle"></i></button>-->
                                <!--</div>-->
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label text-right col-md-3 text-black"style="font-size:15px;">Date</label>
                                                         <div class="col-md-6">
                                                            <input type="date" class="form-control font14 text-black" id="delivery_date{{$voucher->id}}" name="delivery_date">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="control-label text-right col-md-3 text-black"style="font-size:15px;">Remark</label>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" name="remark" id="remark{{$voucher->id}}">
                                                        </div>
                                                    </div>


                                                    <button class="btn btn-sm btn-outline-info reDeliver" type="submit" data-voucherid="{{$voucher->id}}">Redeliver</button>

                                                    <!--<input type="submit" name="btnsubmit" id="delivery_sent" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')" data-voucherid="{{$voucher->id}}">-->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                                     {{--       <button data-toggle="modal" data-target="#return_items_modal{{$voucher->id}}"
                                                             class="btn btn-sm btn-outline-danger">Add to stock</button>--}}




                                                             {{-- Return Items Modal --}}
                                                             <div class="modal fade" id="return_items_modal{{$voucher->id}}" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Return Items</h4>
                                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                              </button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-1 offset-md-7">

                                                                                </div>
                                                                            {{-- @if ($voucher->status == 'Unpacked')
                                                                              <button disabled class="btn btn-success btn-sm">Order To Stock</button>
                                                                            @else
                                                                            <button id="orderToStock" data-voucherid="{{$voucher->id}}"  class="btn btn-success btn-sm">Order To Stock</button>
                                                                            @endif --}}

                                                                            </div>
                                                                            <table class="table table-hover">
                                                                                <thead>
                                                                                    <tr class="text-black">
                                                                                        <th>No.</th>
                                                                                        <th> Item Code</th>
                                                                                        <th> Sku Code</th>
                                                                                        <th>Qty</th>
                                                                                        <th>Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="text-black">
                                                                                    @php
                                                                                        $i=1;
                                                                                    @endphp
                                                                                    @foreach ($voucher->items as $item)
                                                                                    <form action="">

                                                                                        <tr>
                                                                                        <td class="font-weight-normal pt-3" style="font-size:15px;">
                                                                                            {{ $i++ }}</td>

                                                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                                                            <input name="item_code" id="item_code{{$item->id}}" type="text" class="form-control" value="{{$item->item_code }}">
                                                                                            </td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;"><input name="sku_code" id="sku_code{{$item->id}}" type="text" class="form-control" value="{{$item->sku_code }}">
                                                                                        </td>
                                                                                        <td class="font-weight-normal pt-3" style="font-size:15px;">{{ $item->pivot->quantity }}</td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                                                    @if ($item->pivot->status != 7)
                                                                                        <button class="btn btn-sm btn-outline-info itemToStock" type="submit" data-itemid="{{$item->id}}" data-quantity="{{$item->pivot->quantity}}" data-voucherid="{{$voucher->id}}">Item to stock</button>
                                                                                    @else
                                                                                    <button disabled class="btn btn-sm btn-outline-info itemToStock" data-itemid="{{$item->id}}" data-quantity="{{$item->pivot->quantity}}" data-voucherid="{{$voucher->id}}">Item to stock</button>
                                                                                    @endif
                                                                                        </tr>
                                                                                    </form>

                                                                                    @endforeach

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                        <button data-toggle="modal" data-target="#cancel_order_modal{{$voucher->id}}"
                                                             class="btn btn-sm btn-outline-danger" >Cancel Order</button>

                                        <div class="modal fade" id="cancel_order_modal{{$voucher->id}}" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white">Cancel Order @lang('lang.form')</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal m-t-20" action="{{route('canceled_orders')}}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value=2>

                            <input type="hidden" name="canceled_order_ids" id="canceled_order_id" value={{$voucher->id}}>

                            <input type="hidden" name="delivery_order_id" id="delivery_order_id" value="{{$voucher->pivot->deliveredorder_id}}">


                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black" style="font-size:15px;">Date</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control font14 text-black" id="cancel_date" name="cancelDate">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black" style="font-size:15px;">Remark</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="cancelRemark" id="cancel_remark">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black" style="font-size:15px;">Admin Code</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="adminCode" id="admin_code">
                                </div>
                            </div>

                            <div class="form-group row offset-4" >
                            <input type="submit" name="btnsubmit" id="cancel_submit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




                                                        </td>

                                                    </tr>

                                            @endforeach
                                                    <!--<tr class="">-->
                                                    <!--    <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total </td>-->
                                                    <!--    <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">{{ $total }}</td>-->
                                                    <!--</tr>-->
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Delivery Expense</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">{{ $return_order->total_delivery_expense }}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Collect Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">{{ $return_order->total_collect_amount }}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Receive Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">{{ $return_order->total_collect_amount- $return_order->total_delivery_expense }}</td>
                                                    </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 printableArea d-none">
                        <div style="text-align: center;">
                         <img src="{{asset('image/brilliant_logo.png')}}" class="m-l-120 m-b-10" height="150px">
                        </div>
                         <div class="col-md-6 ml-3">
                            <p class="font-weight-bold mt-2" style="font-size: 28px">Delivery Reurn Report</p>
                        </div>
                        <div class="col-md-6 ml-3">
                            <p class="mt-2">Delivery Name  :     {{ $delivery_order->delivery }}</p>
                        </div>
                        <div class="col-md-6  ml-3">
                            <p class="">Date  :   {{ $delivery_order->from }} | {{$delivery_order->to}}</p>
                        </div>
                        <div class="table-responsive text-black">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>

                                        <th>Customer Name</th>
                                        <th>Page Name</th>
                                        <th>Address</th>
                                        <th>Order Date</th>
                                        <th>Total Amount</th>
                                        <th>Delivery Expense</th>
                                        <th>Collect Amount</th>
                                        <th>Receive Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black">
                                    @php
                                    $j = 1;
                                @endphp
                                    @foreach ($delivery_return_lists as $voucher)
                                        <tr class="text-center">
                                            <td>{{ $j++ }}</td>

                                            <td>      {{ $voucher->customer_name }}</td>
                                            <td>      {{ $voucher->fbpage->name }}</td>
                                            <td>   {{ $voucher->customer_address }}</td>
                                            <td>{{ $voucher->order_date }}</td>
                                            <td> {{ $voucher->total_charges }}</td>
                                            <td>{{ $voucher->delivery_expense }}</td>
                                            <td> {{ $voucher->collect_amount }}</td>
                                            <td> {{ $voucher->collect_amount - $voucher->delivery_expense }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row offset-3">

                            <div class="col-md-2 ml-3 " >
                                <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Delivery Expense :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $return_order->total_delivery_expense }}</span></p>
                            </div>
                            <br/>
                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Collect Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $return_order->total_collect_amount }}</span></p>
                            </div>
                            <br/>

                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b> Total Receive Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"> {{ $return_order->total_collect_amount- $return_order->total_delivery_expense }}</span></p>
                            </div>
                            <br/>

                            <div class="col-md-2 ml-3 " >
                                <p class="mt-2" style="font-size: 20px;"><b>CEO Sign:</b>  </p>
                            </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!-- all navpill end -->
        </div><!-- all col-md-8 end -->
    </div>

    <div class="modal fade" id="return_order_modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Return Order</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal">
                        <input type="hidden" id="deliveryOrderID">
                        <input type="hidden"  id="voucherID">
                        <div class="form-group row">
                            <label class="control-label text-black  col-md-6">Date</label>
                            <div class="col-md-6">
                                <input type="date" class="form-control" id="return_date">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label text-black  col-md-6">Remark</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="return_remark">

                            </div>
                        </div>

                        <input type="button" id="save_return_order" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12 mb-3 text-center">
            <button id="print" class="btn btn-success" type="button">
                <span><i class="fa fa-print"></i> Print</span>
            </button>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('js/jquery.PrintArea.js') }}" type="text/JavaScript"></script>

    <script>
        $(document).ready(function() {



            $("#print").click(function() {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $('.printableArea').removeClass('d-none');
                $('.printableArea').addClass('d-block');
                $(".tab-pane.active div.printableArea").printArea(options);
                setInterval(() => {
                $('.tab-pane.active div.printableArea').addClass('d-none');
                }, 3000);

                //$('#header_collect_amt').text($('#table_collect_amt').val());

            });


            $('.returnOrder').click(function(e){

                e.preventDefault();

                var deliveredorder_id = $(this).data('deliveredorder');
                var voucher_id = $(this).data('voucher');
                $('#deliveryOrderID').val(deliveredorder_id);
                $("#voucherID").val(voucher_id);
                $("#return_order_modal").modal('show');

            })
            $('#save_return_order').click(function(e){

                e.preventDefault();

                var deliveredorder_id = $('#deliveryOrderID').val();
                var voucher_id = $('#voucherID').val();
                var return_date = $("#return_date").val();
                var return_remark = $("#return_remark").val();
                console.log(deliveredorder_id,voucher_id,return_date,return_remark);
                if(deliveredorder_id && voucher_id && return_date && return_remark){

                $.ajax({

                    type:'POST',

                    url:'/orders/return',

                    data:{
                    "_token":"{{csrf_token()}}",
                    "deliveredorder_id":deliveredorder_id,
                    "voucher_id":voucher_id,
                    "return_date" : return_date,
                    "return_remark" : return_remark
                    },

                    success:function(data){
                        swal({
                            title: "Success!",
                            text : "Successfully return the order!",
                            icon : "info",
                            });
                        setTimeout(() => {
                            history.go(0);
                            }, 800);
                        }
                });
                           swal({
                            title: "Success!",
                            text : "Successfully return the order!",
                            icon : "info",
                            });
                            setTimeout(() => {
                            history.go(0);
                            }, 800);
                }else{

                    $("#return_order_modal").modal('hide');
                    swal({
                    title: "Error!",
                    text : "Please Fill all fields!",
                    icon : "error",
                    });
                }


                })

                $('.itemToStock').click(function(e){
                    e.preventDefault();
                    let item_id = $(this).data('itemid');
                    let quantity = $(this).data('quantity');
                    let voucher_id = $(this).data('voucherid');
                    let item_code_id = `#item_code${item_id}`;
                    let item_code = $(item_code_id).val();
                    let sku_code_id = `#sku_code${item_id}`;
                    let sku_code = $(sku_code_id).val();
                    $.ajax({

                        type:'GET',

                        url:`/itemToStock/${item_id}/${quantity}/${voucher_id}/${item_code}/${sku_code}`,

                        success:function(data){
                            swal({
                                title: "Success!",
                                text : "Successfully Add To Stock!",
                                icon : "info",
                            });
                            setTimeout(() => {
                                history.go(0);
                            }, 500);

                        }
                    });
                })

                $('#orderToStock').click(function(e){
                    e.preventDefault();
                    let voucher_id = $(this).data('voucherid');
                    $.ajax({

                        type:'GET',

                        url:`/orderToStock/${voucher_id}`,

                        data:{
                        "_token":"{{csrf_token()}}",
                        "voucher_id":voucher_id,
                        },

                        success:function(data){

                            swal({
                                title: "Success!",
                                text : "Successfully Add To Stock!",
                                icon : "info",
                            });
                            setTimeout(() => {
                                history.go(0);
                            }, 800);

                        }
                    });
                })

                $('.reDeliver').click(function(e){
        e.preventDefault();
        let order_id = $(this).data('voucherid');
        let reDeliveryModalName = `#reDeliveryModal${order_id}`;
        let deliverIdName = `#delivery_id${order_id}`;
        let deliverIdValue = $(deliverIdName).val();
        let deliverDateName = `#delivery_date${order_id}`;
        let deliverDateValue = $(deliverDateName).val();
        let remarkName = `#remark${order_id}`;
        let remarkValue = $(remarkName).val();
        if($.trim(order_id) == '' || $.trim(deliverIdValue) =='' || $.trim(deliverDateValue) == '' || $.trim(remarkValue) =='')
        {
            swal({
                title:"Failed!",
                text:"Please fill all basic unit field",
                icon:"error",
                timer: 3000,
            });

        }
        else{
           // $(orderDeliveryModalName).modal('hide');
            console.log(order_id,deliverIdValue,deliverDateValue,remarkValue);
            $.ajax({

                type:'POST',

                url:'/redeliver-order',

                data:{
                "_token":"{{csrf_token()}}",
                "delivered_order_id":order_id,
                "deliverId":deliverIdValue,
                "deliverDate":deliverDateValue,
                "remark":remarkValue,
                },

                success:function(data){

                    $(reDeliveryModalName).modal('hide');

                    // $('#item_deliver').addClass('d-none');
                    if(data.status ==0){
                        swal({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text : data.message,
                        button: true,
                    })
                    }else{

                        swal({
                            icon: 'success',
                            title: 'Success!',
                            text : data.message,
                            button: true,
                        })

                    }

                }

            });
        }

        })

            });

            //$('#cancel_submit').click(function(e){
        //e.preventDefault();
        function cancelOrder(){
        var canceled_order_ids = $('#canceled_order_id').val();
        var delivery_order_id = $('#delivery_order_id').val();
        var cancelDate = $('#cancel_date').val();
        var cancelRemark = $('#cancel_remark').val();
        var adminCode = $('#admin_code').val();
        console.log(canceled_order_ids);
        if($.trim(canceled_order_ids) == '' || $.trim(cancelDate) == '' || $.trim(cancelRemark) =='' || $.trim(adminCode) =='')
        {
            swal({
                title:"Failed!",
                text:"Please fill all basic unit field",
                icon:"error",
                timer: 3000,
            });

        }
        else{
            console.log(canceled_order_ids,delivery_order_id,cancelDate,cancelRemark,adminCode);
            $.ajax({

                type:'POST',

                url:'/cancel-orders',

                data:{
                "_token":"{{csrf_token()}}",
                "canceled_order_ids":canceled_order_ids,
                "cancelDate":cancelDate,
                "cancelRemark":cancelRemark,
                "type" : 2,
                "delivery_order_id": delivery_order_id,
                "adminCode":adminCode,
                },

                success:function(data){
                    $('#canceled_order_id').val(null);
                    $('#cancel_date').val(null);
                    $('#cancel_remark').val(null);
                    $('#admin_code').val(null);
                    $('#orderCancelModal').modal('hide');

                    // $('#item_deliver').addClass('d-none');
                    if(data.status ==1){
                        swal({
                        icon: 'error',
                        title: 'Validation error, required fields!',
                        text : 'Please check again',
                        button: true,
                    })
                    }else if(data.status ==2){
                        swal({
                        icon: 'error',
                        title: 'Admin Code is not correct!',
                        text : 'Please check again',
                        button: true,
                    })
                    }else if(data.status ==3){
                        swal({
                        icon: 'error',
                        title: 'Database query error!',
                        text : 'Please check again',
                        button: true,
                    })
                    }
                    else{

                        swal({
                            icon: 'success',
                            title: 'Success!',
                            text : 'Successfully cancel the orders',
                            button: true,
                        })

                        // setTimeout(() => {
                        //     history.go(0);
                        //     }, 300);
                        // }

                }

                }

                });
         }
   }
    //});


        function ApproveLeave($deliverorder_id,$voucher_id){
                console.log($deliverorder_id,$voucher_id);
            }

            // function windowReload(e){
            //     e.preventDefault;
            //     console.log('reloading');
            //     history.go(0);
            // }
    </script>


@endsection
