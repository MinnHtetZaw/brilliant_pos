@extends('master')

@section('title','Arrived Orders')

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

    <div class="row ml-3 mt-4">

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
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1" onclick="chkdatatype(1)"  checked>
                                    <label class="form-check-label" for="inlineRadio2">Status Change Date</label>
                                  </div>
                            </div>
                            @endif



                        </div>

                    </div>

            {{-- <form action="{{route('arrivedOrderLists')}}" method="POST"> --}}
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
                    <select name="order_type" class="form-control" name="type_order" id="order_type">
                        <option value="1">Pre Order</option>
                        <option value="0">Instock Order</option>
                    </select>
                </div>

                <div class="col-md-1 m-t-30">
                    <button class="btn btn-info px-4" id="search_orders">Search</button>
                </div>
            </div>
        {{-- </form> --}}

        <div class="col-md-2 m-t-30">
        <button class="btn btn-success" id="itemarrived">Arrived</button>
        </div>
        <div class="col-md-2 m-t-30">
            <button class="btn btn-danger" id="itemOutofstock">Out Of Stock</button>
        </div>

        {{-- arrived form hidden --}}
        <form method="post" action="{{route('arrived_items')}}" id="arrived_items">
            @csrf
            <input type="hidden" name="arrived_item_ids" id="arrived_item_ids">
        </form>
        <form method="post" action="{{route('outofstock_items')}}" id="outofstock_items">
            @csrf
            <input type="hidden" name="outofstock_item_ids" id="outofstock_item_ids">
        </form>
        <div class="modal fade" id="itemOutofstockModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title text-white">Items Arrived @lang('lang.form')</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal m-t-20" method="post" action="{{route('store_purchase')}}">
                            @csrf
                            <input type="hidden" name="pur_item_ids" id="pur_item_ids">
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Supplier Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 text-black">Total Price</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="pur_item_total" id="pur_item_total">
                                </div>
                            </div>

                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
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
                            <table class="table" id="item_table">
                                <thead>
                                    <tr>
                                        <th>@lang('lang.number')</th>
                                        <th>@lang('lang.order') @lang('lang.number')</th>
                                        <th>@lang('lang.customer') @lang('lang.name')</th>
                                        <th>Page Name</th>
                                        <th>@lang('lang.order') @lang('lang.date')</th>
                                        <th>@lang('lang.order') @lang('lang.status')</th>
                                        {{-- <th>@lang('lang.total') Qty</th> --}}
                                        <th>Items</th>
                                        {{-- <th>Charges + Delivery Fee</th> --}}
                                        <th>SKU Code</th>
                                        <th class="text-center">@lang('lang.details')</th>
                                    </tr>
                                </thead>
                                <tbody id="item_list">
                                    <?php
                                        $i = 1;
                                    ?>
                                   @foreach($voucher_lists as $voucher)

                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$voucher->voucher_code}}</td>
                                        <td>{{$voucher->customer_name}}</td>
                                        <td>{{$voucher->fbpage->name}}</td>
                                        <td>{{$voucher->order_date}}</td>
                                        <td><span class="badge badge-info font-weight-bold">{{$voucher->status}}</span></td>
                                        {{-- <td>{{$voucher->total_quantity}}</td> --}}
                                        {{-- <td class="text-right">{{$voucher->item_charges}} + {{$voucher->delivery_charges}}</td> --}}
                                        <td>
                                                @forelse ($voucher->items as $item)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                    value="{{$voucher->id}}-{{$item->id}}-{{$item->pivot->quantity}}"
                                                    id="checkitem{{$voucher->id}}{{$item->id}}{{$item->pivot->quantity}}"

                                                        @if ($item->pivot->status==2)    {{--arrived --}}
                                                         disabled
                                                        @elseif ($item->pivot->status==5) {{--purchasing --}}
                                                        disabled
                                                        @endif
                                                    >

                                                    <label class="form-check-label font14
                                                        @if ($item->pivot->status==2)      {{--arrived --}}
                                                            text-success
                                                        @elseif($item->pivot->status==1) {{--purchasing--}}
                                                        text-warning
                                                        @elseif($item->pivot->status == 5)    {{--out of stock --}}
                                                            text-danger
                                                        @endif
                                                    "
                                                    for="checkitem{{$voucher->id}}{{$item->id}}{{$item->pivot->quantity}}">
                                                      {{$item->item_name}} ({{$item->pivot->status}})
                                                      -{{$item->pivot->remark}}
                                                      ({{$item->pivot->quantity}})
                                                    </label>
                                                  </div>
                                                @empty

                                                @endforelse
                                        </td>
                                        <td>{{$item->sku_code}}</td>
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

    $(".select").select2({
                placeholder: "ရှာရန်",
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

        var order_type = $('#order_type').val();
        var fb_page = $('#fb_pages').val();
        var mkt_staff = $('#mkt_staffs').val();
        var from = $('#from_Date').val();
        var to = $('#to_Date').val();
        var date_type = $("input[name='inlineRadioOptions']:checked").val();
        console.log(order_type,fb_page,from,to,mkt_staff,date_type);
        $.ajax({

            type: 'POST',

            url: '{{ route('arrivedOrderLists') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                "order_type": order_type,
                "from" : from,
                "to" : to,
                "mkt_staff": mkt_staff,
                "fb_page" :fb_page,
                "date_type": date_type,
            },

            success: function(data) {
                if (data.length >0) {
                    var html = '';
                    var orderCheckBox = '';
                    $.each(data, function(i, voucher) {
                        var itemhtml = '';
                        var skuhtml = '';
                        var order_type = $('#order_type').val();
                        if(voucher){
                        $.each(voucher.items, function(j, item) {
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock


                                if (item.pivot.status == 2) {
                                var checkdisable = 'disabled';
                                } else if (item.pivot.status == 5) {
                                    var checkdisable = 'disabled';
                                }


                            console.log(checkdisable);
                            if (item.pivot.status == 1) {
                                var check_lable_color = 'text-warning';
                            } else if (item.pivot.status == 2) {
                                var check_lable_color = 'text-success';
                            } else if (item.pivot.status == 5) {
                                var check_lable_color = 'text-danger';
                            }

                            itemhtml += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${voucher.id}-${item.id}-${item.pivot.quantity}" id="checkitem${voucher.id}${item.id}${item.pivot.quantity}"
                                ${checkdisable}
                                >

                                <label style="margin-bottom: 1rem" class="form-check-label font14
                                    ${check_lable_color}
                                "
                                for="checkitem${voucher.id}${item.id}${item.pivot.quantity}" title="${item.sku_code}">
                                    ${item.item_name}-${item.pivot.remark}
                                    (${item.pivot.quantity})
                                </label>
                            </div>
                        `;
                            skuhtml += `
                        <p>${item.sku_code}</p>
                        `;
                        })

                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                        html += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.fbpage? voucher.fbpage.name : '-'}</td>
                                    <td>${voucher.order_date}</td>
                                    <td><span class="badge badge-info font-weight-bold">${voucher.status}</span></td>
                                    <td>
                                    ` +
                            itemhtml +
                            `

                                    </td>
                                    <td>
                                        ` +
                            skuhtml +
                            `
                                    </td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>

                    </tr>
                    `;

                        $('#item_list').empty();
                        $('#item_list').html(html);
                        }
                    })

                  // $('#item_table').DataTable().clear().draw();
                    $('#item_table').DataTable( {

                        "paging":   false,
                        "ordering": true,
                        "info":     false,
                        "destroy": true
                    });

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

    function chkdatatype(val){
        $('#data_types').val(val);
    }

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
