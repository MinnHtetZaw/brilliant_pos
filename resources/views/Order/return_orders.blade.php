@extends('master')

@section('title','Return Orders')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h4 class="text-themecolor m-b-0 m-t-0">@lang('lang.sale_history')</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">Return Orders</li>
    </ol>
</div>

@endsection

@section('content')
<section id="plan-features">
    
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h4 class="font-weight-normal">Return Lists</h4>
        </div>
    </div>
    
    <div class="row ml-3 mt-2">
        
            <div class="row mt-2 offset-1">
            <div class="col-md-3">
                <label class="control-label font-weight-bold">@lang('lang.from')</label>
                <input type="date" name="from" id="from_Date" class="form-control" value="{{ $start_date }}" required>
            </div>
            
            <div class="col-md-3">
                <label class="control-label font-weight-bold">@lang('lang.to')</label>
                <input type="date" name="from" id="to_Date" class="form-control" value="{{ $current_Date }}" required>
            </div>
            
            <div class="col-md-2 m-t-30">
                    <button class="btn btn-info px-4" id="search_orders">Search</button>
                </div>
                
             <div class="col-md-2 m-t-30 m-l-10">
                <button class="btn btn-success px-4" id="print">Print</button>
            </div>
           
            </div>
            
        
     
    </div>
    <br/>

    <div class="container">
        <div class="card">
            <div class="card-body shadow">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive text-black" id="slimtest2">
                            <table class="table" id="item_table">
                                <thead>
                                   <tr class="text-black">
                                                <th>No.</th>
                                                <th>Order Code</th>
                                                <th>Customer Name</th>
                                                <th>Page Name</th>
                                                <th>Address</th>
                                                <th>Delivery</th>
                                                <th>Delivered Date</th>
                                                <th>Return Date</th>
                                                <th>Return Remark</th>
                                                <th>Total Amount</th>
                                                <th>Delivery Expense</th>
                                                <th>Collect Amount</th>
                                                <th>Receive Amount</th>
                                                <th class="printableAction">Detail</th>
                                            </tr>
                                </thead>
                                <tbody id="item_list">
                                    <?php
                                        $i = 1;
                                    ?>
                                    
                                   
                                        @foreach($return_vouchers as $voucher)
                                        
                                    <tr>
                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $i++ }}</td>
                                                        <td>{{$voucher->voucher_code}}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_name }}</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->fbpage->name }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_address }}</td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                                {{ $voucher->deliveryorders[0]->delivery->name }}
                                                            </td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                                {{ $voucher->deliveryorders[0]->date }}
                                                            </td>
                                                            
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->pivot->date }}
                                                            </td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->pivot->return_remark }}
                                                            </td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->total_charges }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->delivery_expense }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount - $voucher->delivery_expense }}</td>
                                                           
                                                        <td class="text-center printableAction ">
                                                            <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Details</a>
                                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                    
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
        </div>
    </div>
    
    <div class="col-md-12 printableArea d-none">
    
    
        <div style="text-align: center;">
         <img src="{{asset('image/brilliant_logo.png')}}" class="m-l-120 m-b-10" height="150px">
    </div>
         <div class="col-md-6 ml-3">
            <p class="font-weight-bold mt-2" style="font-size: 28px">Return Orders Report</p> 
        </div>
        
        <div class="col-md-6 ml-3">
            <p class="font-weight-bold mt-2" style="font-size: 20px" id="report_date">Report Date: {{$current_Date}} </p> 
        </div>
    
    
    
