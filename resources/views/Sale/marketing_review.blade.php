@extends('master')

@section('title','Financial Report')

@section('place')
{{-- 
<div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.financial')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.financial')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
            	<h4 class="card-title text-success">Marketing Staff Performance Review</h4>
                <!--<ul class="nav nav-pills m-t-30 m-b-30">-->
                <!--    <li class=" nav-item"> -->
                <!--        <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">@lang('lang.daily')</a> -->
                <!--    </li>-->
                <!--    <li class="nav-item"> -->
                <!--        <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">@lang('lang.weekly')</a> -->
                <!--    </li>-->
                <!--    <li class="nav-item"> -->
                <!--        <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="false">@lang('lang.monthly')</a> -->
                <!--    </li>-->
                <!--</ul><br/>-->
                <!--<div class="tab-content br-n pn">-->
                <!--    <div id="navpills-1" class="tab-pane active">-->
                <!--        <div class="row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group">-->
                <!--                     <label class="control-label text-success font-weight-bold">@lang('lang.daily')</label>-->
                <!--                    <input type="date" class="form-control" id="daily">-->
                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-md-3 pull-right">-->
                <!--                <button class="btn btn-success btn-submit" type="submit" onclick="showDailySale()">	-->
                <!--                	@lang('lang.search')-->
                <!--                </button>-->
                <!--            </div>-->
                <!--        </div> -->
                <!--    </div>-->

                <!--    <div id="navpills-2" class="tab-pane">-->
                <!--    	<div class="row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group">-->
                <!--                    <label class="control-label text-success font-weight-bold">@lang('lang.weekly')</label>-->
                <!--                    <select class="form-control custom-select" id="weekly">-->
                <!--                        <option value="">@lang('lang.select_week')</option>-->
                <!--                        <option value="1">@lang('lang.one_week')</option>-->
                <!--                        <option value="2">@lang('lang.two_week')</option>-->
                <!--                        <option value="3">@lang('lang.three_week')</option>-->
                <!--                        <option value="4">@lang('lang.four_week')</option>-->
                <!--                    </select>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-md-3 pull-right">-->
                <!--                <button class="btn btn-success btn-submit" type="submit" onclick="showWeeklySale()">	-->
                <!--                	@lang('lang.search')-->
                <!--                </button>-->
                <!--            </div>-->
                <!--        </div>                        -->
                <!--    </div>-->

                <!--    <div id="navpills-3" class="tab-pane">-->
                <!--    	<div class="row">-->
                <!--            <div class="col-md-12">-->
                <!--                <div class="form-group">-->
                <!--                    <label class="control-label text-success font-weight-bold">@lang('lang.monthly')</label>-->
                <!--                    <select class="form-control custom-select" id="monthly">-->
                <!--                        <option value="">@lang('lang.select_month')</option>-->
                <!--                        <option value="01">January</option>-->
                <!--                        <option value="02">February</option>-->
                <!--                        <option value="03">March</option>-->
                <!--                        <option value="04">April</option>-->
                <!--                        <option value="05">May</option>-->
                <!--                        <option value="06">June</option>-->
                <!--                        <option value="07">July</option>-->
                <!--                        <option value="08">August</option>-->
                <!--                        <option value="09">September</option>-->
                <!--                        <option value="10">October</option>-->
                <!--                        <option value="11">November</option>-->
                <!--                        <option value="12">December</option>-->
                <!--                    </select>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-md-3 pull-right">-->
                <!--                <button class="btn btn-success btn-submit" type="submit" onclick="showMonthlySale()">	-->
                <!--                	@lang('lang.search')-->
                <!--                </button>-->
                <!--            </div>-->
                <!--        </div>                        -->
                <!--    </div>-->
                <!--</div>-->
                
                <div class="row ml-4 mt-3">
            @csrf
            <div class="col-md-2">
                <label class="control-label font-weight-bold">@lang('lang.from')</label>
                <input type="date" name="from" id="from_Date" class="form-control"  value="{{ $current_Date }}" required>
            </div>
            
            <div class="col-md-2">
                <label class="control-label font-weight-bold">@lang('lang.to')</label>
                <input type="date" name="from" id="to_Date" class="form-control" value="{{ $current_Date }}"  required>
            </div>
            
            <div class="col-md-2 m-t-30">
                <select class="form-control" id="mkt_staffs" onchange="showRelatedFbPages(this.value)">
                    <option value="0">All</option>
                    @foreach ($mkt_staffs as $mkt_staff)
                            <option value="{{$mkt_staff->id}}">{{ $mkt_staff->name }}</option>
                        @endforeach
                </select>
            </div>
            
            <div class="col-md-2 m-t-30">
                <select class="form-control" id="fb_pages" >
                    <option value="0">All</option>
                   @foreach ($fb_pages as $fb_page)
                                <option value="{{$fb_page->id}}">{{ $fb_page->name }}</option>
                            @endforeach
                </select>
            </div>
            <div class="col-md-2 m-t-30">
                <select name="order_type" class="form-control" id="order_type">
                    <option value="1">Pre Order</option>
                    <option value="0">Instock Order</option>
                </select>
            </div>
        
            <div class="col-md-1 m-t-30">
                <button class="btn btn-info px-4" id="search_orders">Search</button>
            </div>
            
        </div>
                
                
            </div>
        </div>

        <div class="card mt-3" id="report">
        	<div class="card-body">
        		<div class="row mt-5">
	                <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Total Sales Item :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="totalsales_item">0 </span> 

                        
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Total Sales Amount :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="totalsales_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#totalsales_amt_modal">Show</button>
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Total Delivered Items:
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="total_delivered_items">0 </span> 

                       
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Total Delivered Amount :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="total_delivered_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#total_delivered_amt_modal">Show</button>
	                </div>
	                
	                
	                <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Total Return Items:
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="total_return_items">0 </span> 

                       
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Total Return Amount :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="total_return_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#total_return_amt_modal">Show</button>
	                </div>
	                
	                <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Total Out of Stock Items:
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="total_stockout_items">0 </span> 

                       
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Total Out of Stock Amount :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="total_stockout_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#total_stockout_amt_modal">Show</button>
	                </div>
                    
                    <div class="col-md-8 offset-2 row">
                        <h5 class="text-success col-md-3">
	                    	Net Sales Items :
	                    </h5>
	                    	<span class="pl-2 pr-2 col-md-2 font-weight-bold text-center" id="net_item">0 </span> 
	                    	
	                     <h5 class="text-success col-md-3 ml-3">
	                    	Net Sales Amount :
	                    </h5>
	                    	<span class="pl-2 pr-2 col-md-2 font-weight-bold text-center" id="net_amount">0 </span> 

	                </div>
                    
                    <div class="col-md-8 offset-2 row">
                        <h5 class="text-success col-md-3">
	                    	Success Rate :
	                    </h5>
	                    	<span class="pl-2 pr-2 col-md-2 font-weight-bold text-center" id="success_rate">0 </span>% 
	                    	
	                     <h5 class="text-success col-md-3 ml-3">
	                    	Fail Rate :
	                    </h5>
	                    	<span class="pl-2 pr-2 col-md-2 font-weight-bold text-center" id="fail_rate">0 </span>% 

	                </div>
	                
	                
	                
	               

	                <div class="col-md-12 mt-3">
	                  
	                </div>
	            </div>
        	</div>   
            
            <div class="modal fade" role="dialog" id="totalsales_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Total Received Orders List</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                         <tr class="text-center">
                                            <th>@lang('lang.number')</th>
                                            
                                            <th>@lang('lang.order') @lang('lang.number')</th>
                                            <!--<th>Page Name</th>-->
                                            <th>@lang('lang.customer') @lang('lang.name')</th>
                                            <th>@lang('lang.order') @lang('lang.date')</th>
                                            <th>@lang('lang.order') @lang('lang.status')</th>
                                            {{-- <th>@lang('lang.total') Qty</th> --}}
                                            <th>Items</th>
                                            <th>Sku Code</th>
                                            {{-- <th>Charges + Delivery Fee</th> --}}
                                            <th class="text-center">@lang('lang.details')</th>
                                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="totalsales_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="total_delivered_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delivered Order Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                       <tr class="text-center">
                                            <th>@lang('lang.number')</th>
                                            
                                            <th>@lang('lang.order') @lang('lang.number')</th>
                                            <!--<th>Page Name</th>-->
                                            <th>@lang('lang.customer') @lang('lang.name')</th>
                                            <th>@lang('lang.order') @lang('lang.date')</th>
                                            <th>Delivered Date</th>
                                            <th>@lang('lang.order') @lang('lang.status')</th>
                                            {{-- <th>@lang('lang.total') Qty</th> --}}
                                            <th>Items</th>
                                            
                                            {{-- <th>Charges + Delivery Fee</th> --}}
                                            <th class="text-center">@lang('lang.details')</th>
                                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="total_delivered_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="total_return_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Returned Order Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>@lang('lang.number')</th>
                                            
                                            <th>@lang('lang.order') @lang('lang.number')</th>
                                            <!--<th>Page Name</th>-->
                                            <th>@lang('lang.customer') @lang('lang.name')</th>
                                            <th>@lang('lang.order') @lang('lang.date')</th>
                                            <th>Delivered Date</th>
                                            <th>Returned Date</th>
                                            <th>@lang('lang.order') @lang('lang.status')</th>
                                            {{-- <th>@lang('lang.total') Qty</th> --}}
                                            <th>Items</th>
                                            <th>Return Remark</th>
                                            {{-- <th>Charges + Delivery Fee</th> --}}
                                            <th class="text-center">@lang('lang.details')</th>
                                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="total_return_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="total_stockout_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Out of Stock Order Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                       <tr class="text-center">
                                            <th>@lang('lang.number')</th>
                                            
                                            <th>@lang('lang.order') @lang('lang.number')</th>
                                            <!--<th>Page Name</th>-->
                                            <th>@lang('lang.customer') @lang('lang.name')</th>
                                            <th>@lang('lang.order') @lang('lang.date')</th>
                                            <th>@lang('lang.order') @lang('lang.status')</th>
                                            {{-- <th>@lang('lang.total') Qty</th> --}}
                                            <th>Items</th>
                                            <th>Sku Code</th>
                                            {{-- <th>Charges + Delivery Fee</th> --}}
                                            <th class="text-center">@lang('lang.details')</th>
                                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="total_stockout_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>
</div>

@endsection

@section('js')

<script>

	$(document).ready(function() {
	        
	    // $('#report').hide();           

	});

	function showDailySale() {

		$('#total_sales').empty();

		$('#total_sales').empty();

		$('#sale_table').empty();

		var  daily = $('#daily').val();

		var  type  = 1;

        profitLossAjax(daily,type);
	}
    function profitLossAjax(daily,type){
        $.ajax({
           type:'POST',
           url:'/getTotalSaleReport',
           data:{   
            "value": daily,
            "type": type,
            "_token":"{{csrf_token()}}"
           },

           	success:function(data){

                swal({
                title:"Success",
                text:"Success",
                icon:"info",
                timer: 1000,
                showCancelButtons: false,
                showConfirmButtons: false
            });

                var profit_loss = (data.delivery_received['delivery_received_amt'] + data.transactions['transaction_amt'])-(data.purchases['purchase_amt'] + data.total_expense['total_expense']);

            	$('#delivery_received_amt').text(data.delivery_received['delivery_received_amt']);
            	$('#purchse_amt').text(data.purchases['purchase_amt']);
            	$('#transaction_amt').text(data.transactions['transaction_amt']);
            	$('#total_expense').text(data.total_expense['total_expense']);
            	$('#profit_loss').text(profit_loss);

                var delivery_received_amt_table = ``;
		        $.each(data.delivery_received['delivery_received_vouchers'],function(i,voucher){

                    delivery_received_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.delivery ? voucher.delivery.name : ""}</td>    
                            <td>${voucher.deliveryorders[0].date ?? ""}</td>    
                            <td>${voucher.collect_amount}</td>    
                        </tr>
                    `;
		        });
                $('#delivery_received_amt_table').empty();
                $('#delivery_received_amt_table').html(delivery_received_amt_table);

                var transaction_amt_table = ``;
                $.each(data.transactions['transaction_lists'],function(i,transaction){
                    transaction_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${transaction.bank_account.account_holder_name} (${transaction.bank_account.bank_name})</td>    
                            <td>${ transaction.voucher ? transaction.voucher.voucher_code : ""}</td>    
                            <td>${transaction.voucher ? transaction.voucher.fbpage.name : ""}</td>    
                            <td>${transaction.tran_date}</td>    
                            <td>${transaction.remark ?? ""}</td>    
                            <td>${transaction.pay_amount}</td>    
                        </tr>
                    `;
                    });
                $('#transaction_amt_table').empty();
                $('#transaction_amt_table').html(transaction_amt_table);

                var purchase_amt_table =``;
                $.each(data.purchases['purchase_lists'],function(i,purchase){
                    purchase_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${purchase.supplier_name}</td>    
                            <td>${purchase.purchase_date}</td>    
                            <td>${purchase.total_price}</td>    
                            <td>${purchase.total_quantity ?? ""}</td>    
                        </tr>
                    `;
                    });
                $('#purchase_amt_table').empty();
                $('#purchase_amt_table').html(purchase_amt_table);

                var  total_expense_table = `
                        <tr>
                            <td>1</td>
                            <td>Expense Indate Amt</td>    
                            <td>${data.total_expense['expenses_indate_amount']}</td>    
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Expense Daily Amt</td>    
                            <td>${data.total_expense['expenses_daily_amount']}</td>    
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Expense Weekly Amt</td>    
                            <td>${data.total_expense['expenses_weekly_amount']}</td>    
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Expense Montyly Amt</td>    
                            <td>${data.total_expense['expenses_monthly_amount']}</td>    
                        </tr>
                    `;

                $('#total_expense_table').empty();
                $('#total_expense_table').html(total_expense_table);
		        $('#report').show();
            }
        });
    }
	function showWeeklySale() {

		$('#total_sales').empty();

		$('#total_sales').empty();
		
		$('#sale_table').empty();

		var  daily = $('#weekly').val();

		var  type  = 2;

        profitLossAjax(daily,type);
	}

	function showMonthlySale() {

		$('#total_sales').empty();

		$('#total_sales').empty();
		
		$('#sale_table').empty();

		var  daily = $('#monthly').val();

		var  type  = 3;

        profitLossAjax(daily,type);
		
	}
	
	$('#search_orders').click(function(){
            var order_type = $('#order_type').val();
            var fb_page = $('#fb_pages').val();
            var mkt_staff = $('#mkt_staffs').val();
            var from = $('#from_Date').val();
            var to = $('#to_Date').val();
            console.log(order_type,fb_page,from);
            $.ajax({

            type: 'POST',

            url: '{{ route('getallordersforreview') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                "order_type": order_type,
                "from" : from,
                "to" : to,
                "mkt_staff": mkt_staff,
                "fb_page" :fb_page
            },

            success: function(data) {
                
                    console.log(data);
                    var salesItemTotal = 0;
                    var salesAmountTotal = 0;
                    var totalsales_amt_table ="";
                    var total_delivered_items = 0;
                    var total_delivered_amt = 0;
                    var total_delivered_amt_table="";
                    var total_return_items = 0;
                    var total_return_amt = 0;
                    var total_return_amt_table="";
                    var total_stockout_items = 0;
                    var total_stockout_amt = 0;
                    var total_stockout_amt_table="";
                    var netSalesItems = 0;
                    var netSalesAmount = 0;
                   
                    $.each(data.voucher_lists, function(i, voucher) {
                        var salesitemhtml = '';
                        var salesskuhtml = '';
                        
                        
                        salesAmountTotal += voucher.item_charges;
                        $.each(voucher.items, function(j, item) {
                                salesItemTotal += item.pivot.quantity;
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock
                            
                                 salesitemhtml += `
                                <label style="margin-bottom: 1rem" class="form-check-label font14
                                " 
                                for="checkitem${voucher.id}${item.id}${item.pivot.quantity}" title="${item.sku_code}">
                                    ${item.item_name}-${item.pivot.remark}
                                    (${item.pivot.quantity})
                                </label>
                            
                                `;
                                salesskuhtml += `
                                    <p>${item.sku_code}</p>
                                `;
                            
                        })
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                        totalsales_amt_table += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.order_date}</td>
                                     <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                    <td>
                                    ` +
                            salesitemhtml +
                            `
                                    </td>
                                    <td>
                                        ` +
                            salesskuhtml +
                            `
                                    </td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                
                    </tr>
                    `;
                    })
                       $('#totalsales_item').text(salesItemTotal);
                       $('#totalsales_amt').text(salesAmountTotal);
                         $('#totalsales_amt_table').empty();
                $('#totalsales_amt_table').html(totalsales_amt_table);

                 
                  $.each(data.delivered_voucher_lists, function(i, voucher) {
                        var delivereditemhtml = '';
                        
                        total_delivered_amt += voucher.item_charges;
                        
                        $.each(voucher.items, function(j, item) {
                              
                                    total_delivered_items += item.pivot.quantity;
                                    delivereditemhtml += `
                                <label style="margin-bottom: 1rem" class="form-check-label font14
                                " 
                                for="checkitem${voucher.id}${item.id}${item.pivot.quantity}" title="${item.sku_code}">
                                    ${item.item_name}-${item.pivot.remark}
                                    (${item.pivot.quantity})
                                </label>
                            
                                `;
                                    
                               
                           
                        })
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                        var delivered_date = "NA";
                        if(voucher.deliveryorders.length > 0){
                            delivered_date = voucher.deliveryorders[0].date;
                        }
                        total_delivered_amt_table += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.order_date}</td>
                                    
                                    <td>${delivered_date}</td>
                                     <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                    <td>
                                    ` +
                            delivereditemhtml +
                            `
                                    </td>
                                    
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                
                    </tr>
                    `;
                    
                    })
		        
		        $('#total_delivered_amt').text(total_delivered_amt);
		        $('#total_delivered_items').text(total_delivered_items);
                $('#total_delivered_amt_table').html(total_delivered_amt_table);
                
                $.each(data.returned_voucher_lists, function(i, voucher) {
                        var returneditemhtml = '';
                        
                        total_return_amt += voucher.item_charges;
                        
                        $.each(voucher.items, function(j, item) {
                              
                                    total_return_items += item.pivot.quantity;
                                    returneditemhtml += `
                                <label style="margin-bottom: 1rem" class="form-check-label font14
                                " 
                                for="checkitem${voucher.id}${item.id}${item.pivot.quantity}" title="${item.sku_code}">
                                    ${item.item_name}-${item.pivot.remark}
                                    (${item.pivot.quantity})
                                </label>
                            
                                `;
                                    
                               
                           
                        })
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                         var delivered_date = "NA";
                         var returned_date = "NA";
                         var return_remark = '';
                        if(voucher.deliveryorders.length > 0){
                            delivered_date = voucher.deliveryorders[0].date;
                            returned_date = voucher.deliveryorders[0].pivot.date;
                            return_remark = voucher.deliveryorders[0].pivot.return_remark;
                        }
                        
                       
                        total_return_amt_table += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.order_date}</td>
                                    
                                    //<td>${delivered_date}</td>
                                    <td>${returned_date}</td>
                                     <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                    <td>
                                    ` +
                            returneditemhtml +
                            `
                                    </td>
                                    <td>${return_remark}</td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                
                    </tr>
                    `;
                    
                    })
		        
		        $('#total_return_amt').text(total_return_amt);
		        $('#total_return_items').text(total_return_items);
                $('#total_return_amt_table').html(total_return_amt_table);
                
                $.each(data.outofstock_voucher_lists, function(i, voucher) {
                        var stockoutitemhtml = '';
                        var stockoutskuhtml = '';
                        $.each(voucher.items, function(j, item) {
                              
                                    total_stockout_items += item.pivot.quantity;
                                    total_stockout_amt += item.pivot.quantity * item.pivot.price;
                                    stockoutitemhtml += `
                                <label style="margin-bottom: 1rem" class="form-check-label font14
                                " 
                                for="checkitem${voucher.id}${item.id}${item.pivot.quantity}" title="${item.sku_code}">
                                    ${item.item_name}-${item.pivot.remark}
                                    (${item.pivot.quantity})
                                </label>
                            
                                `;
                                
                                stockoutskuhtml += `
                                    <p>${item.sku_code}</p>
                                `;   
                               
                           
                        })
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                         
                        total_stockout_amt_table += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.order_date}</td>
                                     <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                    <td>
                                    ` +
                            stockoutitemhtml +
                            `
                                    </td>
                                    <td>
                                        ` +
                            stockoutskuhtml +
                            `
                                    </td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                
                    </tr>
                    `;
                    
                    })
		        
		        $('#total_stockout_amt').text(total_stockout_amt);
		        $('#total_stockout_items').text(total_stockout_items);
                $('#total_stockout_amt_table').html(total_stockout_amt_table);
               
               total_success_items = ((total_delivered_items-total_return_items)/(salesItemTotal-total_stockout_items)) * 100;
               
               $('#success_rate').text(total_success_items.toFixed(2));
               
               total_fail_items = ((total_return_items + total_stockout_items) / salesItemTotal) *100;
               
                $('#fail_rate').text(total_fail_items.toFixed(2));
                
                netSalesItems = salesItemTotal - total_stockout_items;
                netSalesAmount = salesAmountTotal - total_stockout_amt;
                $('#net_item').text(netSalesItems);
                $('#net_amount').text(netSalesAmount);
                    
            },
            });
        })
	
	function showRelatedFbPages(value) {

        console.log(value);

        $('#fb_pages').prop("disabled", false);

        var employee_id = value;

        $('#fb_pages').empty();

        $.ajax({
            type: 'POST',
            url: '/showFbPages',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                "employee_id": employee_id,
            },

            success: function(data) {

                console.log(data);
                
                $('#fb_pages').append($('<option>').text("All").attr('value', 0));

                $.each(data, function(i, value) {

                    $('#fb_pages').append($('<option>').text(value.name).attr('value', value.id));
                });

            }

        });
        }
	
</script>

@endsection