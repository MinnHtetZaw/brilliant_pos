@extends('master')

@section('title','Purchase Details')

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
        <h4 class="font-weight-normal">@lang('lang.purchase') @lang('lang.details') @lang('lang.page')</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-14">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="font-weight-bold mt-2">@lang('lang.purchase') @lang('lang.details')</h4>
            </div>
            <div class="card-body">
           	           	
            	<div class="row">
            		<div class="col-md-6">

            			<div class="row">				           
			              	<div class="font-weight-bold text-primary col-md-5">@lang('lang.purchase_date')</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">
			              		{{date('d-m-Y', strtotime($purchase->purchase_date))}}
			              	</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">@lang('lang.total') @lang('lang.price')</div>
			              	@php
			              	    $backup_total = 0;
			              	    $sub_total = 0;
			              	@endphp
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{($purchase->total_price==0)? $backup_total : $purchase->total_price}} ကျပ်</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">@lang('lang.total') @lang('lang.quantity')</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->total_quantity}}</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">@lang('lang.supplier_name')</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->supplier_name}}</h5>
				        </div> 

				        <div class="row mt-1">				           
			              	<div class="font-weight-bold text-primary col-md-5">@lang('lang.purchase_by')</div>
			              	<h5 class="font-weight-bold col-md-4 mt-1">{{$purchase->user->name}}</h5>
				        </div>
				

            		</div>
					<div class="col-md-5">
						<form action="{{route('purchase_delete')}}" method="POST">
							@csrf
							<input type="hidden" name="purchase_id" value="{{$purchase->id}}">
							<button class="btn btn-danger float-right">Delete</button>
						
						</form>
					</div>
            		<div class="col-md-7" style="margin-left:auto;margin-right:auto;">
            			<h4 class="font-weight-bold mt-2 text-primary text-center">@lang('lang.purchase_unit')</h4>
            			<div class="table-responsive text-black">
		                    <table class="table" id="example23" >
		                        <thead>
		                            <tr>
		                                <th>@lang('lang.index')</th>
		                                <th>@lang('lang.item') @lang('lang.name')</th>
										<th>Item Code</th>
										<th>SKU</th>
		                                <th>@lang('lang.purchase_qty')</th>
		                            </tr>
		                        </thead>
		                        <tbody id="units_table">
		                            @php
                                        $i = 1 ;
                                    @endphp
									
		                            @foreach($purchase_items as $unit)
								
		                                <tr>
		                                    <td>{{$i++}}</td>
		                                	<td>{{$unit->item_name}}</td>
		                                	<td>{{$unit->item_code}}</td>
		                                	<td>{{$unit->sku_code}}</td>
											<td class="w-100">
												{{$unit->purchase_stock}}
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