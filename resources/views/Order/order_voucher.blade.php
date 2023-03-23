@extends('master')

@section('title','Order Voucher')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.order') @lang('lang.voucher')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.order') @lang('lang.voucher') @lang('lang.page')</li>
    </ol>
</div> --}}

@endsection


@section('content')

<div class="row">
    <div class="col-md-12">

 
        <?php
            $countitem = count($voucher->counting_unit);
            echo $countitem;
            if ($countitem <= 19) {
                $z = 1;
            }
            if ($countitem > 19 && $countitem < 44) {
                $z = 2;
            }
            if ($countitem > 43 && $countitem < 68) {
                $z = 3;
            }
            if ($countitem > 67 && $countitem < 92) {
                $z = 4;
            }
            if ($countitem > 91) {
                $z = 5;
            }
        
            $i = 1;
            ?>
            @for($j=1;$j <= $z;$j++) 
            <?php
                if ($j == 1) {
                    if ($countitem < 20) {
                        ?>

<div class="card card-body printableArea">
            
            <div style="display:flex;justify-content:space-around">
                
                <div>
                    <img src="{{asset('image/Shwe_Kyar_Phyu.png')}}">
                </div>
                
                <div>
                    <h3 class="mt-1 text-center"> &nbsp;<b style="font-size: 40px;">K Win POS</b><span>(ပင်လယ်စာနှင့်အကင်အမျိုးမျိုး)</span></h3>

                    <p class="mt-2" style="font-size: 20px;" > အမှတ်-၆၆၃၊ သမိန်ဗရမ်းလမ်း၊ ၃၅ရပ်ကွက်၊ ဒဂုံမြို့သစ်မြောက်ပိုင်းမြို့နယ်၊ ရန်ကုန်မြို့။
                        <br/><i class="fas fa-mobile-alt"></i> 09-420022490 , 09-444345502 , 09-955132320 
                    </p>
                </div>
                
                <div></div>
            </div>
            
            <div class="row">
                
                <div class="col-md-12">
                    
                    <h3 class="text-info" style="font-size : 35px"><b>Bill To : </b>{{$voucher->order->customer->user->name??"" }} - {{$voucher->order->customer->phone??""}} </h3> 
                    
                    <h3 class="text-info mt-3" style="font-size : 25px"><b>@lang('lang.invoice') @lang('lang.number') :</b> {{$voucher->voucher_code}} </h3>
                    
                    <h3 class="text-info mt-2" style="font-size : 25px"><b>@lang('lang.invoice') @lang('lang.date') :</b> {{date('d-m-Y', strtotime($voucher->voucher_date))}} </h3>
                    
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">@lang('lang.number')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.item')</th>                                        
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.order_voucher_qty')</th>                                               
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.price')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.total')</th>

                        </tr>
                    </thead>
                        <tbody>
                        @php
                            $i = 1 ;
                        @endphp 

                        @foreach($voucher->counting_unit as $unit)

                     
                        <tr> 
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$i++}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$unit->item->item_name}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->quantity}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price * $unit->pivot->quantity}}</td>   
                        </tr> 
                        @endforeach
                    </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    <div class="text-right">
                        <h2 class="font-weight-bold" style="font-size : 30px"><b>@lang('lang.total') :</b> {{$voucher->total_price}} MMK</h2>
                    </div>
                </div>
            </div>
        </div>
 
                        <?php
                    } 
                    else 
                    {
                        $endpt = 19;
                    ?>
                     
  <div class="card card-body printableArea">
            
            <div style="display:flex;justify-content:space-around">
                
                <div>
                    <img src="{{asset('image/Shwe_Kyar_Phyu.png')}}">
                </div>
                
                <div>
                    <h3 class="mt-1 text-center"> &nbsp;<b style="font-size: 40px;">K Win POS</b><span></span></h3>

                    <p class="mt-2" style="font-size: 20px;" > အမှတ်-၆၆၃၊ သမိန်ဗရမ်းလမ်း၊ ၃၅ရပ်ကွက်၊ ဒဂုံမြို့သစ်မြောက်ပိုင်းမြို့နယ်၊ ရန်ကုန်မြို့။
                        <br/><i class="fas fa-mobile-alt"></i> 09-420022490 , 09-444345502 , 09-955132320 
                    </p>
                </div>
                
                <div></div>
            </div>
            
            <div class="row">
                
                <div class="col-md-12">
                    
                    <h3 class="text-info" style="font-size : 35px"><b>Bill To : </b>{{$voucher->order->customer->user->name??"" }} - {{$voucher->order->customer->phone??""}} </h3> 
                    
                    <h3 class="text-info mt-3" style="font-size : 25px"><b>@lang('lang.invoice') @lang('lang.number') :</b> {{$voucher->voucher_code}} </h3>
                    
                    <h3 class="text-info mt-2" style="font-size : 25px"><b>@lang('lang.invoice') @lang('lang.date') :</b> {{date('d-m-Y', strtotime($voucher->voucher_date))}} </h3>
                    
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                     
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">@lang('lang.number')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.item')</th>                                        
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.order_voucher_qty')</th>                                               
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.price')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.total')</th>

                        </tr>
                      
                    </thead>
                        <tbody>
                        @php
                            $i = 1 ;
                        @endphp 

                    
                        <?php foreach ($voucher->counting_unit as $key=> $unit) {
                                        if ($key == $endpt) {
                                            break;
                                        }
                                            ?>
                        <tr> 
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$i++}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$unit->item->item_name}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->quantity}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price * $unit->pivot->quantity}}</td>   
                        </tr> 
                        <?php
                        } 
                        ?>
                    </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
 
                    <?php
                   
                    }
                    }

                    // middle loop
                else if ($j > 1 && $j < $z) {
                    $startpt = $endpt;
                    $endpt = $startpt + 24;
                    if ($j == 3) {
                        $startpt = 43;
                        $endpt = 67;
                    }
                 ?>
        <div class="card card-body printableArea">        
        
            
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;">
                    <thead>
                        <tr>
                     
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">@lang('lang.number')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.item')</th>                                        
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.order_voucher_qty')</th>                                               
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.price')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.total')</th>

                        </tr>
                      
                    </thead>
                        <tbody>
                             @foreach($voucher->counting_unit as $key=> $unit)
                                        <?php
                                        if ($key >= $startpt && $key < $endpt) {
                                        ?>
                        <tr> 
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$i++}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$unit->item->item_name}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->quantity}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price * $unit->pivot->quantity}}</td>   
                        </tr> 
                        <?php
                                        }
                                        ?>
                                        @endforeach
                    </tbody>
                    </table>
                    
                </div>
            </div>
          </div>
                 <?php
                }
                 //last loop
                    else {
                            $startpt = $endpt;
                    ?>
        <div class="card card-body printableArea">
         
            
            <div class="row">
                <div class="col-md-12">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;">@lang('lang.number')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.item')</th>                                        
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.order_voucher_qty')</th>                                               
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.price')</th>
                            <th style="font-size:20px; font-weight:bold; height: 15px; border: 2px solid black;" class="text-center">@lang('lang.total')</th>

                        </tr>
                    </thead>
                        <tbody>
                     

                   
                        @foreach($voucher->counting_unit as $key=>$unit)
                                        <?php
                                        if ($key >= $startpt) 
                                        {
                                            ?>
                        <tr> 
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$i++}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;">{{$unit->item->item_name}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->quantity}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price}}</td>
                            <td style="font-size:25px; font-weight:bold; height: 8px; border: 2px solid black;" class="text-center">{{$unit->pivot->price * $unit->pivot->quantity}}</td>   
                        </tr> 

                        <?php
                                            }
                                        ?>
                        @endforeach
                    </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    <div class="text-right">
                        <h2 class="font-weight-bold" style="font-size : 30px"><b>@lang('lang.total') :</b> {{$voucher->total_price}} MMK</h2>
                    </div>
                </div>
            </div>
        </div>
                    <?php
                }
                ?>
                @endfor
 
    </div>

    <div class="col-md-12 mb-3">
        <div class="text-right">
            <button id="print" class="btn btn-outline-info" type="button"> <span><i class="fa fa-print"></i>Print</span> </button>
        </div>
    </div>
</div>

@endsection


@section('js')

<script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

<script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
    </script>


@endsection