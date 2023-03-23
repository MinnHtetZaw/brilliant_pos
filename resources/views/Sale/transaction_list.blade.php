@extends('master')

@section('title', 'Transition Vouchers')

@section('place')

    <div class="col-md-5 col-8 align-self-center">
        <h4 class="text-themecolor m-b-0 m-t-0"></h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">@lang('lang.back_to_dashboard')</a></li>
            <li class="breadcrumb-item active">Transaction Lists</li>
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

        <div class="row ml-4 mt-3">
            @csrf
            <div class="col-md-2">
                <label class="control-label font-weight-bold">From Date</label>
                <input type="date" name="from" id="current_Date" class="form-control" value="{{ $current_Date }}" required>
            </div>
            <div class="col-md-2">
                <label class="control-label font-weight-bold">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $current_Date }}" required>
            </div>
            @if (session()->get('user')->role == 'Owner')
            <div class="col-md-2">
                <label class="control-label font-weight-bold">Bank Acconts</label>
                <select class="form-control" id="bank_accs">
                    <option value="0">All</option>
                    @foreach ($bank_accs as $bank_acc)
                        <option value="{{$bank_acc->id}}">{{ $bank_acc->account_holder_name  }}-{{$bank_acc->bank_name}}</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="col-md-2">
                <label class="control-label font-weight-bold">Pages</label>
                <select class="form-control" id="fb_pages">
                    <option value="0">All</option>
                    @foreach ($fb_pages as $fb_page)
                        <option value="{{$fb_page->id}}">{{ $fb_page->name }}</option>
                    @endforeach
                </select>
            </div>

            @endif
       
            <div class="col-md-1 m-t-30">
                <button class="btn btn-info px-4" id="search_orders">Search</button>
            </div>
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
                                    <tbody id="item_list">
                                        <?php
                                        $i = 1;
                                        ?>
                                        @foreach ($voucher_lists as $voucher)
                                            <tr class="text-center">
                                                <td>{{ $i++ }}</td>
                                                <td></td>
                                                <td>{{ $voucher->voucher_code }}</td>
                                                <td>{{ $voucher->customer_name }}</td>
                                                <td>{{ $voucher->order_date }}</td>
                                                <td><span
                                                        class="badge badge-info font-weight-bold">{{ $voucher->status }}</span>
                                                </td>
                                                {{-- <td>{{$voucher->total_quantity}}</td> --}}
                                                {{-- <td class="text-right">{{$voucher->item_charges}} + {{$voucher->delivery_charges}}</td> --}}
                                                <td>
                                                    @forelse ($voucher->items as $item)
                                                        <div class="form-check">
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
                                                                {{ $item->item_name }}
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
        $('#item_table').DataTable({

            "paging": false,
            "ordering": true,
            "info": false

        });

        $('#slimtest2').slimScroll({
            color: '#00f',
            height: '600px'
        });

        $('#search_orders').click(function(){
            var fb_page = $('#fb_pages').val();
            var bank_accs = $('#bank_accs').val();
            var from = $('#current_Date').val();
            var to = $('#to_date').val();
            if(!fb_page){
                type = 'owner'  //for owner filter with bank_acc
                value = bank_accs
            }
            else{
                type = 'marketing'    //for marketing, filter with fb_pages
                value = fb_page
            }
            $.ajax({

            type: 'POST',

            url: '{{ route('search_transactions_bydate') }}',

            data: {
                "_token": "{{ csrf_token() }}",
                'to' : to,
                "from" : from,
                "type" :type,
                "value":value
            },

            success: function(data) {
                console.log(data);
                if (data.length >0) {
                    var html = '';
                    var orderCheckBox = '';
                    $.each(data, function(i, voucher) {
                        var itemhtml = '';
                        var skuhtml = '';

                        $.each(voucher.items, function(j, item) {
                            // 0-Not Purchase,1-purchasing, 2 - arrived,3- instock,4 -packed, 5- Out of stock
                            if (item.pivot.status != 3) {
                                var checkdisable = 'disabled';
                            } else {
                                var checkdisable = null;
                            }
                            if (item.pivot.status == 1) {
                                var check_lable_color = 'text-warning';
                            } else if (item.pivot.status == 2 || item.pivot
                                .status == 4) {
                                var check_lable_color = 'text-success';
                            } else if (item.pivot.status == 5) {
                                var check_lable_color = 'text-danger';
                            }


                            itemhtml += `
                        <p class="${check_lable_color}">${item.item_name}</p>
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
                                    <td>
                                    ` +
                            orderCheckBox +
                            `
                                    </td>
                                    <td>${voucher.voucher_code}</td>
                                    <td>${voucher.customer_name}</td>
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
    </script>

@endsection