<div class="table-responsive text-black" id="slimtest3">
            <table class="table" id="item_table">
                <thead>
                    <tr class="text-black">
                                                <th>No.</th>
                                                
                                                <th>Customer Name</th>
                                                <th>Page Name</th>
                                                <th>Address</th>
                                                <th>Delivery</th>
                                                <th>Delivered Date</th>
                                                <th>Return Date</th>
                                                <th>Return Remark</th>
                                                <th>Total Amount</th>
                                                <th>Delivery Expense</th>
                                                <th>Collect (Receive) Amount</th>
                                                
                                                
                                            </tr>
                </thead>
                <tbody id="print_item_list">
                   <?php
                                        $i = 1;
                                    ?>
                                    
                                    
                                        @foreach($return_vouchers as $voucher)
                                        
                                    <tr>
                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $i++ }}</td>
                                                        
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_name }}</td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->fbpage->name }}</td>
                                                            
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->customer_address }}</td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->delivery->name }}</td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->date }}</td>
                                                            
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->pivot->date }}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            {{ $voucher->deliveryorders[0]->pivot->return_remark }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->total_charges }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->delivery_expense }}</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            {{ $voucher->collect_amount }} ({{ $voucher->collect_amount - $voucher->delivery_expense }})</td>
                                                        
                                                           
                                                        
                                    </tr>
                                    
                                    @endforeach
                                    
                </tbody>
            </table>
        </div>
        
        <div class="row offset-3">
            <div class="col-md-2 ml-3 " >
                <div class="col-md-2 ml-3 " >
                    <p class="mt-2" style="font-size: 20px;"><b> Total Delivery Expense :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;" id="delivery_expense"> {{ $return_order->total_delivery_expense }}</span></p> 
                </div>
                <br/>
                            
                <div class="col-md-2 ml-3 " >
                    <p class="mt-2" style="font-size: 20px;"><b> Total Collect Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;" id="collect_amt"> {{ $return_order->total_collect_amount }}</span></p> 
                </div>
                <br/>

                <div class="col-md-2 ml-3 " >
                    <p class="mt-2" style="font-size: 20px;"><b> Total Receive Amt :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;" id="receive_amt"> {{ $return_order->total_collect_amount- $return_order->total_delivery_expense }}</span></p> 
                </div>
                <br/> 

                <div class="col-md-2 ml-3 " >
                    <p class="mt-2" style="font-size: 20px;"><b>CEO Sign:</b>  </p> 
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

// 	$('#item_table').DataTable( {

//             "paging":   false,
//             "ordering": true,
//             "info":     false

