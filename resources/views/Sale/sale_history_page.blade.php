@extends('master')

@section('title', 'Incoming Ordes')

@section('place')

    <div class="col-md-5 col-8 align-self-center">
        <h4 class="text-themecolor m-b-0 m-t-0">@lang('lang.sale_history')</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">@lang('lang.back_to_dashboard')</a></li>
            <li class="breadcrumb-item active">Incoming Orders</li>
        </ol>
    </div>

@endsection

@section('content')
    <section id="plan-features">
        {{-- <div class="row ml-2 mr-2">
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h3 font-weight-normal mb-0 text-info"
                                    style="font-size: 20px;">{{ $total_sales }} @lang('lang.ks')</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow"
                                    style="background-color:#473C70;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-success font-weight-normal text-sm">
                            <span>@lang('lang.all_time_sale')</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h3 font-weight-normal mb-0 text-info"
                                    style="font-size: 20px;">{{ $daily_sales }} @lang('lang.ks')</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow"
                                    style="background-color:#473C70;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-success font-weight-normal text-sm">
                            <span>@lang('lang.today') @lang('lang.sales')</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h2 font-weight-normal mb-0 text-info"
                                    style="font-size: 25px;">{{ $weekly_sales }} @lang('lang.ks')</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow"
                                    style="background-color:#473C70;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-success font-weight-normal text-sm">
                            <span>@lang('lang.this') @lang('lang.week')</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <span class="h3 font-weight-normal mb-0 text-info"
                                    style="font-size: 20px;">{{ $monthly_sales }} @lang('lang.ks')</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape text-white rounded-circle shadow"
                                    style="background-color:#473C70;">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-success font-weight-normal text-sm">
                            <span>@lang('lang.this') @lang('lang.month')</span>
                        </p>
                    </div>
                </div>
            </div>
        </div> --}}
        <form action="{{route('orderListsOrderType')}}" method="POST">
            @csrf
            <div class="row ml-4 mt-3">

            <div class="col-md-2">
                <label class="control-label font-weight-bold">@lang('lang.from')</label>
                <input type="date" name="from" id="from_Date" class="form-control" value="{{$current_Date}}" required>
            </div>

            <div class="col-md-2">
                <label class="control-label font-weight-bold">@lang('lang.to')</label>
                <input type="date" name="to" id="to_Date" class="form-control" value="{{$current_Date}}" required>
            </div>

            <div class="col-md-2 m-t-30">
                <select class="form-control" name="mkt_staff" id="mkt_staffs">
                    <option value="0">All</option>
                    @foreach ($mkt_staffs as $mkt_staff)
                        <option value="{{$mkt_staff->id}}">{{ $mkt_staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 m-t-30">
                <select class="form-control select" name="fb_page" id="fb_pages" >
                    <option value="0">All</option>
                    @foreach ($fb_pages as $fb_page)
                        <option value="{{$fb_page->id}}">{{ $fb_page->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 m-t-30">
                <select name="order_type" class="form-control" id="order_type" name="type_order">
                    <option value="1">Pre Order</option>
                    <option value="0">Instock Order</option>
                </select>
            </div>

            <div class="col-md-1 m-t-30">
                <button type="submit" class="btn btn-info px-4" id="search_orders">Search</button>
            </div>

        </div>
        </form>


        <div class="row ml-4 mt-3">

            <div class="col-md-1 m-t-30">
                <button class="btn btn-success px-4" id="itempack">Pack</button>
            </div>
            <div class="col-md-1 m-t-30">
                <button class="btn btn-warning" id="itempurchase">Purchase</button>
            </div>
            <div class="col-md-1 m-t-30">
                <button class="btn btn-danger" id="itemOutofstock">Out Of Stock</button>
            </div>

             <div class="col-md-3 m-t-30 ml-4">
           <p class="mt-2" style="font-size: 15px;"><b>Total Sale Items  :</b>  <span class="itemTotal font-weight-bold" style="font-size: 20px;"></span></p>
            </div>

            <div class="col-md-3 m-t-30 ml-4">
           <p class="mt-2" style="font-size: 15px;"><b>Total Sale Amount  :</b>  <span class="amountTotal font-weight-bold" style="font-size: 20px;"></span></p>
            </div>

            <div class="modal fade" id="itempurchaseModal" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <h4 class="modal-title text-white">Items Purcahse @lang('lang.form')</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form class="form-horizontal m-t-20" method="post" action="{{ route('store_purchase') }}">
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
                                        <input type="number" class="form-control" name="pur_item_total" id="pur_item_total">
                                    </div>
                                </div>

                                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary"
                                    value="@lang('lang.save')">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('outofstock_items') }}" id="outofstock_items">
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
                            <form class="form-horizontal m-t-20" method="post" action="{{ route('store_purchase') }}">
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
                                        <input type="number" class="form-control" name="pur_item_total" id="pur_item_total">
                                    </div>
                                </div>

                                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary"
                                    value="@lang('lang.save')">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('packed_orders') }}" id="packed_orders">
                @csrf
                <input type="hidden" name="packed_order_ids" id="packed_order_ids">
            </form>
        </div>
        <br />

        <div class="container">
            <div class="card">
                <div class="card-body shadow">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive text-black" id="slimtest2">
                                <table class="table" id="item_table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>@lang('lang.number')</th>
                                            <th>#</th>
                                            <th>@lang('lang.order') @lang('lang.number')</th>
                                            <!--<th>Page Name</th>-->
                                            <th>@lang('lang.customer') @lang('lang.name')</th>
                                            <th>Page Name</th>
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
                                    <tbody id="item_list">
                                        <?php
                                        $i = 1;
                                        ?>
                                        @foreach ($voucher_lists as $voucher)
                                            <tr class="text-center">
                                                <td>{{ $i++ }}</td>
                                                <td></td>
                                                <td>{{ $voucher->voucher_code }}</td>
                                                <!--<td>{{$voucher->fbpage->name}}</td>-->
                                                <td>{{ $voucher->customer_name }}</td>
                                                <td>{{ $voucher->fbpage->name }}</td>
                                                <td>{{ $voucher->order_date }}</td>
                                                <td><span
                                                        class="badge badge-info font-weight-bold">{{ $voucher->status }}</span>
                                                </td>
                                                {{-- <td>{{$voucher->total_quantity}}</td> --}}
                                                {{-- <td class="text-right">{{$voucher->item_charges}} + {{$voucher->delivery_charges}}</td> --}}
                                                <td>
                                                    @forelse ($voucher->items as $item)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="{{ $voucher->id }}-{{ $item->id }}-{{ $item->pivot->quantity }}"
                                                                data-purchase = "{{$item->purchase_price}}"
                                                                id="checkitem{{ $voucher->id }}{{ $item->id }}{{ $item->pivot->quantity }}"
                                                                @if ($item->pivot->status != 0) disabled  {{-- 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock --}} @endif>

                                                            <label style="margin-bottom: 1rem"
                                                                class="form-check-label font14
                                                        @if ($item->pivot->status == 1) text-warning
                                                        @elseif ($item->pivot->status == 2)      {{-- arrived --}}
                                                            text-success
                                                        @elseif($item->pivot->status == 5)    {{-- out of stock --}}
                                                            text-danger @endif
                                                    "
                                                                for="checkitem{{ $voucher->id }}{{ $item->id }}{{ $item->pivot->quantity }}"
                                                                title="{{ $item->sku_code }}">
                                                                {{ $item->item_name }}-{{$item->pivot->remark}}
                                                                ({{ $item->pivot->quantity }})
                                                            </label>
                                                        </div>
                                                    @empty
                                                    @endforelse

                                                </td>
                                                <td>
                                                    @forelse ($voucher->items as $item)
                                                        <p>{{ $item->sku_code }}</p>
                                                    @empty
                                                    @endforelse
                                                </td>
                                                <td class="text-center"><a
                                                        href="{{ route('getVoucherDetails', $voucher->id) }}"
                                                        class="btn btn-sm btn-outline-info">Details</a>
                                                    {{-- <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Transaction</a> --}}

                                                </td>
                                                {{-- <td class="text-center"> --}}
                                                {{-- <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Arridved</a> --}}
                                                {{-- <a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Deliver</a> --}}
                                                {{-- </td> --}}
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

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    {{-- <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script> --}}

    <script type="text/javascript">
        // $('#item_table').DataTable({

        //     "paging": false,
        //     "ordering": true,
        //     "info": false

        // });

        // $('#slimtest2').slimScroll({
        //     color: '#00f',
        //     height: '600px'
        // });
        $(".select").select2({
                placeholder: "ရှာရန်",
        });

        $(document).ready(function() {
            $('#itempack').addClass('d-none');
        })
        $('#itempurchase').click(function() {
            var arr = [];
            var total_purchase_price = 0;

            $('input.form-check-input:checkbox:checked').each(function() {
                arr.push($(this).val());
                total_purchase_price += $(this).data('purchase');
            });

            if (!arr.length) {
                swal({
                    icon: 'error',
                    title: 'Item ရွေးပါ!',
                    text: 'Please choose items to purchse',
                    button: true,
                })
            } else {
                console.log(total_purchase_price);
                $('#pur_item_ids').val(JSON.stringify(arr));
                $('#itempurchaseModal').find('input[id="pur_item_total"]').val(total_purchase_price);
                $('#itempurchaseModal').modal('show');


            }
        })
        $('#itemOutofstock').click(function() {
            var arr = [];
            $('input.form-check-input:checkbox:checked').each(function() {
                arr.push($(this).val());
            });

            if (!arr.length) {
                swal({
                    icon: 'error',
                    title: 'Item ရွေးပါ!',
                    text: 'Please choose items to purchse',
                    button: true,
                })
            } else {
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
        $("#order_type").change(function() {
            var order_type = $(this).val();
            if (order_type == 0) {
                $('#itempurchase').removeClass('d-block');
                $('#itempurchase').addClass('d-none');

                $('#itempack').removeClass('d-none');
                $('#itempack').addClass('d-block');
            } else {
                $('#itempack').removeClass('d-block');
                $('#itempack').addClass('d-none');

                $('#itempurchase').removeClass('d-none');
                $('#itempurchase').addClass('d-block');
            }

        })

        $('#search_orders').click(function(){
            var order_type = $('#order_type').val();
            var fb_page = $('#fb_pages').val();
            var mkt_staff = $('#mkt_staffs').val();
            var from = $('#from_Date').val();
            var to = $('#to_Date').val();
            console.log(order_type,fb_page,from);
            $.ajax({

            type: 'POST',

            url: '{{ route('orderListsOrderType') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                "order_type": order_type,
                "from" : from,
                "to" : to,
                "mkt_staff": mkt_staff,
                "fb_page" :fb_page
            },

            success: function(data) {

                if (data.length >0) {
                    var html = '';
                    var orderCheckBox = '';
                    var itemTotal = 0;
                    var amountTotal = 0;
                    $.each(data, function(i, voucher) {
                        var itemhtml = '';
                        var skuhtml = '';
                        var order_type = $('#order_type').val();
                        amountTotal += voucher.item_charges;
                        $.each(voucher.items, function(j, item) {
                            itemTotal += item.pivot.quantity;
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock
                            let instockOrder = 1;
                            if(order_type==instockOrder){
                                if (item.pivot.status == 0) {
                                var checkdisable = null;
                                } else {
                                    var checkdisable = 'disabled';
                                }
                            }else{
                                if (item.pivot.status != 3) {
                                var checkdisable = 'disabled';
                                } else {
                                    var checkdisable = null;
                                }
                            }

                            console.log(checkdisable);
                            if (item.pivot.status == 1) {
                                var check_lable_color = 'text-warning';
                            } else if (item.pivot.status == 2 || item.pivot
                                .status == 4) {
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
                        if (voucher.order_type == "Instock" && voucher.status ==
                            "Order Save") {
                            orderCheckBox = `
                        <div class="form-check">
                            <input class="form-check-input order_pack_checkbox" type="checkbox" value="${voucher.id}" id="checkOrder${voucher.id}"
                            >

                            <label style="margin-bottom: 1rem" class="form-check-label font14 " for="checkOrder${voucher.id}" title="${voucher.voucher_code}">
                            </label>
                        </div>
                    `;
                        } else {
                            orderCheckBox = '';
                        }
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                        html += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>
                                    ` +
                            orderCheckBox +
                            `
                                    </td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.fbpage.name}</td>
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
                    })
                       $('.itemTotal').text(itemTotal);
                       $('.amountTotal').text(amountTotal);
                    $('#item_table').DataTable({

                    "paging": false,
                    "ordering": true,
                    "info": false,
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
        $('#itempack').click(function() {
            var arr = [];
            $('input.order_pack_checkbox:checkbox:checked').each(function() {
                arr.push($(this).val());
            });
            console.log(arr);
            if (!arr.length) {
                swal({
                    icon: 'error',
                    title: 'Order ရွေးပါ!',
                    text: 'Please choose orders to pack',
                    button: true,
                })
            } else {
                $('#packed_order_ids').val(JSON.stringify(arr));
                $('#packed_orders').submit();

            }
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
                    alert('successs');
                    console.log(data.page);

                    $('#fb_pages').append($('<option>').text("All").attr('value', 0));

                    $.each(data, function(i, value) {

                        $('#fb_pages').append($('<option>').text(value.name).attr('value', value.id));
                    });

                }

            });
        }

    </script>

@endsection
