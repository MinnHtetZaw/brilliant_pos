@extends('master')

@section('title', 'Voucher Details')

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

<input type="hidden" id="from_id" value="{{$from_id}}">
    
    
    <div class="row justify-content-center pl-4 mt-5"  style="font-weight: 500">
            <div class="col-md-8 text-left font14">
                <div class="row mb-2">
                    <div class="col-md-6">
                            Customer Name : 
                         {{$unit->customer_name}}
                    </div>
                    <div class="col-md-6">
                            Total  : 
                        {{$unit->total_charges}}
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Customer Phone : {{$unit->customer_phone}}

                    </div>
                    <div class="col-md-6">
                        Prepaid amount : {{$unit->prepaid_amount}}</span>
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Customer Address : {{$unit->customer_address}}

                    </div>
                    <div class="col-md-6">
                        Delivery Fee : {{$unit->delivery_charges}}</span>
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Order Date : {{$unit->order_date}}

                    </div>
                    <div class="col-md-6">
                        Order Type : <span class="badge
                        @if ($unit->order_type =="Instock")
                            badge-info
                        @else
                            badge-danger
                        @endif
                         
                          font-weight-bold">{{$unit->order_type}}</span>
                    </div>  
       
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Order No : {{$unit->voucher_code}}
                    </div>
                    <div class="col-md-6">
                        Order Status : 
                        
                        
                        <span class="badge badge-info font-weight-bold">{{$unit->status}} 
                        @if($unit->status == 'Delivered')
                        ( {{$unit->deliveryorders[0]->date}} )
                        @endif
                        </span>
                        
                        
                        @if (session()->get('user')->role == 'Owner' && $unit->prepaid_clear_flash ==1)
                        <a href="{{route("clearFlashOverride",$unit->id)}}" class="btn btn-sm btn-warning">Override</a>
                        @endif
                 

                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-6">
                        Payment Type :
                         @if ($unit->payment_type==0)
                            COD
                            @elseif($unit->payment_type==1)
                            Prepaid Full
                            @else
                            Prepaid Partial
                        @endif
                    </div>
                    <div class="col-md-6 btn-group">
                        @if($unit->notify_flag == 'has' && $unit->status == 'Arrived')
                        <a href="{{ route('notify_change_status',$unit->id)}}" type="button" class="btn btn-sm btn-danger w-30">Notify Customer</a>
                        
                        @else
                        <button class="btn btn-dark btn-sm w-30" disabled>Notify Customer</button>
                        <!-- <button class="btn btn-secondary btn-sm w-50">Transaction</button> -->
                        @endif
                        <a href="{{route('transaction_list',$unit->id)}}" type="button" class="btn btn-sm btn-warning w-30">Transaction</a>
                         
                    </div>  
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        Page Name : {{$unit->fbpage->name}}
                    </div>
                </div>
                
            </div>
    </div>
    <div class="row justify-content-center">
        <ul class="nav nav-pills m-t-30 m-b-30 container offset-md-2">
            <li class="nav-item">
                <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">
                Item Detail
                </a>
            </li>
            <li class="nav-item">
                <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">
                Transaction List
                </a>
            </li>    
        </ul>
        <div class="col-md-8">
        <!-- Begin navpill -->
        <div class="tab-content br-n pn">
        <div id="navpills-1" class="tab-pane active">
            <div class="card card-body">
            <h4 class="">Item Detail</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive text-black" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-black">
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>SKU Code</th>
                                        <th>Qty*Price</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody class="text-black">
                                
                                    @php
                                        $j=1;
                                    @endphp
                                    @foreach ($unit->items as  $key => $countingunit)
                                    @php
                                            $price_wif_discount = $countingunit->pivot->price;
                                    @endphp
                                    <tr>
                                        <td style="font-size:15px;">{{$j++}}</td>
                                        <td style="font-size:15px;">{{ $countingunit->item_name }} ({{$countingunit->pivot->remark}})</td>
                                        <td style="font-size:15px;">{{ $countingunit->sku_code }}</td>
                                        <td style="font-size:15px;">{{ $countingunit->pivot->quantity }} * {{ $price_wif_discount }}</td>
                                        <td style="font-size:15px;" id="subtotal">{{ $countingunit->pivot->quantity *  $price_wif_discount}}</td>
                                        <td style="font-size:15px;"><span class="badge badge-info font-weight-bold">{{$countingunit->status}}</td>
                                        {{-- <td class="text-center btn-group">
                                            @if ($unit->order_type =='Instock' )
                                                <a href="{{ route('item_change_status',['voucher_id'=>$unit->id,'item_id'=>$countingunit->id,'status' =>4])}}" type="button" class="btn btn-sm btn-outline-danger">Pack</a>

                                            @elseif($unit->order_type == 'PreOrder' && $countingunit->status == 'Not Purchase')
                                            <a href="{{ route('item_change_status',['voucher_id'=>$unit->id,'item_id'=>$countingunit->id,'status' =>1])}}" type="button" class="btn btn-sm btn-outline-warning">Purchase</a>

                                            @elseif($unit->order_type == 'PreOrder' && $countingunit->status == 'Purchasing')
                                            <a href="{{ route('item_change_status',['voucher_id'=>$unit->id,'item_id'=>$countingunit->id,'status' =>2])}}" type="button" class="btn btn-sm btn-outline-info">Arrived</a>

                                            @else
                                            <a href="{{ route('item_change_status',['voucher_id'=>$unit->id,'item_id'=>$countingunit->id,'status' =>4])}}" type="button" class="btn btn-sm btn-outline-danger">Deliver</a>

                                            @endif
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end navpil -1 -->
        <div id="navpills-2" class="tab-pane">
            <div class="card mt-1">
            <div class="card-body">
                <h3>Transaction List</h3>
                <!-- Begin Table -->
                    <div class="table-responsive text-black">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction Date and Time</th>
                                    <th style="overflow:hidden;white-space: nowrap;">Bank Account</th>
                                    <th style="overflow:hidden;white-space: nowrap;">Pay Amount</th>
                                    <th style="overflow:hidden;white-space: nowrap;">Remark</th>
                                    <th style="overflow:hidden;white-space: nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;?>
                                @foreach($transaction as $tran)
                                <tr>
                                <td style="font-size:16px">{{$i++}}</td>
                                <td style="font-size:16px">{{$tran->tran_date}}-{{$tran->tran_time}}</td>
                                <td style="font-size:16px">{{$tran->bank_account->bank_name}}-{{$tran->bank_account->account_number}}</td>
                                <td style="font-size:16px">{{$tran->pay_amount}}</td>
                                <td style="font-size:16px">{{$tran->remark}}</td>
                                <td><a href="" class="btn btn-danger btn-sm w-10" style="border-radius: 25px;">Delete</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                <!-- End table -->
                </div>
            </div>
        </div><!-- end navpil -2 -->
        </div><!-- all navpill end -->
        </div><!-- all col-md-8 end -->
    </div>


    {{-- voucher print --}}
    <div id="navpills-2" class="tab-pane active">
        <div class="row justify-content-center" id="a5_voucher">
            <div class="col-md-10">

                <div class="card card-body printableArea">
                    <!--<div style="display:flex;justify-content:space-around">-->
                    <!--    <div>-->
                    <!--        <img src="{{ asset('image/brilliant_logo.png') }}">-->
                    <!--    </div>-->

                    <!--    <div>-->
                    <!--        <h3 class="mt-1 text-center"> &nbsp;<b-->
                    <!--                style="font-size: 40px;">Brilliant</b>-->
                    <!--        </h3>-->

                    <!--        <p class="mt-2" style="font-size: 20px;"> အမှတ်-၆၆၃၊-->
                    <!--            သမိန်ဗရမ်းလမ်း၊ ၃၅ရပ်ကွက်၊-->
                    <!--            ဒဂုံမြို့သစ်မြောက်ပိုင်းမြို့နယ်၊ ရန်ကုန်မြို့။-->
                    <!--            <br /><i class="fas fa-mobile-alt"></i> 09-420022490 ,-->
                    <!--            09-444345502 , 09-955132320-->
                    <!--        </p>-->
                    <!--    </div>-->

                    <!--    <div></div>-->
                    <!--</div>-->
                    <div class="row text-black">
                        <div class="col-md-6">
                            <h1 class=" mt-2 text-black font-weight-bold"
                            style="font-size : 40px; color:black">INVOICE</h1>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class=" mt-2 text-black font-weight-bold"
                                    style="font-size : 25px; color:black">
                                    {{$unit->fbpage->name}} </h5>
                                </div>
                                @php
                                    $logo = $unit->fbpage->logo;
                                @endphp
                                <div class="col-md-3">
                                    <img src="{{asset($logo)}}" width="100" height="100" alt="nes">
                                </div>
                                
                                {{-- <h3 class=" mt-2 text-black"
                                    style="font-size : 25px; color:black">
                                    {{$unit->fbpage->name}} </h3> --}}
                                
                            </div>
                        </div>
                    </div>
                    <hr style="border: 1px solid rgb(68, 62, 62);">
                    <div class="row">
                       <div class="col-md-6">
                        <h6 class=" mt-2 text-black pl-3"
                        style="font-size : 18px; color:black">INVOICE TO:
                        </h6> 
                       </div>
                       <div class="col-md-6">
                        <h6 class="float-right mt-2 text-black pl-3"
                        style="font-size : 18px; color:black">TOTAL DUE:
                        </h6> 
                       </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                         <h6 class=" mt-2 text-black pl-3"
                         style="font-size : 26px; color:black">{{$unit->customer_name}}
                         </h6> 
                        </div>
                        <div class="col-md-6">
                         <h6 class="float-right mt-2 text-black pl-3"
                         style="font-size : 18px; color:black">MMK:{{$unit->total_charges}}
                         </h6> 
                        </div>
                     </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <h3 class=" mt-2 pl-3 text-black"
                                style="font-size : 18px; color:black">Phone
                                : {{$unit->customer_phone}} </h3>
                        </div>

                        <div class="col-md-6">
                            <h3 class=" mt-2 text-black float-right" style="font-size : 18px">NO.
                                : <span id="cus_name">{{$unit->voucher_code}}</span>
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class=" mt-2 pl-3 text-black" style="font-size : 18px">Address :
                                <span id="cus_phone"> {{$unit->customer_address}}</span>
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class=" mt-2 text-black float-right" style="font-size : 18px">
                                Date : <span
                                    class="vou_code">{{$unit->order_date}}</span>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%; ">
                                <thead class="text-center">
                                    <tr>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            @lang('lang.number')</th>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            @lang('lang.item')</th>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            SKU</th>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                           Qty</th>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            @lang('lang.price')</th>
                                        <th
                                            style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">
                                            @lang('lang.total')</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php
                                    $j=1;
                                    $out_of_stock_amount = 0;
                                    $change_date = "2022-06-16";
                                    $change_time = strtotime($change_date);
                                    $status_change_date = $unit->status_change_date;
                                    $status_change_time = strtotime($status_change_date);
                                    @endphp
                                    @foreach ($unit->items as  $key => $countingunit)
                                    @php
                                            $price_wif_discount = $countingunit->pivot->price;
                                            
                                            if($countingunit->pivot->status == 5 && $status_change_time <= $change_time){
                                            $out_of_stock_amount += ($countingunit->pivot->price*$countingunit->pivot->quantity);
                                            }
                                    @endphp
                                    {{-- <tr>
                                    
                                        <td style="font-size:15px;">{{$j++}}</td>
                                        <td style="font-size:15px;">{{ $countingunit->sku_code }}</td>
                                        <td style="font-size:15px;">{{ $countingunit->pivot->quantity }} * {{ $price_wif_discount }}</td>
                                        <td class="text-right" style="font-size:15px;" id="subtotal">{{ $countingunit->pivot->quantity *  $price_wif_discount}}</td>
                                    </tr> --}}
                                    @if($countingunit->pivot->status != 5)
                                    <tr>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$j++}}</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$countingunit->item_name}} ({{$countingunit->pivot->remark}})</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$countingunit->sku_code}}</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$countingunit->pivot->quantity}}</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$price_wif_discount}}</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">{{$countingunit->pivot->quantity * $price_wif_discount}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Total</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span>{{$unit->item_charges}}</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Prepaid Amt</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span > {{$unit->prepaid_amount}} </span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Delivery Fee</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span> {{$unit->delivery_charges}} </span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">Total Amt To Collect</td>
                                        <td style="font-size:20px;height: 8px; border: 2px solid black;">
                                            <span > {{$unit->collect_amount}} </span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>








    <div class="row">
        <div class="col-md-12 mb-3 text-center">
            <button id="print" class="btn btn-success" type="button">
                <span><i class="fa fa-print"></i> Print</span>
            </button>
            <button id="edit" data-status="{{$unit->status}}" class="btn btn-warning" type="button">
                <span><i class="fa fa-edit"></i> Edit</span>
            </button>
            {{-- //TODO Payment Delete --}}
            <button id="delete" class="btn btn-danger" data-id="{{$unit->id}}" data-status="{{$unit->status}}" type="button">
                <span><i class="fa fa-trash"></i> Delete</span>
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
                $(".tab-pane.active div.printableArea").printArea(options);

            });
            $('#edit').click(function(){
                let status = $(this).data('status');
                if(status != 'Order Save'){
                    swal({
                        title: "Failed!",
                        text: `${status} Voucher ဖျက်မရပါ !`,
                        icon: "error",
                    });
                }else{
                var unit = @json($unit); //voucher
                clearLocalstorage(0);
                var totalPrice = 0;
                var totalQty = 0;

                localStorage.removeItem('voucher_details');
                localStorage.removeItem('mycart');
                localStorage.removeItem('grandTotal');
                
                var voucher_details = {
                    customer_name: unit.customer_name,
                    customer_phone: unit.customer_phone,
                    customer_address: unit.customer_address,
                    order_date: unit.order_date,
                    order_type: unit.order_type,
                    location_flag: unit.location_flag,
                    page_id: unit.page_id,
                    payment_type: unit.payment_type,
                    delivery_charges: unit.delivery_charges,
                    delivery_expense: unit.delivery_expense,
                };
                
                localStorage.setItem('voucher_details', JSON.stringify(voucher_details));
                
                $.each(unit.items,function(i,item){

                        var realPrice = item.pivot.price;
                    console.log("item",item,realPrice);
                    var local_item = {
                        id: item.id,
                        item_name: item.item_code,
                        unit_name: item.item_name,
                        remark : item.pivot.remark,
                        current_qty: item.stock,
                        order_qty: item.pivot.quantity,
                        selling_price: item.pivot.price,
                        each_sub: (parseInt(realPrice) * item.pivot.quantity),
                        discount: 0,
                    };

                    var mycart = localStorage.getItem('mycart');
              
                    if (mycart == null) {

                    mycart = '[]';

                    var mycartobj = JSON.parse(mycart);

                    mycartobj.push(local_item);

                    localStorage.setItem('mycart', JSON.stringify(mycartobj));

                    } else {

                    var mycartobj = JSON.parse(mycart);

                    mycartobj.push(local_item);

                    localStorage.setItem('mycart', JSON.stringify(mycartobj));
                    }
                    
                    totalPrice += (parseInt(realPrice) * item.pivot.quantity);
                    totalQty +=  item.pivot.quantity;
                })
                    var total_amount = {
                        sub_total: totalPrice,
                        total_qty: totalQty,
                        vou_discount: 0
                    };
                    var customer_information = {customer_name:unit.customer_name};

                    console.log("grand",total_amount);

                    var grand_total = localStorage.getItem('grandTotal');

                    localStorage.setItem('grandTotal', JSON.stringify(total_amount));

                    localStorage.setItem('customer_information', JSON.stringify(customer_information));

                    localStorage.setItem('editvoucher', JSON.stringify(unit.id));  //voucher_id
                    
                    window.location.href = "{{ route('sale_page')}}";
                }
            })
            $('#delete').click(function(){

                var voucher_id = $(this).data('id');

                let status = $(this).data('status');

                if(status != 'Order Save'){
                    swal({
                        title: "Failed!",
                        text: `${status} Voucher ဖျက်မရပါ !`,
                        icon: "error",
                    });
                }else{
                $.ajax({

                    type: 'POST',

                    url: '/voucher-delete',

                    data: {
                        "_token": "{{ csrf_token() }}",
                        "voucher_id": voucher_id,
                    },

                    success: function(data) {
                        if(data==1){
                            swal({
                                title: "Success",
                                text: "Voucher ဖျက်ပြီးပါပြီ!",
                                icon: "info",
                            });

                            setTimeout(function() {
                                window.location.href = "{{ route('sale_history')}}";
                                }, 600);
                        }else{
                            swal({
                                title: "Failed!",
                                text: "Voucher ဖျက်မရပါ !",
                                icon: "error",
                            });

                        }
                    },


                });
                }
              
            })
        });
    </script>


@endsection