//     });
        
    // $('#slimtest2').slimScroll({
    //     color: '#00f',
    //     height: '600px'
    // });
    
    $(document).ready(function() {
            $("#print").click(function() {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $('.printableArea').removeClass('d-none');
                $("div.printableArea").printArea(options);
                setInterval(() => {
                $('.printableArea').addClass('d-none');
                    
                }, 3000);

            });
        });
    
    $('#itemarrived').click(function(){
        var arr = [];
        $('input.form-check-input:checkbox:checked').each(function () {
            arr.push($(this).val());
        });

        if(!arr.length){
            swal({
                icon: 'error',
                title: 'Item ရွေးပါ!',
                text : 'Please choose items to purchse',
                button: true,
            })
        }
        else{
            // swal({
            //     title: 'Do you want to save the changes?',
            //     showDenyButton: true,
            //     confirmButtonText: 'Save',
            //     }).then((result) => {
            //     /* Read more about isConfirmed, isDenied below */
            //     if (result.isConfirmed) {
            //         Swal.fire('Saved!', '', 'success')
            //     } else if (result.isDenied) {
            //         Swal.fire('Changes are not saved', '', 'info')
            //     }
            //     })
            $('#arrived_item_ids').val(JSON.stringify(arr));
            $('#arrived_items').submit();
            // $('#itemOutofstockModal').modal('show');
        
        }
    })
    $('#itemOutofstock').click(function(){
        var arr = [];
        $('input.form-check-input:checkbox:checked').each(function () {
            arr.push($(this).val());
        });

        if(!arr.length){
            swal({
                icon: 'error',
                title: 'Item ရွေးပါ!',
                text : 'Please choose items to purchse',
                button: true,
            })
        }
        else{
            // swal({
            //     title: 'Do you want to save the changes?',
            //     showDenyButton: true,
            //     confirmButtonText: 'Save',
            //     }).then((result) => {
            //     /* Read more about isConfirmed, isDenied below */
            //     if (result.isConfirmed) {
            //         Swal.fire('Saved!', '', 'success')
            //     } else if (result.isDenied) {
            //         Swal.fire('Changes are not saved', '', 'info')
            //     }
            //     })
            $('#outofstock_item_ids').val(JSON.stringify(arr));
            $('#outofstock_items').submit();
            // $('#itemOutofstockModal').modal('show');
        
        }
    })
    $('#search_orders').click(function(){
        // let current_Date = $('#current_Date').val();
        // let fb_page = $('#fb_pages').val();
        // let order_type = $('#order_type').val();
        // let url = `/arrived-orders/${current_Date}/${fb_page}/${order_type}`;
        // window.location.href= url;
        
        
        var from = $('#from_Date').val();
        var to = $('#to_Date').val();
        $.ajax({

            type: 'POST',

            url: '{{ route('delivery_return_orders') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                "from" : from,
                "to" : to,
            },

            success: function(data) {
                if (data.length >0) {
                    var html = '';
                    var print_html = '';
                    var dindex = 0;
                    var pindex = 0;
                    var total_delivery_expense = 0;
                    var total_collect_amt = 0;
                    var total_receive_amt =0;
                    console.log(data);
                    $.each(data, function(i, voucher) {
                        
                        
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock
                           
                             var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                            html += `
                    <tr class="text-center">
                    
                                    <td class="font-weight-normal" style="font-size:15px;">
                                                            ${++dindex}</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.voucher_code }</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.customer_name }</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.fbpage.name }</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.customer_address }</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            
                                                            </td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].date }</td>
                                                            
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].pivot.date }</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].pivot.return_remark }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.total_charges }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.delivery_expense }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.collect_amount }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.collect_amount - voucher.delivery_expense }</td>
                                    
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                
                    </tr>
                    `;
                            print_html += `
                    <tr class="text-center">
                    
                                    <td class="font-weight-normal" style="font-size:15px;">
                                                            ${++pindex}</td>
                                                        
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.customer_name }</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.fbpage.name }</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.customer_address }</td>
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            </td>
                                                            
                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].date }</td>
                                                            
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].pivot.date }</td>
                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                            ${voucher.deliveryorders[0].pivot.return_remark }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.total_charges }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.delivery_expense }</td>
                                                        <td class="font-weight-normal text-right" style="font-size:15px;">
                                                            ${voucher.collect_amount }  ( ${voucher.collect_amount - voucher.delivery_expense })</td>
                                                        
                                
                    </tr>
                    `;

                        total_delivery_expense += voucher.delivery_expense;
                        total_collect_amt += voucher.collect_amount;
                        var receive_amt = voucher.collect_amount - voucher.delivery_expense;
                        total_receive_amt += receive_amt;
                        
                    })
                    
                    html+=`
                        <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Delivery Expense</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">${total_delivery_expense}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Collect Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">${total_collect_amt}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="7" class="text-right font-weight-normal" style="font-size: 15px">Total Receive Amt</td>
                                                        <td colspan="2" class="text-right font-weight-normal" style="font-size: 15px">${total_receive_amt}</td>
                                                    </tr>
                    `;
                    
                    $('#item_list').empty();
                        $('#item_list').html(html);
                       $('#print_item_list').empty();
                        $('#print_item_list').html(print_html);
                        $('#report_date').text('Report Date: '  + from + ' to ' + to );
                        $('#delivery_expense').text(total_delivery_expense);
                        $('#collect_amt').text(total_collect_amt);
                        $('#receive_amt').text(total_receive_amt);
                    
                  // $('#item_table').DataTable().clear().draw();
                    // $('#item_table').DataTable( {

                    //     "paging":   false,
                    //     "ordering": true,
                    //     "info":     false,
                    //     "destroy": true
                    // });

                    // swal({
                    //     toast:true,
                    //     position:'top-end',
                    //     title:"Success",
                    //     text:"Orders Changed!",
                    //     button:false,
                    //     timer:500,
                    //     icon:"success"  
                    // });

                } else {
                    var html = `
                    
                    <tr>
                        <td colspan="9" class="text-danger text-center">No Data Found</td>
                    </tr>

                    `;
                    $('#item_list').empty();
                    $('#item_list').html(html);
                    
                
                }
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