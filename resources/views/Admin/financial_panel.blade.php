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
            	<h4 class="card-title text-success">@lang('lang.financial') @lang('lang.list')</h4>
                <ul class="nav nav-pills m-t-30 m-b-30">
                    <li class=" nav-item"> 
                        <a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">@lang('lang.daily')</a> 
                    </li>
                    <li class="nav-item"> 
                        <a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">@lang('lang.weekly')</a> 
                    </li>
                    <li class="nav-item"> 
                        <a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="false">@lang('lang.monthly')</a> 
                    </li>
                </ul><br/>
                <div class="tab-content br-n pn">
                    <div id="navpills-1" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label class="control-label text-success font-weight-bold">From</label>
                                    <input type="date" class="form-control" id="start_daily">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label class="control-label text-success font-weight-bold">To</label>
                                    <input type="date" class="form-control" id="end_daily">
                                </div>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button class="btn btn-success btn-submit" type="submit" onclick="showDailySalev2()">	
                                	@lang('lang.search')
                                </button>
                            </div>
                        </div> 
                    </div>

                    <div id="navpills-2" class="tab-pane">
                    	<div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label text-success font-weight-bold">@lang('lang.weekly')</label>
                                    <select class="form-control custom-select" id="weekly">
                                        <option value="">@lang('lang.select_week')</option>
                                        <option value="1">@lang('lang.one_week')</option>
                                        <option value="2">@lang('lang.two_week')</option>
                                        <option value="3">@lang('lang.three_week')</option>
                                        <option value="4">@lang('lang.four_week')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button class="btn btn-success btn-submit" type="submit" onclick="showWeeklySale()">	
                                	@lang('lang.search')
                                </button>
                            </div>
                        </div>                        
                    </div>

                    <div id="navpills-3" class="tab-pane">
                    	<div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label text-success font-weight-bold">@lang('lang.monthly')</label>
                                    <select class="form-control custom-select" id="monthly">
                                        <option value="">@lang('lang.select_month')</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button class="btn btn-success btn-submit" type="submit" onclick="showMonthlySale()">	
                                	@lang('lang.search')
                                </button>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3" id="report">
        	<div class="card-body">
        		<div class="row mt-5">
        		    
        		    <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Instock Sales Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="instock_sales_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#instock_sales_amt_modal">Show</button>
	                </div>
	                
	                <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Instock COGS Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="instock_cogs_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#instock_cogs_amt_modal">Show</button>
	                </div>
	                
	                <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Gross Profit/Loss :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 font-weight-bold text-right" id="gross_profit_loss">0 </span>MMK 

	                </div>
	                
	                <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Preorder Sales Amt:
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-right" id="preorder_sales_amt">0 </span>MMK 
	                    <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#preorder_sales_amt_modal">Show</button>

	                </div>
        		    
        		    
	                <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Delivery Receive Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="delivery_received_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#delivery_received_amt_modal">Show</button>
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Bank Transaction Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-success text-right" id="transaction_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#transactions_amt_modal">Show</button>
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
	                    <h5 class="text-success col-md-4">
	                    	Purchase Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="purchse_amt">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#purchase_amt_modal">Show</button>
	                </div>
                    <div class="col-md-8 offset-2 row mb-2">
                        <h5 class="text-success col-md-4">
	                    	Expense Amt :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 text-danger text-right" id="total_expense">0 </span>MMK 

                        <button class="btn btn-success ml-5 col-md-1" data-toggle="modal" data-target="#total_expense_modal">Show</button>
	                </div>
                    <div class="col-md-8 offset-2 row">
                        <h5 class="text-success col-md-4">
	                    	Net Profit/Loss :
	                    </h5>
	                    	<span class="pl-4 pr-3 col-md-2 font-weight-bold text-right" id="profit_loss">0 </span>MMK 

	                </div>

	                <div class="col-md-12 mt-3">
	                  
	                </div>
	            </div>
        	</div>
        	
        	<div class="modal fade" role="dialog" id="instock_sales_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Instock Sales Vouchers</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Voucher Code</th>
                                            <th>Voucher Date</th>
                                            <th>Customer Name</th>
                                            <th>Page Name</th>
                                            <th>Total Qty</th>
                                            <th class="text-center">Total Sales Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="instock_sales_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" role="dialog" id="instock_cogs_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Instock COGS Vouchers</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Voucher Code</th>
                                            <th>Voucher Date</th>
                                            <th>Customer Name</th>
                                            <th>Page Name</th>
                                            <th>Total Qty</th>
                                            <th class="text-center">Total COGS Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="instock_cogs_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" role="dialog" id="preorder_sales_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Preorder Sales Vouchers</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Voucher Code</th>
                                            <th>Voucher Date</th>
                                            <th>Customer Name</th>
                                            <th>Page Name</th>
                                            <th>Total Qty</th>
                                            <th class="text-center">Total Sales Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preorder_sales_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" role="dialog" id="delivery_received_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Delivery Received Amount</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Voucher Code</th>
                                            <th>Customer Name</th>
                                            <th>Delivery Name</th>
                                            <th>Delivery Date</th>
                                            <th class="text-center">Payamount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="delivery_received_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="transactions_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Transaction Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bank Acc</th>
                                            <th>Order Code</th>
                                            <th>Page Name</th>
                                            <th>Transaction Date</th>
                                            <th>Remark</th>
                                            <th class="text-center">Payamount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="purchase_amt_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Purchase Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Supplier Name</th>
                                            <th>Purchase Date</th>
                                            <th>Total Price</th>
                                            <th>Total Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchase_amt_table">
                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="total_expense_modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Purchase Lists</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">  
                            <div class="table-responsive text-black">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Expense</th>
                                            <th>Expense Amt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="total_expense_table">
                                      
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
    // function profitLossAjax(daily,type){
    //     $.ajax({
    //       type:'POST',
    //       url:'/getTotalSaleReport',
    //       data:{   
    //         "value": daily,
    //         "type": type,
    //         "_token":"{{csrf_token()}}"
    //       },

    //       	success:function(data){

    //             swal({
    //             title:"Success",
    //             text:"Success",
    //             icon:"info",
    //             timer: 1000,
    //             showCancelButtons: false,
    //             showConfirmButtons: false
    //         });

    //             var profit_loss = (data.delivery_received['delivery_received_amt'] + data.transactions['transaction_amt'])-(data.purchases['purchase_amt'] + data.total_expense['total_expense']);

    //             $('#instock_sales_amt').text(data.total_sales['total_instock_sales']);
    //             $('#instock_cogs_amt').text(data.total_sales['total_instock_COGS']);
    //             $('#gross_profit_loss').text(data.total_sales['total_gross_profit']);
    //         	$('#delivery_received_amt').text(data.delivery_received['delivery_received_amt']);
    //         	$('#purchse_amt').text(data.purchases['purchase_amt']);
    //         	$('#transaction_amt').text(data.transactions['transaction_amt']);
    //         	$('#total_expense').text(data.total_expense['total_expense']);
    //         	$('#profit_loss').text(profit_loss);

    //             var delivery_received_amt_table = ``;
		  //      $.each(data.delivery_received['delivery_received_vouchers'],function(i,voucher){

    //                 delivery_received_amt_table += `
    //                     <tr>
    //                         <td>${++i}</td>
    //                         <td>${voucher.voucher_code}</td>    
    //                         <td>${voucher.customer_name}</td>    
    //                         <td>${voucher.delivery ? voucher.delivery.name : ""}</td>    
    //                         <td>${voucher.deliveryorders[0].date ?? ""}</td>    
    //                         <td>${voucher.collect_amount}</td>    
    //                     </tr>
    //                 `;
		  //      });
    //             $('#delivery_received_amt_table').empty();
    //             $('#delivery_received_amt_table').html(delivery_received_amt_table);

    //             var transaction_amt_table = ``;
    //             $.each(data.transactions['transaction_lists'],function(i,transaction){
    //                 transaction_amt_table += `
    //                     <tr>
    //                         <td>${++i}</td>
    //                         <td>${transaction.bank_account.account_holder_name} (${transaction.bank_account.bank_name})</td>    
    //                         <td>${ transaction.voucher ? transaction.voucher.voucher_code : ""}</td>    
    //                         <td>${transaction.voucher ? transaction.voucher.fbpage.name : ""}</td>    
    //                         <td>${transaction.tran_date}</td>    
    //                         <td>${transaction.remark ?? ""}</td>    
    //                         <td>${transaction.pay_amount}</td>    
    //                     </tr>
    //                 `;
    //                 });
    //             $('#transaction_amt_table').empty();
    //             $('#transaction_amt_table').html(transaction_amt_table);

    //             var purchase_amt_table =``;
    //             $.each(data.purchases['purchase_lists'],function(i,purchase){
    //                 purchase_amt_table += `
    //                     <tr>
    //                         <td>${++i}</td>
    //                         <td>${purchase.supplier_name}</td>    
    //                         <td>${purchase.purchase_date}</td>    
    //                         <td>${purchase.total_price}</td>    
    //                         <td>${purchase.total_quantity ?? ""}</td>    
    //                     </tr>
    //                 `;
    //                 });
    //             $('#purchase_amt_table').empty();
    //             $('#purchase_amt_table').html(purchase_amt_table);

    //             var  total_expense_table = `
    //                     <tr>
    //                         <td>1</td>
    //                         <td>Expense Indate Amt</td>    
    //                         <td>${data.total_expense['expenses_indate_amount']}</td>    
    //                     </tr>
    //                     <tr>
    //                         <td>2</td>
    //                         <td>Expense Daily Amt</td>    
    //                         <td>${data.total_expense['expenses_daily_amount']}</td>    
    //                     </tr>
    //                     <tr>
    //                         <td>3</td>
    //                         <td>Expense Weekly Amt</td>    
    //                         <td>${data.total_expense['expenses_weekly_amount']}</td>    
    //                     </tr>
    //                     <tr>
    //                         <td>4</td>
    //                         <td>Expense Montyly Amt</td>    
    //                         <td>${data.total_expense['expenses_monthly_amount']}</td>    
    //                     </tr>
    //                 `;

    //             $('#total_expense_table').empty();
    //             $('#total_expense_table').html(total_expense_table);
		  //      $('#report').show();
    //         }
    //     });
    // }
    
    function showDailySalev2() {

		$('#total_sales').empty();

		$('#total_sales').empty();

		$('#sale_table').empty();

		var  start_daily = $('#start_daily').val();
		
		var  end_daily = $('#end_daily').val();

		var  type  = 1;

        profitLossAjaxv2(start_daily,end_daily,type);
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
                console.log(data);

                var profit_loss = (data.total_sales['total_gross_profit']+ data.delivery_received['delivery_received_amt'] + data.transactions['transaction_amt'])-(data.purchases['purchase_amt'] + data.total_expense['total_expense']);
                
                $('#instock_sales_amt').text(data.total_sales['total_instock_sales']);
                $('#preorder_sales_amt').text(data.total_sales['total_preorder_sales']);
                $('#instock_cogs_amt').text(data.total_sales['total_instock_COGS']);
                $('#gross_profit_loss').text(data.total_sales['total_gross_profit']);

            	$('#delivery_received_amt').text(data.delivery_received['delivery_received_amt']);
            	$('#purchse_amt').text(data.purchases['purchase_amt']);
            	$('#transaction_amt').text(data.transactions['transaction_amt']);
            	$('#total_expense').text(data.total_expense['total_expense']);
            	$('#profit_loss').text(profit_loss);
            	
            	var instock_sales_amt_table = ``;
		        $.each(data.total_sales['instock_vouchers_final'],function(i,voucher){

                    instock_sales_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${voucher.total_charges}</td>
                        </tr>
                    `;
		        });
                $('#instock_sales_amt_table').empty();
                $('#instock_sales_amt_table').html(instock_sales_amt_table);
                
                var instock_cogs_amt_table = ``;
		        $.each(data.total_sales['instock_vouchers_final'],function(i,voucher){
		            var instock_cogs = 0;
                    $.each(voucher.items, function(j,item){
                        instock_cogs += item.purchase_price;
                    });
                    instock_cogs_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${instock_cogs}</td>
                        </tr>
                    `;
		        });
                $('#instock_cogs_amt_table').empty();
                $('#instock_cogs_amt_table').html(instock_cogs_amt_table);
                
                var preorder_sales_amt_table = ``;
		        $.each(data.total_sales['preorder_vouchers_final'],function(i,voucher){

                    preorder_sales_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${voucher.total_charges}</td>
                        </tr>
                    `;
		        });
                $('#preorder_sales_amt_table').empty();
                $('#preorder_sales_amt_table').html(preorder_sales_amt_table);

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
    
    function profitLossAjaxv2(start_daily,end_daily,type){
        $.ajax({
           type:'POST',
           url:'/getTotalSaleReportv2',
           data:{   
            "start_value": start_daily,
            "end_value": end_daily,
            "type": type,
            "_token":"{{csrf_token()}}"
           },

           	success:function(data){
                console.log(data.num_days,data);
                swal({
                title:"Success",
                text:"Success",
                icon:"info",
                timer: 1000,
                showCancelButtons: false,
                showConfirmButtons: false
            });

                var profit_loss = (data.total_sales['total_gross_profit'] + data.delivery_received['delivery_received_amt'] + data.transactions['transaction_amt'])-(data.purchases['purchase_amt'] + data.total_expense['total_expense']);
                
                $('#instock_sales_amt').text(data.total_sales['total_instock_sales']);
                $('#preorder_sales_amt').text(data.total_sales['total_preorder_sales']);
                $('#instock_cogs_amt').text(data.total_sales['total_instock_COGS']);
                $('#gross_profit_loss').text(data.total_sales['total_gross_profit']);

            	$('#delivery_received_amt').text(data.delivery_received['delivery_received_amt']);
            	$('#purchse_amt').text(data.purchases['purchase_amt']);
            	$('#transaction_amt').text(data.transactions['transaction_amt']);
            	$('#total_expense').text(data.total_expense['total_expense']);
            	$('#profit_loss').text(profit_loss);
            	
            	var instock_sales_amt_table = ``;
		        $.each(data.total_sales['instock_vouchers_final'],function(i,voucher){

                    instock_sales_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${voucher.total_charges}</td>
                        </tr>
                    `;
		        });
                $('#instock_sales_amt_table').empty();
                $('#instock_sales_amt_table').html(instock_sales_amt_table);
                
                var instock_cogs_amt_table = ``;
		        $.each(data.total_sales['instock_vouchers_final'],function(i,voucher){
		            var instock_cogs = 0;
                    $.each(voucher.items, function(j,item){
                        instock_cogs += item.purchase_price;
                    });
                    instock_cogs_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${instock_cogs}</td>
                        </tr>
                    `;
		        });
                $('#instock_cogs_amt_table').empty();
                $('#instock_cogs_amt_table').html(instock_cogs_amt_table);
                
                var preorder_sales_amt_table = ``;
		        $.each(data.total_sales['preorder_vouchers_final'],function(i,voucher){

                    preorder_sales_amt_table += `
                        <tr>
                            <td>${++i}</td>
                            <td>${voucher.voucher_code}</td>    
                            <td>${voucher.order_date}</td>    
                            <td>${voucher.customer_name}</td>    
                            <td>${voucher.fbpage ? voucher.fbpage.name : ""}</td>    
                            <td>${voucher.total_quantity}</td>
                            <td>${voucher.total_charges}</td>
                        </tr>
                    `;
		        });
                $('#preorder_sales_amt_table').empty();
                $('#preorder_sales_amt_table').html(preorder_sales_amt_table);

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
	
</script>

@endsection