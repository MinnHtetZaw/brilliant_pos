@extends('master')

@section('title','Item Adjust Lists')

@section('place')

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal">Item Adjust lists</h4>
    </div>


    <div class="col-md-7 col-4 align-self-center">
        <div class="d-flex m-t-10 justify-content-end">
            <a href="{{route('itemadjust')}}" class="btn btn-outline-primary">
                <i class="fas fa-plus"></i>                   
                 @lang('lang.item_adjust')
            </a>
        </div>
    </div>  
   
</div>

<div class="row ml-4 mt-3">
    <form action="{{route('search_item_adjusts')}}" method="POST" class="form">
        @csrf
        <div class="row">
            <div class="col-md-7 offset-2">
                <label class="control-label font-weight-bold">@lang('lang.date')</label>
                <input type="date" name="from" class="form-control" required>
            </div>

            <div class="col-md-2 offset-1 m-t-30">
                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.search')">
            </div>
        </div>
    </form>

</div>
<br>
<div class="row justify-content-center">
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive text-black">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('lang.date')</th>
                            <th>@lang('lang.item') @lang('lang.name')</th>
                            <th>အရင်လက်ကျန်</th>
                            <th>@lang('lang.item_adjust') @lang('lang.quantity')</th>
                            <th>@lang('lang.new_qty')</th>
                            <th>Created By</th>
                            <th>From</th>
                            {{-- <th class="text-center">@lang('lang.action')</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;?>
                        @foreach($item_adjusts as $item_adjust)
                            <tr>
                                <th>{{$i++}}</th>
                                <th>{{date('d-m-Y', strtotime($item_adjust->created_at))}}</th>
                                <th>{{$item_adjust->counting_unit->unit_name}}</th>
                                <th>{{$item_adjust->oldstock_qty}}</th>
                                <th>{{$item_adjust->adjust_qty}}</th>
                                <th>{{$item_adjust->newstock_qty}}</th>
                                <th>{{$item_adjust->user->name}}</th>
                                <th>{{$item_adjust->from->name}}</th>
                          
                             
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection