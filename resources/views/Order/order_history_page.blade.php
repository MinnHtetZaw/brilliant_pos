@extends('master')

@section('title','Sale History Page')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.order_voucher_history')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.sale_history') @lang('lang.page')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<section id="plan-features">

    <div class="row mb-4 justify-content-center">
        <form action="{{route('search_order_history')}}" method="POST" class="form">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <label class="control-label font-weight-bold">@lang('lang.from')</label>
                    <input type="date" name="from" class="form-control" required>
                </div>
                
                <div class="col-md-5">
                    <label class="font-weight-bold">@lang('lang.to')</label>
                    <input type="date" name="to" class="form-control" required>
                </div>

                <div class="col-md-2 m-t-30">
                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.search')">
                </div>
            </div>
        </form>
    </div>
            
    <div class="container">
        <div class="card">
            <div class="card-body shadow">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive text-black" id="slimtest2">
                            <table class="table" id="item_table">
                                <thead>
                        <tr>
                            <th class="font-weight-bold text-themecolor">#</th>
                            <th class="font-weight-bold text-themecolor">@lang('lang.voucher') @lang('lang.number')</th>
                            <th class="font-weight-bold text-themecolor">@lang('lang.voucher') @lang('lang.date')</th>
                            <th class="font-weight-bold text-themecolor">Customer</th>
                            <th class="font-weight-bold text-themecolor">@lang('lang.total') @lang('lang.quantity')</th>
                            <th class="font-weight-bold text-themecolor">@lang('lang.total') @lang('lang.price')</th>
                            <th class="font-weight-bold text-themecolor">@lang('lang.details')</th>
                        </tr>
                    </thead>
                    <tbody id="item_list">
                        <?php
                            $i = 1;
                        ?>
                       @foreach($voucher_lists as $voucher) 
                        <tr>
                            <td class="font-weight-bold">{{$i++}}</td>
                            <td class="font-weight-bold">{{$voucher->voucher_code}}</td>
                            <td class="font-weight-bold">{{$voucher->voucher_date}}</td>
                            <td class="font-weight-bold">{{isset($customer_lists[$voucher->id]) ? $customer_lists[$voucher->id] : "Customer"}}</td>
                            <td class="font-weight-bold">{{$voucher->total_quantity}}</td>
                            <td class="font-weight-bold">{{$voucher->total_price}}</td>
                            <td style="text-align: center;"><a href="{{ route('voucher_order_details',$voucher->id)}}" class="btn btn-primary" style="color: white;">@lang('lang.details')</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')

<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>

<script type="text/javascript">

	$('#item_table').DataTable( {

            "paging":   false,
            "ordering": true,
            "info":     false

    });
        
    $('#slimtest2').slimScroll({
        color: '#00f',
        height: '600px'
    });
	
</script>

@endsection