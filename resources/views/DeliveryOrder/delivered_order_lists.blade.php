@extends('master')

@section('title', 'Delivery Orders')

@section('place')

    {{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.purchase_history') @lang('lang.list')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.purchase_history') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h4 class="font-weight-normal">Delivery Lists</h4>
        </div>
    </div>
    
    <div class="row">
    <form action="{{route('deliveryordersListsByDate')}}" method="post">
        @csrf
        @method('post')
        <div class="row mb-3">
            <div class="col-md-3 offset-1">
                <label class="control-label font-weight-bold">From</label>
                <input id="from_date" type="date" name="from" class="form-control" value="{{ $from_date }}" required>
            </div>
            
            <div class="col-md-3 offset-1">
                <label class="control-label font-weight-bold">To</label>
                <input id="to_date" type="date" name="to" class="form-control" value="{{ $to_date }}" required>
            </div>
            
            <div class="col-md-2 mt-4 pt-1">
                <button class="btn btn-success">Search</button>
            </div>
            
           
        </div>
    </form>
    
    <div class="col-md-2 mt-4 pt-1">
                <button class="btn btn-success" id="export">Export</button>
            </div>
    
        
        </div>

    {{-- <form action="{{ route('delivery_order_details') }}" id="delivery_detail_form" method="post">
        @csrf
        @method('post')
        <div class="row mb-3">
            <div class="col-md-3 offset-1">
                <input type="hidden" name="delivery_id" id="delivery_id">
                <input type="hidden" id="delivery_from_date" name="from" class="form-control" value="{{ $current_date }}" required>
                <input type="hidden" name="total_order_qty" >
            </div>
        </div>
    </form> --}}

    <div class="row justify-content-center">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Delivery name</th>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.total') Orders</th>
                                <th>Total collect amt</th>
                                <th>Total delivery expense</th>
                                <th>Total received amt</th>
                                <th class="text-center">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse ($deliveryListArr as $delivery_list)
                                <tr>
                                    <th>{{ $i++ }}</th>
                                    <th>{{ $delivery_list['delivery']->name }}</th>
                                    <th>{{ $delivery_list['date'] }}</th>
                                    <th>{{ $delivery_list['total_order_count'] }}</th>
                                    <th>{{ $delivery_list['total_collect_amount'] }}</th>
                                    <th>{{ $delivery_list['total_delivery_expense'] }}</th>
                                    <th>{{ $delivery_list['total_collect_amount'] - $delivery_list['total_delivery_expense'] }}
                                    </th>
                                    <th class="text-center">
                                       
                                        <form action="{{ route('delivery_order_details') }}" id="delivery_detail_form" method="post">
                                            @csrf
                                            @method('post')
                                            <div class="row mb-3">
                                                <div class="col-md-3 offset-1">
                                                    <input type="hidden" name="delivery_id" value="{{ $delivery_list['delivery_id'] }}">
                                                    <input type="hidden" id="delivery_from_date" name="from" class="form-control" value="{{ $from_date }}" required>
                                                    <input type="hidden" id="delivery_from_date" name="to" class="form-control" value="{{ $to_date }}" required>
                                                    <input type="hidden" name="total_order_qty" value="{{ $delivery_list['total_order_qty'] }}">
                                                    <input type="hidden" name="total_order_count" value="{{ $delivery_list['total_order_count'] }}">
                                                    <input type="hidden" name="total_collect_amount" value="{{ $delivery_list['total_collect_amount'] }}">
                                                    <input type="hidden" name="total_delivery_expense" value="{{ $delivery_list['total_delivery_expense'] }}">
                                                    <input type="hidden" name="delivery" value="{{ $delivery_list['delivery']->name }}">
                                                </div>
                                            </div>
                                            <button 
                                            type="submit"
                                            class="btn btn-success">
                                            Check Details
                                        </button>
                                        </form>
                                    </th>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <p class="text-danger">
                            No Delivery Orders Founds!
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')

    <script>
        $(document).ready(function() {
            $('.delivery_detail_btn').click(function(e) {
                e.preventDefault();
                var delivery_id = $(this).data('id');
                $('#delivery_id').val(delivery_id);
                var from_date= $('#from_date').val();
                var total_order_qty = $(this).data('total_order_qty');
                var total_collect_amount = $(this).data('total_collect_amount');
                var total_delivery_expense = $(this).data('total_delivery_expense');
                var delivery = $(this).data('delivery');
                $('#delivery_from_date').val(from_date);
                console.log(total_order_qty,total_collect_amount,total_delivery_expense,delivery);
                // $('#delivery_detail_form').submit();
            })
        });
        
        $('#export').click(function(){
            var from = $("#from_date").val();
        var to = $("#to_date").val();
        var id =  6;
        
        console.log(from,to,id);
        
        // fetch("http://medicalworldinvpos.kwintechnologykw09.com/Sale/Voucher/HistoryExport/${from}/${to}/${id}",{
        //     method: "get"
        // }).then(()=>{console.log('Export Success');})
        // .catch((err)=>{console.log(err);});
         let url = `/export-deliveredorder-history/${from}/${to}/${id}`;
         window.location.href= url;
            
        });
    </script>


@endsection
