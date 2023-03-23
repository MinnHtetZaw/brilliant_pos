@extends('master')

@section('title','Transaction Details')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.purchase') @lang('lang.details')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.purchase') @lang('lang.details')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal">Transaction @lang('lang.details') @lang('lang.page')</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-14">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">Transaction @lang('lang.details')</h4>
            </div>
            <div class="card-body">
           	           	
            	<div class="row">
            		<div class="col-md-6">

            			<div class="row">				           
			              	<div class="font-weight-bold text-primary col-md-5">Transaction Date</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">
			              		{{$transactionArr['tran_date']}}
			              	</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">Bank Acc</div>
			             
							  <h5 class="font-weight-bold col-md-4 mt-1">
								  {{$transactionArr['bank_acc_id']->account_holder_name}}  						-		  {{$transactionArr['bank_acc_id']->bank_name}}
							  </h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">Current Amt</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$transactionArr['bank_acc_id']->balance}}</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">Pay Amt</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$transactionArr['pay_amount']}}</h5>
				        </div> 

				

            		</div>
					{{-- <div class="col-md-5">
						<form action="{{route('purchase_delete')}}" method="POST">
							@csrf
							<input type="hidden" name="purchase_id" value="{{$purchase->id}}">
							<button class="btn btn-danger float-right">Delete</button>
						
						</form>
					</div> --}}
            		<div class="col-md-7" style="margin-left:auto;margin-right:auto;">
            			<h4 class="font-weight-bold mt-2 text-primary text-center">Transaction Detail</h4>
            			<div class="table-responsive text-black">
		                    <table class="table" id="example23" >
		                        <thead>
		                            <tr>
		                                <th>@lang('lang.index')</th>
		                                <th>Customer Name</th>
		                                <th>Order code</th>
										<th>Date</th>
										<th>Remark</th>
		                                <th>Pay Amt</th>
		                                <th>Voucher Detail</th>
		                            </tr>
		                        </thead>
		                        <tbody id="units_table">
		                            @php
                                        $i = 1 ;
                                    @endphp
									
		                            @foreach($transaction_lists as $transaction)
								
		                                <tr>
		                                    <td>{{$i++}}</td>
		                                	<td>{{$transaction->voucher->customer_name}}</td>
		                                	<td>{{$transaction->voucher->voucher_code}}</td>
		                                	<td>{{$transaction->tran_date}}</td>
		                                	<td>{{$transaction->remark}}</td>
		                                	<td>{{$transaction->pay_amount}}</td>
		                                	<td>{{$transaction->pay_amount}}</td>
											<td>

											<a href="{{route('getVoucherDetails',$transaction->voucher->id)}}" class="btn btn-success checkdetail">
											 Details
											</a>	  
										</td>

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
</div>

@endsection

@section('js')

<script>
    
    $('#units_table').on('keypress','.purchaseinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){

            var new_qty = $(this).val();
            var unit_id= $(this).data('id');
            var olderqty = $(this).data('olderqty');
            var purchase_id = $(this).data('purchaseid');
            $.ajax({

                type:'POST',

                url:'{{route('purchaseupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "new_qty": new_qty,
                    "olderqty": olderqty,
                    "unit_id":unit_id,
					"purchase_id": purchase_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Stock Changed!",
                            button:false,
                            timer:500,
                            icon:"success"  
                        });
                        $(`#purchaseinput${unit_id}`).addClass("is-valid");
						setTimeout(function(){
						window.location.reload();
						}, 1000);
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500  
                        });
                        $(`#${stockinputid}`).addClass("is-invalid");
                    }
                },
                });
        }
    })
  


</script>
@endsection