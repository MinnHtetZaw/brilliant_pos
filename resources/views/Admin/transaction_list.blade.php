@extends('master')

@section('title','Transaction Page')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h4 class="text-themecolor m-b-0 m-t-0">@lang('lang.sale_history')</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.sale_history')</li>
    </ol>
</div>

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
                        {{$unit->item_charges}}
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Customer Phone : {{$unit->customer_phone}}

                    </div>
                    <div class="col-md-6">
                        Delivery Fee : {{$unit->delivery_charges}}</span>
                    </div>  
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        Customer Address : {{$unit->customer_address}}

                    </div>
                    <div class="col-md-6">
                        Order Status =<span class="badge badge-info font-weight-bold">{{$unit->status}}</span>
                    </div>  
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        Order Date : {{$unit->order_date}}

                    </div>
                    <div class="col-md-6">
                        Order No : {{$unit->voucher_code}}
                    </div>  
                </div>
            </div>
    </div>
    <div class="col-md-12 mt-3 offset-md-2">
        <input type="hidden" id="total_charges" value="{{$unit->total_charges}}">
        <input type="hidden" id="prepaid" value="{{$unit->prepaid_amount}}">
        <input type="hidden" id="collect" value="{{$unit->collect_amount}}">
    <div class="row">
        <div class="col-md-6">
            <div class="badge badge-primary p-4" style="width:750px;border-radius:10px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                            <span class="font-weight-bold" style="font-size:14px;">Prepaid Amount</span> = <span style="font-size:13px;">{{$unit->prepaid_amount}}</span>
                            </div>
                            <div class="col-md-4">
                            <span class="font-weight-bold" style="font-size:14px;">Last Payment Date</span> = <span style="font-size:13px;">{{$unit->order_date}}</span>
                            </div>
                            <div class="col-md-4">
                            <span class="font-weight-bold" style="font-size:14px;">Collect Amount</span> = <span style="font-size:13px;">{{$unit->collect_amount}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3" style="padding-left: 150px;">
        
            <button class="btn btn-info rounded" data-toggle="modal" data-target="#paid_vou">Pay</button>
            <!-- Begin Paid Modal -->
            <div class="modal fade"  id="paid_vou" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="border-radius:25px;" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title font-weight-bold text-white" id="exampleModalLabel">Pay Information</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('store_transaction')}}" method="post">
                        @csrf
                        <input type="hidden" name="vou_id" value="{{$unit->id}}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 ml-3">
                            <label class="font-weight-bold">Pay Date</label></br>
                            <input type="date" style="border-radius: 25px;width: 200px;height: 35px;" class="from-control border border-success pl-3" name="pay_date" id="pay_date">
                            </div>
                            <div class="col-md-4">
                            <label class="font-weight-bold">Pay Time</label></br>
                            <input type="time" style="border-radius: 25px;width: 200px;height: 35px;" class="from-control border border-success pl-3" name="pay_time" >
                            </div>
                        </div><hr>
                        <div class="from-group">
                            <label class="font-weight-bold">Pay Amount</label>
                            <input type="number" class="form-control border border-info" name="pay_amt" onkeyup="collect_cal(this.value)">
                        </div>
                        <div class="from-group">
                            <label class="font-weight-bold">Remark</label>
                            <textarea  cols="3" class="form-control border border-info" name="remark"></textarea>
                        </div>
                        <div class="from-group">
                            <label class="font-weight-bold">Bank Account</label>
                            <select  class="form-control border border-info" name="bank_info">
                                <option>Choose Bank Account</option>
                                @foreach($bank as $acc)
                                <option value="{{$acc->id}}">{{$acc->bank_name}}-{{$acc->account_number}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="from-group">
                            <label class="font-weight-bold">Collect Amount</label>
                            <input type="number" id="result" class="form-control border border-info" name="collect_amt" readonly>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Pay</button>
                    </div>
                    </div>
                    </form>
                </div>
            </div>

            <!-- End Paid Modal -->
        </div>
    </div>
    
    </div>
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
                            <td><a href="" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <!-- End table -->
        </div>
    </div>

@endsection
@section('js')
<script>
function collect_cal(value)
{
    var tot = parseInt($('#total_charges').val());
    var prepaid = parseInt($('#prepaid').val());
    prepaid += parseInt(value);
    console.log(prepaid);
    tot -= parseInt(prepaid);
    var result = tot;
    $('#result').val(tot);
}
</script>
@endsection