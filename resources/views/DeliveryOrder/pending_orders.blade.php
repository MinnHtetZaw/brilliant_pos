@extends('master')

@section('title','Pending Orders')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h4 class="text-themecolor m-b-0 m-t-0">@lang('lang.sale_history')</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">Arrived Orders</li>
    </ol>
</div>

@endsection

@section('content')
<section id="plan-features">

    <div class="row ml-4 mt-3">

            <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 for="font-weight-bold">Date Type</h5>
                            </div>
                            @if ($radio == 0)
                            <div class="col-md-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="0" onclick="chkdatatype(0)" checked>
                                    <label class="form-check-label" for="inlineRadio1">Order Date</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1" onclick="chkdatatype(1)">
                                    <label class="form-check-label" for="inlineRadio2">Status Change Date</label>
                                  </div>
                            </div>
                            @endif
                            @if ($radio == 1)
                            <div class="col-md-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="0" onclick="chkdatatype(0)">
                                    <label class="form-check-label" for="inlineRadio1">Order Date</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1" onclick="chkdatatype(1)" checked>
                                    <label class="form-check-label" for="inlineRadio2">Status Change Date</label>
                                  </div>
                            </div>
                            @endif

                        </div>


                    </div>


            <form action="{{route('get_orders')}}" method="POST">
                @csrf
                <input type="hidden" id="data_types" name="data_type" value="{{$radio}}">
            <div class="row mt-2">
                <div class="col-md-2">
                    <label class="control-label font-weight-bold">@lang('lang.from')</label>
                    <input type="date" name="from" id="from_Date" class="form-control" value="{{ $start_date }}" required>
                </div>

                <div class="col-md-2">
                    <label class="control-label font-weight-bold">@lang('lang.to')</label>
                    <input type="date" name="to" id="to_Date" class="form-control" value="{{ $current_Date }}" required>
                </div>

                <div class="col-md-2 m-t-30">
                    <select class="form-control" name="mk_staff" id="mkt_staffs">
                        <option value="0">All</option>
                        @foreach ($mkt_staffs as $mkt_staff)
                            <option value="{{$mkt_staff->id}}">{{ $mkt_staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                    <div class="col-md-2 m-t-30">
                        <select class="form-control select" name="fb_page" id="fb_pages">
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
                <select class="form-control col-md-2 m-t-30" id="pendingOrders">
                    <option value="1">Pending</option>
                    <option value="2">Payment-complete Orders</option>
                    <option value="3">Payment-incomplete Orders</option>
                    <option value="4">Orders(All)</option>
                </select>
                <div class="col-md-1 m-t-30">
                    <button type="submit" class="btn btn-info px-4" id="search_orders">Search</button>
                </div>
                <div class="col-md-1 m-t-30">
                    <button class="btn btn-success ml-4 d-none" id="item_deliver">Deliver</button>
                </div>

                <div class="col-md-1 m-t-30">
                    <button class="btn btn-danger ml-4 d-none" id="item_cancel">Cancel</button>
                </div>
            </div>
          </form>




        {{-- arrived form hidden --}}

        <div class="modal fade" id="orderDeliveryModal" role="dialog" aria-hidden="true">
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
                                <label class="control-label text-right col-md-3 text-black">Delivery Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="delivery_id" id="delivery_id">
                                        @foreach ($deliveries as $delivery)
                                        <option class="form-control" value="{{$delivery->id}}">{{$delivery->name}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" id="delivery_name" name="delivery_name">  --}}
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-sm btn-warning" id="add_delivery"><i class="fas fa-plus-circle"></i></button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Date</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control font14 text-black" id="delivery_date" name="delivery_date" @if (session()->get('user')->role != 'Owner')disabled @endif>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Remark</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="remark" id="remark">
                                </div>
                            </div>


                            <input type="submit" name="btnsubmit" id="delivery_sent" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="add_delivery_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title text-white">Add Delivery @lang('lang.form')</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal m-t-20">
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Delivery Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="delivery_name" id="delivery_name">
                                </div>
                            </div>


                            <input type="submit" name="btnsubmit" id="add_delivery_sent" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="orderCancelModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title text-white">Cancel Order @lang('lang.form')</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal m-t-20">
                            <input type="hidden" name="canceled_order_ids" id="canceled_order_ids">


                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Date</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control font14 text-black" id="cancel_date" name="cancel_date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Remark</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="cancel_remark" id="cancel_remark">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Admin Code</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="admin_code" id="admin_code">
                                </div>
                            </div>


                            <input type="submit" name="btnsubmit" id="cancel_submit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                        </form>
                    </div>
                </div>
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
                            <table class="table"  id="item_table">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.number')</th>
                                        <th>#</th>
                                        <th>@lang('lang.order') @lang('lang.number')</th>
                                        <th>@lang('lang.customer') @lang('lang.name')</th>
                                        <th>Page Name</th>
                                        <th>@lang('lang.order') @lang('lang.date')</th>
                                        <th>@lang('lang.order') @lang('lang.status')</th>
                                        {{-- <th>@lang('lang.total') Qty</th> --}}
                                        <th>Items</th>
                                        {{-- <th>Charges + Delivery Fee</th> --}}
                                        <th class="text-center">@lang('lang.details')</th>
                                        {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="order_lists">
                                    <?php
                                        $i = 1;
                                    ?>
                                    @foreach($voucher_lists as $voucher)

                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>

                                        </td>
                                        <td>{{$voucher->voucher_code}}</td>
                                        <td>{{$voucher->customer_name}}</td>
                                        <td>{{$voucher->fbpage->name}}</td>
                                        <td>{{$voucher->order_date}}</td>
                                        <td><span class="badge badge-info font-weight-bold">{{$voucher->status}}</span></td>
                                        <td>
                                                @forelse ($voucher->items as $item)
                                                        <p class="form-check-label mb-2 font14
                                                            @if ($item->pivot->status==2)      {{--arrived --}}
                                                                text-success
                                                            @elseif($item->pivot->status == 5)    {{--out of stock --}}
                                                                text-danger
                                                            @endif
                                                        ">
                                                        {{$item->item_name}}
                                                        ({{$item->pivot->remark}})
                                                        ({{$item->pivot->quantity}})
                                                        </p>
                                                @empty

                                                @endforelse
                                            </td>
                                            <td class="text-center"><a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Details</a>
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
</section>

@endsection

@section('js')

<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>

{{-- <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script> --}}

<script type="text/javascript">

	 $('#item_table').DataTable( {

            "paging":   false,
            "ordering": true,
            "info":     false,
            "destroy": true,

    });

    $(".select").select2({
                placeholder: "ရှာရန်",
        });

    // $('#slimtest2').slimScroll({
    //     color: '#00f',
    //     height: '600px'
    // });

    $("#delivery_date").datepicker({
                    format:'yyyy-mm-dd',
                }).datepicker("setDate",'now');

    $("#cancel_date").datepicker({
                    format:'yyyy-mm-dd',
                }).datepicker("setDate",'now');

    $('#item_deliver').click(function(){
        var arr = [];
        $('#order_lists input.form-check-input:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        if(!arr.length){
            swal({
                icon: 'error',
                title: 'Order ရွေးပါ!',
                text : 'Please choose order to deliver',
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
            $('#delivered_order_ids').val(JSON.stringify(arr));
            $('#orderDeliveryModal').modal('show');

            // $('#delivered_orders').submit();

        }
    })

    $('#item_cancel').click(function(){
        var arr = [];
        $('#order_lists input.form-check-input:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        if(!arr.length){
            swal({
                icon: 'error',
                title: 'Order ရွေးပါ!',
                text : 'Please choose order to deliver',
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
            $('#canceled_order_ids').val(JSON.stringify(arr));
            console.log(arr);
            $('#orderCancelModal').modal('show');

            // $('#delivered_orders').submit();

        }
    })



    $('#delivery_sent').click(function(e){
        e.preventDefault();
        var delivered_order_ids = $('#delivered_order_ids').val();
        var deliverId = $('#delivery_id').val();
        var deliverDate = $('#delivery_date').val();
        var remark = $('#remark').val();
        if($.trim(delivered_order_ids) == '' || $.trim(deliverId) =='' || $.trim(deliverDate) == '' || $.trim(remark) =='')
        {
            swal({
                title:"Failed!",
                text:"Please fill all basic unit field",
                icon:"error",
                timer: 3000,
            });

        }
        else{
            $.ajax({

                type:'POST',

                url:'/delivere-orders',

                data:{
                "_token":"{{csrf_token()}}",
                "delivered_order_ids":delivered_order_ids,
                "deliverId":deliverId,
                "deliverDate":deliverDate,
                "remark":remark,
                },

                success:function(data){
                    $('#delivered_order_ids').val(null);
                    $('#delivery_name').val(null);
                    $('#delivery_date').val(null);
                    $('#remark').val(null);
                    $('#orderDeliveryModal').modal('hide');

                    // $('#item_deliver').addClass('d-none');
                    if(data.status ==0){
                        swal({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text : 'Please check again',
                        button: true,
                    })
                    }else{

                        swal({
                            icon: 'success',
                            title: 'Success!',
                            text : 'Successfully add to delivery orders',
                            button: true,
                        })

                    var html = "";

                    $.each(data.voucher_lists,function(i,voucher)
                    {
                        var complete_order_checkbox = '';
                        if(data.order_type==2){
                            complete_order_checkbox += `
                            <input class="form-check-input" type="checkbox" value="${voucher.id}" id="checkitem${voucher.id}">
                            <label class="form-check-label font14" for="checkitem${voucher.id}"></label>
                            `;
                        }
                        var incomplete_order_checkbox = '';
                        if(data.order_type==3){
                            incomplete_order_checkbox += `
                            <a href="" class="btn btn-sm btn-outline-info">Transaction</a>
                            `;
                        }

                        var itemhtml = ``;
                        $.each(voucher.items,function(j,item){
                            if(item.pivot.status ==2){
                                var textcolor = 'text-success';
                            }else if(item.pivot.status == 5){
                                var textcolor = 'text-danger';
                            }
                            itemhtml += `
                                <label class=" font14 ${textcolor}">
                                    ${item.item_name}
                                    (${item.pivot.remark})
                                    (${item.pivot.quantity})
                                </label>
                            `;
                        })
                        var url = '{{route('getVoucherDetails',":voucher_id")}}';

                        url = url.replace(':voucher_id', voucher.id);
                        html+=`
                                        <tr>
                                            <td>${++i}</td>
                                            <td>${complete_order_checkbox}</td>
                                            <td>${voucher.voucher_code}</td>
                                            <td>${voucher.customer_name}</td>
                                            <td>${voucher.order_date}</td>
                                            <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                            <td>
                                                ${itemhtml}
                                            </td>
                                            <td class="text-center"><a href="${url}" class="btn btn-sm btn-outline-info">Details ${incomplete_order_checkbox}</a>
                                            </td>
                                            </tr>
                        `;

                    })
                    $('#order_lists').empty();
                    $('#order_lists').html(html);

                }

                }

                });
        }

    })

    $('#cancel_submit').click(function(e){
        e.preventDefault();
        var canceled_order_ids = $('#canceled_order_ids').val();
        var cancelDate = $('#cancel_date').val();
        var cancelRemark = $('#cancel_remark').val();
        var adminCode = $('#admin_code').val();
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
            $.ajax({

                type:'POST',

                url:'/cancel-orders',

                data:{
                "_token":"{{csrf_token()}}",
                "canceled_order_ids":canceled_order_ids,
                "cancelDate":cancelDate,
                "cancelRemark":cancelRemark,
                "type" : 1,
                "adminCode":adminCode,
                },

                success:function(data){
                    $('#canceled_order_ids').val(null);

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

                    var html = "";

                    $.each(data.voucher_lists,function(i,voucher)
                    {
                        var complete_order_checkbox = '';
                        if(data.order_type==2){
                            complete_order_checkbox += `
                            <input class="form-check-input" type="checkbox" value="${voucher.id}" id="checkitem${voucher.id}">
                            <label class="form-check-label font14" for="checkitem${voucher.id}"></label>
                            `;
                        }
                        var incomplete_order_checkbox = '';
                        if(data.order_type==3){
                            incomplete_order_checkbox += `
                            <a href="" class="btn btn-sm btn-outline-info">Transaction</a>
                            `;
                        }

                        var itemhtml = ``;
                        $.each(voucher.items,function(j,item){
                            if(item.pivot.status ==2){
                                var textcolor = 'text-success';
                            }else if(item.pivot.status == 5){
                                var textcolor = 'text-danger';
                            }
                            itemhtml += `
                                <label class=" font14 ${textcolor}">
                                    ${item.item_name}
                                    (${item.pivot.remark})
                                    (${item.pivot.quantity})
                                </label>
                            `;
                        })
                        var url = '{{route('getVoucherDetails',":voucher_id")}}';

                        url = url.replace(':voucher_id', voucher.id);
                        html+=`
                                        <tr>
                                            <td>${++i}</td>
                                            <td>${complete_order_checkbox}</td>
                                            <td>${voucher.voucher_code}</td>
                                            <td>${voucher.customer_name}</td>
                                            <td>${voucher.fbpage.name}</td>
                                            <td>${voucher.order_date}</td>
                                            <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                            <td>
                                                ${itemhtml}
                                            </td>
                                            <td class="text-center"><a href="${url}" class="btn btn-sm btn-outline-info">Details ${incomplete_order_checkbox}</a>
                                            </td>
                                            </tr>
                        `;

                    })
                    $('#order_lists').empty();
                    $('#order_lists').html(html);

                }

                }

                });
        }

    })


    function chkdatatype(val){
        $('#data_types').val(val);
    }

    $('#search_orders').click(function(){
        // var instockOrPreorder = $('#instockOrPreorder').val();
        // var fb_page = $('#fb_pages').val();
        // var current_Date = $("#current_Date").val();
        var order_type = $('#pendingOrders').val();
        var instockOrPreorder = $('#order_type').val();
        var fb_page = $('#fb_pages').val();
        var mkt_staff = $('#mkt_staffs').val();
        var from = $('#from_Date').val();
        var to = $('#to_Date').val();
        var date_type = $("input[name='inlineRadioOptions']:checked").val();
        console.log(order_type,fb_page,from,to,mkt_staff,date_type);

        // console.log(instockOrPreorder,fb_page,current_Date,order_type);
        $.ajax({

            type:'POST',

            url:'/get-orders',

            data:{
            "_token":"{{csrf_token()}}",
            "order_type":order_type,
            'instockOrPreorder':instockOrPreorder,
            'fb_page':fb_page,
            'from':from,
            'to':to,
            'mkt_staff':mkt_staff,
            'date_type': date_type,
            },

            success:function(data){
                if (data.order_lists.length >0) {
                $('#item_deliver').addClass('d-none');
                var html = "";
                var complete_order_checkbox = '';
                $.each(data.order_lists,function(i,voucher)
                {
                    if(order_type==2 || order_type == 3){
                        console.log("complete");
                        complete_order_checkbox = `
                        <input class="form-check-input" type="checkbox" value="${voucher.id}" id="checkitem${voucher.id}" enabled>
                        <label class="form-check-label font14" for="checkitem${voucher.id}"></label>
                        `;

                   }
                    var incomplete_order_checkbox = '';
                    if(order_type==3){
                        console.log("incomplete");
                        incomplete_order_checkbox = `
                        <a href="" class="btn btn-sm btn-outline-info">Transaction</a>
                        `;
                    }

                    var itemhtml = ``;
                    $.each(voucher.items,function(j,item){
                        if(item.pivot.status ==2){
                            var textcolor = 'text-success';
                        }else if(item.pivot.status == 5){
                            var textcolor = 'text-danger';
                        }
                        itemhtml += `
                            <label class=" font14 ${textcolor}">
                                ${item.item_name}
                                -${item.pivot.remark}
                                (${item.pivot.quantity})
                            </label>
                        `;
                    })
                    var url = '{{route('getVoucherDetails',":voucher_id")}}';

                    url = url.replace(':voucher_id', voucher.id);
                    html+=`
                                    <tr>
                                        <td>${++i}</td>
                                        <td>
                                        ` +
                            complete_order_checkbox +
                            `
                                        </td>
                                        <td>${voucher.voucher_code}</td>
                                        <td>${voucher.customer_name}</td>
                                        <td>${voucher.fbpage.name}</td>
                                        <td>${voucher.order_date}</td>
                                        <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                        <td>
                                            ${itemhtml}
                                        </td>
                                        <td class="text-center"><a href="${url}" class="btn btn-sm btn-outline-info">Details</a>
                                        `+
                                        incomplete_order_checkbox
                                        +
                                        `
                                        </td>
                                        </tr>
                    `;

                    $('#order_lists').empty();
                    $('#order_lists').html(html);

                })
                if(data.order_type == 2) {     //complete-orders
                    $('#item_deliver').removeClass('d-none');
                }else if(order_type == 3){
                    $('#item_cancel').removeClass('d-none');

                }
                } else {
                    var html = `

                    <tr>
                        <td colspan="9" class="text-danger text-center">No Data Found</td>
                    </tr>

                    `;
                    $('#order_lists').empty();
                    $('#order_lists').html(html);

                }




                //$("#item_table").dataTable().fnDestroy();

                    // var table= $("#item_table").DataTable();

            }
        });
    })
    $('#add_delivery').click(function(e){
        e.preventDefault();
       $('#add_delivery_modal').modal('show');
       $('#orderDeliveryModal').modal('hide');
    })
    $('#add_delivery_sent').click(function(e){
        e.preventDefault();
        var delivery_name = $('#delivery_name').val();
        $.ajax({

            type:'POST',

            url:'/add-delivery-name',

            data:{
            "_token":"{{csrf_token()}}",
            "delivery_name":delivery_name,
            },

            success:function(data){
                $('#add_delivery_modal').modal('hide');

                // $('#item_deliver').addClass('d-none');
                if(data.status ==0){
                    swal({
                    icon: 'error',
                    title: 'Something went wrong!',
                    text : 'Please check again',
                    button: true,
                })
                }else{

                    swal({
                        icon: 'success',
                        title: 'Success!',
                        text : 'Successfully add to new delivery',
                        button: true,
                    })

                var html = "";

                $.each(data.delivery_lists,function(i,delivery)
                {
                    html += `
                        <option class="form-control" value="${delivery.id}">${delivery.name}</option>
                    `;
                })
                $('#delivery_id').empty();
                $('#delivery_id').html(html);

                }
            }
        })
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
