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
                <label class="control-label font-weight-bold">From</label>
                <input type="date" name="from" id="current_Date" class="form-control" value="{{ $current_Date }}" required>
            </div>
            <div class="col-md-2">
                <label class="control-label font-weight-bold">To</label>
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
            <div class="col-md-1 m-t-30">
                <button class="btn btn-success px-4" id="print">Print</button>
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
                                            
                                            <th>Transaction Date</th>
                                            <th>Bank Account</th>
                                            <th>Pay Amount</th>
                                            <th>Voucher Code</th>
                                            <th>Customer Name</th>
                                            <th>Page Name</th>
                                            <th>Remark</th>
                                           
                                            <th class="text-center">@lang('lang.details')</th>
                                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="item_list">
                                        <?php
                                        $i = 1;
                                        ?>
                                        @foreach ($transactions_lists as $key=>$transaction)
                                            <tr class="text-center">
                                                <td>{{ $i++ }}</td>
                                                
                                                <td>{{ $transaction['tran_date'] }}</td>
                                                <td>{{ $transaction['bank_name'] }} + {{$transaction['account_number']}}</td>
                                                <td>{{ $transaction['pay_amount'] }}</td>
                                                <td>{{$transaction['voucher_code']}}</td>
                                                <td>{{$transaction['customer_name']}}</td>
                                                <td>{{$transaction['page_name']}}</td>
                                                <td>{{$transaction['remark']}}</td>
                                                <td class="text-center"><a
                                                        href="{{ route('getVoucherDetails', $transaction['voucher_id']) }}"
                                                        class="btn btn-sm btn-outline-info">Details</a>
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
<div class="col-md-12 printableArea d-none">
    
    
        <div style="text-align: center;">
         <img src="{{asset('image/brilliant_logo.png')}}" class="m-l-120 m-b-10" height="150px">
    </div>
         <div class="col-md-6 ml-3">
            <p class="font-weight-bold mt-2" style="font-size: 28px">Transaction Report</p> 
        </div>
        
        
         <div class="col-md-6 ml-3">
            <p class="font-weight-bold mt-2" style="font-size: 20px">Report Name: {{$name}}</p> 
        </div>
        
        <div class="col-md-6 ml-3">
            <p class="font-weight-bold mt-2" style="font-size: 20px" id="report_date">Report Date: {{$current_Date}} </p> 
        </div>
    
    
    
<div class="table-responsive text-black" id="slimtest3">
            <table class="table" id="item_table">
                <thead>
                    <tr class="text-center">
                        <th>@lang('lang.number')</th>
                        <th>Transaction Date</th>
                        <th>Bank Account</th>
                        <th>Pay Amount</th>
                        <th>Voucher Code</th>
                        <th>Customer Name</th>
                        <th>Page Name</th>
                        <th>Remark</th>
                       
                        {{-- <th class="text-center">@lang('lang.action')</th> --}}
                    </tr>
                </thead>
                <tbody id="print_item_list">
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($transactions_lists as $transaction)
                        <tr class="text-center">
                            <td>{{ $i++ }}</td>
                            <td>{{ $transaction['tran_date'] }}</td>
                            <td>{{ $transaction['bank_name'] }} + {{$transaction['account_number']}}</td>
                            <td>{{ $transaction['pay_amount'] }}</td>
                            <td>{{$transaction['voucher_code']}}</td>
                            <td>{{$transaction['customer_name']}}</td>
                            <td>{{$transaction['page_name']}}</td>
                            <td>{{$transaction['remark']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="row offset-3">

        <div class="col-md-2 ml-3 " >
            <p class="mt-2" style="font-size: 20px;"><b>Pay Total  :</b>  <span class="payTotal font-weight-bold" style="font-size: 20px;"></span></p> 
        </div>
        <br/>
        
        <div class="col-md-2 ml-3 " >
            <p class="mt-2" style="font-size: 20px;"><b>CEO Sign:</b>  </p> 
        </div>
    </div>
        
</div>
        
    </section>

@endsection

@section('js')

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    {{-- <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script> --}}
    <script src="{{ asset('js/jquery.PrintArea.js') }}" type="text/JavaScript"></script>

    <script type="text/javascript">
        $('#item_table').DataTable({

            "paging": false,
            "ordering": true,
            "info": false

        });

        // $('#slimtest2').slimScroll({
        //     color: '#00f',
        //     height: '600px'
        // });

        // $('#slimtest3').slimScroll({
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
            
            console.log(from,to)
            $.ajax({

            type: 'POST',

            url: '{{ route('search_transactions_bydatev2') }}',

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
                    var print_html = '';
                    var orderCheckBox = '';
                    var payTotal = 0;
                    $.each(data, function(i, transaction) {
                       
                        var url1 = '{{ route('getVoucherDetails', ':voucher_id') }}';

                        url1 = url1.replace(':voucher_id', transaction.voucher_id);
                        html += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${transaction.tran_date}</td>
                                    <td>${transaction.bank_name}+${transaction.account_number}</td>
                                    <td>${transaction.pay_amount}</td>
                                    <td>${transaction.voucher_code}</td>
                                    <td>${transaction.customer_name}</td>
                                    <td>${transaction.page_name}</td>
                                    <td>${transaction.remark}</td>
                                    <td class="text-center"><a href="${url1}" class="btn btn-sm btn-outline-info">Details</a>
                                    </td>
                    </tr>
                    `;
                    

                    })
                    
                    $('#item_list').empty();
                        $('#item_list').html(html);
                        
                        
                        
                    $.each(data, function(i, transaction) {
                       
                        payTotal += transaction.pay_amount;
                       
                        
                    print_html += `
                    <tr class="text-center">
                                    <td>${++i}</td>
                                    <td>${transaction.tran_date}</td>
                                    <td>${transaction.bank_name}+${transaction.account_number}</td>
                                    <td>${transaction.pay_amount}</td>
                                    <td>${transaction.voucher_code}</td>
                                    <td>${transaction.customer_name}</td>
                                    <td>${transaction.page_name}</td>
                                    <td>${transaction.remark}</td>
                    </tr>
                    `;

                        
                    })
                    
                    $('#print_item_list').empty();
                        $('#print_item_list').html(print_html);
                        $('.payTotal').text(payTotal);
                        $('#report_date').text("Report Date: "+ from + " to " + to);

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
