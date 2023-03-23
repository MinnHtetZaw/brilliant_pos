@extends('master')

@section('title','Arrived Orders')

@section('place')

<div class="col-md-5 col-8 align-self-center">
    <h4 class="text-themecolor m-b-0 m-t-0">@lang('lang.sale_history')</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">Canceled Orders</li>
    </ol>
</div>

@endsection

@section('content')
<section id="plan-features">
    <div class="row ml-3 mt-4">


        <form action="{{route('canceledOrderLists')}}" method="POST">
            @csrf
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
                <select class="form-control" name="mkt_staff" id="mkt_staffs">
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

                <div class="col-md-1 m-t-30">
                    <button type="submit" class="btn btn-info px-4" id="search_orders">Search</button>
                </div>
            </div>
        </form>
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

                                        <th class="text-center">@lang('lang.details')</th>
                                        <th class="text-center">Action</th>
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
                                        <td><span class="badge badge-info font-weight-bold">Canceled</span></td>

                                        <td class="text-center"><a href="{{ route('getVoucherDetails',$voucher->id)}}" class="btn btn-sm btn-outline-info">Details</a>
                                        </td>
                                        <td>
                                            <button data-toggle="modal" data-target="#return_items_modal{{$voucher->id}}"
                                                             class="btn btn-sm btn-outline-danger">Add to stock</button>
                                            <div class="modal fade" id="return_items_modal{{$voucher->id}}" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Return Items</h4>
                                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                              </button>
                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <table class="table table-hover">
                                                                                <thead>
                                                                                    <tr class="text-black">
                                                                                        <th>No.</th>
                                                                                        <th> Item Code</th>
                                                                                        <th> Sku Code</th>
                                                                                        <th>Qty</th>
                                                                                        <th>Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="text-black">
                                                                                    @php
                                                                                        $j=1;
                                                                                    @endphp
                                                                                    @foreach ($voucher->items as $item)
                                                                                    <form action="">

                                                                                        <tr>
                                                                                        <td class="font-weight-normal pt-3" style="font-size:15px;">
                                                                                            {{ $j++ }}</td>

                                                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                                                            <input name="item_code" id="item_code{{$item->id}}" type="text" class="form-control" value="{{$item->item_code }}">
                                                                                            </td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;"><input name="sku_code" id="sku_code{{$item->id}}" type="text" class="form-control" value="{{$item->sku_code }}">
                                                                                        </td>
                                                                                        <td class="font-weight-normal pt-3" style="font-size:15px;">{{ $item->pivot->quantity }}</td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;">
                                                                                    @if ($item->pivot->status != 7)
                                                                                        <button class="btn btn-sm btn-outline-info itemToStock" type="submit" data-itemid="{{$item->id}}" data-quantity="{{$item->pivot->quantity}}" data-voucherid="{{$voucher->id}}">Item to stock</button>
                                                                                    @else
                                                                                    <button disabled class="btn btn-sm btn-outline-info itemToStock" data-itemid="{{$item->id}}" data-quantity="{{$item->pivot->quantity}}" data-voucherid="{{$voucher->id}}">Item to stock</button>
                                                                                    @endif                                                                               </td>
                                                                                        </tr>
                                                                                    </form>

                                                                                    @endforeach

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

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

    $('.itemToStock').click(function(e){
                    e.preventDefault();
                    let item_id = $(this).data('itemid');
                    let quantity = $(this).data('quantity');
                    let voucher_id = $(this).data('voucherid');
                    let item_code_id = `#item_code${item_id}`;
                    let item_code = $(item_code_id).val();
                    let sku_code_id = `#sku_code${item_id}`;
                    let sku_code = $(sku_code_id).val();
                    $.ajax({

                        type:'GET',

                        url:`/itemToStock/${item_id}/${quantity}/${voucher_id}/${item_code}/${sku_code}`,

                        success:function(data){
                            swal({
                                title: "Success!",
                                text : "Successfully Add To Stock!",
                                icon : "info",
                            });
                            setTimeout(() => {
                                history.go(0);
                            }, 800);

                        }
                    });
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
        console.log(order_type,fb_page,from,to,mkt_staff);
        $.ajax({

            type: 'POST',

            url: '{{ route('canceledOrderLists') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                "order_type": order_type,
                "from" : from,
                "to" : to,
                "mkt_staff": mkt_staff,
                "fb_page" :fb_page,
            },

            success: function(data) {
                if (data.length >0) {
                    console.log(data);
                //     var html = '';
                //     var orderCheckBox = '';
                    $.each(data, function(i, voucher) {
                        var itemhtml = '';
                        var buttonhtml = '';

                        $.each(voucher.items, function(j, item) {
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock


                                if (item.pivot.status == 7) {
                                var checkdisable = 'disabled';
                                } else{
                                    var checkdisable = '';
                                }




                            itemhtml += `
                                <tr>
                                <td class="font-weight-normal pt-3" style="font-size:15px;">
                                                                                            ${++j}</td>

                                                                                            <td class="font-weight-normal" style="font-size:15px;">
                                                                                            <input name="item_code" id="item_code${item.id}" type="text" class="form-control" value="${item.item_code }">
                                                                                            </td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;"><input name="sku_code" id="sku_code${item.id}" type="text" class="form-control" value="${item.sku_code }">
                                                                                        </td>
                                                                                        <td class="font-weight-normal pt-3" style="font-size:15px;">${item.pivot.quantity }</td>
                                                                                        <td class="font-weight-normal" style="font-size:15px;">

                                                                                        <button ${checkdisable} class="btn btn-sm btn-outline-info itemToStock" type="submit" data-itemid="${item.id}" data-quantity="${item.pivot.quantity}" data-voucherid="${voucher.id}">Item to stock</button>
                                                                                   </td>
                                </tr>
                        `;

                        })

                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', voucher.id);
                        html += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
                                    <td>${voucher.fbpage.name}</td>
                                    <td>${voucher.order_date}</td>
                                    <td><span class="badge badge-info font-weight-bold">Canceled</span></td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                                <td>
                                            <button data-toggle="modal" data-target="#return_items_modal${voucher.id}"
                                                             class="btn btn-sm btn-outline-danger">Add to stock</button>
                                            <div class="modal fade" id="return_items_modal${voucher.id}" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Return Items</h4>
                                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                              </button>
                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <table class="table table-hover">
                                                                                <thead>
                                                                                    <tr class="text-black">
                                                                                        <th>No.</th>
                                                                                        <th> Item Code</th>
                                                                                        <th> Sku Code</th>
                                                                                        <th>Qty</th>
                                                                                        <th>Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="text-black">


                                                                                    <form action="">

                                                                                                                          `+                                        itemhtml
                                                                                        +
                                                 `                                                                     </form>



                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                        </td>

                    </tr>
                    `;


                    })
                    $('#item_list').empty();
                        $('#item_list').html(html);

                  // $('#item_table').DataTable().clear().draw();
                    $('#item_table').DataTable( {

                        "paging":   false,
                        "ordering": true,
                        "info":     false,
                        "destroy": true
                    });

                    swal({
                        toast:true,
                        position:'top-end',
                        title:"Success",
                        text:"Orders Changed!",
                        button:false,
                        timer:500,
                        icon:"success"
                    });

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
