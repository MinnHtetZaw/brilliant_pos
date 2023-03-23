@extends('master')

@section('title', 'Transaction Lists')

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
            <h4 class="font-weight-normal">Transaction @lang('lang.list')</h4>
        </div>

        {{-- <div class="col-md-7 col-4 align-self-center">
        <div class="d-flex m-t-10 justify-content-end">
            <a href="{{route('create_purchase')}}" class="btn btn-outline-primary">
                <i class="fas fa-plus"></i>                   
                @lang('lang.purchase_history') @lang('lang.create')
            </a>
        </div>
    </div> --}}
    </div>

    <form method="post" action="{{ route('search_transactions_bydate') }}">
        <div class="row ml-4 my-3">
            @csrf
            <div class="col-md-2">
                <label class="control-label font-weight-bold">@lang('lang.from')</label>
                <input type="date" name="from" id="current_Date" class="form-control" value="{{ $current_Date }}"
                    required>
            </div>

            <div class="col-md-1 m-t-30">
                <button type="submit" class="btn btn-info px-4">Search</button>
            </div>
        </div>
    </form>

    <form method="post" action="{{ route('transaction_detail') }}" id="transaction_detail">
        @csrf
        <input type="hidden" name="from" id="from">
        <input type="hidden" name="bank_id" id="bank_id">
    </form>

    <div class="row justify-content-center">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bank Name</th>
                                <th>Acc Number</th>
                                <th>Name</th>
                                <th>Current Balance</th>
                                <th>Date</th>
                                <th>Total Pay Amount</th>
                                <th class="text-center">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                                $total_payamount = 0;
                            ?>
                            @forelse($transactionArr as $list)
                            @php
                                $total_payamount += $list['pay_amount'];
                            @endphp
                                <tr>
                                    <th>{{ $i++ }}</th>
                                    <th>{{ $list['bank_acc_id']->bank_name }}</th>
                                    <th>{{ $list['bank_acc_id']->account_number }}</th>
                                    <th>{{ $list['bank_acc_id']->account_holder_name }}</th>
                                    <th>{{ $list['bank_acc_id']->balance }}</th>
                                    <th>{{ date('d-m-Y', strtotime($list['tran_date'])) }}</th>
                                    <th>{{ $list['pay_amount'] }}</th>
                                    <th class="text-center">
                                        <button data-id="{{ $list['bank_acc_id']->id }}"
                                            class="btn btn-success checkdetail">
                                            Check Details
                                        </button>
                                    </th>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-danger text-center">No transaction lists found! </td>
                                </tr>
                                
                            @endforelse

                            @if ($transactionArr)
                            <tr>
                                <td colspan="6" class="text-right font-weight_bold">Total Pay Amount </td>
                                <td class=" font-weight_bold">{{$total_payamount}}</td>
                            </tr>
                            @endif

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
            $('.checkdetail').click(function() {
                var bank_acc_id = $(this).data('id');
                var current_Date = $('#current_Date').val();
                $('#bank_id').val(bank_acc_id);
                $('#from').val(current_Date);
                $('#transaction_detail').submit();

            })
        })
    </script>
@endsection
