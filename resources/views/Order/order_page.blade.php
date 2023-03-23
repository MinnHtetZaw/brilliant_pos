@extends('master')

@section('title','Order Page')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.order') @lang('lang.page')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.order') @lang('lang.page')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<style>
    th{
    overflow:hidden;
    white-space: nowrap;
  }
</style>

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal text-black">@lang('lang.order') @lang('lang.page')</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                @if($type == 1)
                <h4 class="font-weight-bold mt-2">@lang('lang.incoming_order') @lang('lang.list')</h4>
                @elseif($type == 2)
                <h4 class="font-weight-bold mt-2">@lang('lang.confirm_order') @lang('lang.list')</h4>
                @elseif($type == 3)
                <h4 class="font-weight-bold mt-2">@lang('lang.changes_order') @lang('lang.list')</h4>
                @elseif($type == 4)
                <h4 class="font-weight-bold mt-2">@lang('lang.delivered_order') @lang('lang.list')</h4>
                @else
                <h4 class="font-weight-bold mt-2">@lang('lang.accepted_order') @lang('lang.list')</h4>
                @endif

            </div>
            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table" id="example23">
                        <thead>
                            <tr>
                                <th>@lang('lang.order') @lang('lang.number')</th>
                                <th>@lang('lang.customer') @lang('lang.name')</th>
                                <th>@lang('lang.order') @lang('lang.address')</th>
                                <th>@lang('lang.order') @lang('lang.date')</th>
                                @if($type == 5)
                                <th>@lang('lang.accepted_date')</th>
                                @endif
                                <th>@lang('lang.order') @lang('lang.total') @lang('lang.quantity')</th>
                                <th>@lang('lang.order') @lang('lang.status')</th>
                                <th class="text-center">@lang('lang.details')</th>
                                @if($type != 5)
                                <th class="text-center">@lang('lang.action')</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order_lists as $order)
                                <tr>
                                	<td>{{$order->order_number}}</td>
                                    <td>{{$order->customer->user->name}}</td>
                                	<td style="overflow:hidden;white-space: nowrap;">{{$order->address}}</td>
                                	<td style="overflow:hidden;white-space: nowrap;">{{date('d-m-Y', strtotime($order->order_date))}}</td>
                                    @if($order->status == 5)
                                    <td style="overflow:hidden;white-space: nowrap;">{{date('d-m-Y h:i A' , strtotime($order->accepted_date))}}</td>
                                    @endif
                                	<td>{{$order->total_quantity}}</td>
                                    @if($order->status == 1)
                                	<td><span class="badge badge-info font-weight-bold">Incoming Order</span></td>
                                    @elseif($order->status == 2)
                                    <td><span class="badge badge-info font-weight-bold">Confirm Order</span></td>
                                    @elseif($order->status == 3)
                                    <td><span class="badge badge-info font-weight-bold">Change Order</span></td>
                                    @elseif($order->status == 4)
                                    <td><span class="badge badge-info font-weight-bold">Delivered Order</span></td>
                                    @elseif($order->status == 5)
                                    <td><span class="badge badge-info font-weight-bold">Accepted Order</span></td>
                                    @endif
                                	<td class="text-center"><a href="{{route('order_details', $order->id)}}" class="btn btn-outline-info">Check Details</a>
                                    </td>
                                    @if($type != 5)
                                        @if($order->status !== 4)
                                        <td class="text-center">
                                            <a href="#" class="btn btn-outline-info" data-toggle="modal" data-target="#confirm_{{$order->id}}">@lang('lang.change_order_status')</a>

                                            <div class="modal fade" id="confirm_{{$order->id}}" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('lang.change_order_status') @lang('lang.form')</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form class="form" method="post" action="{{route('update_order_status')}}">
                                                                @csrf
                                                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                                                <input type="hidden" name="order_status" value="{{$order->status}}">

                                                                @if($order->status == 2 || $order->status == 3)

                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="col-5 col-form-label">
                                                                        @lang('lang.delivered_date')
                                                                    </label>

                                                                    <div class="col-7">
                                                                        <input class="form-control" type="datetime-local" name="delivered_date">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="example-text-input" class="col-5 col-form-label">
                                                                        @lang('lang.choose_employee')
                                                                    </label>

                                                                    <div class="col-7">
                                                                        <select class="form-control" name="employee" style="width: 100%" >
                                                                            <option value="">@lang('lang.select')</option>
                                                                            @foreach($employee_lists as $emp)
                                                                                @if($emp->user->role == "Delivery_Person")
                                                                            <option value="{{$emp->id}}">{{$emp->user->name}}</option>
                                                                                @endif
                                                                            @endforeach                              
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                @else 
                                                                    <div class="form-group row">
                                                                        <label for="example-text-input" class="col-5 col-form-label">
                                                                            @lang('lang.delivered_date')
                                                                        </label>

                                                                        <div class="col-7">
                                                                            <input class="form-control" type="date" name="delivered_date">
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.change_order_status')">
                                                            </form>           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>

                                        @else
                                        <td class="text-center" style="overflow:hidden;white-space: nowrap;">
                                            Order is Delivered!
                                        </td>
                                        @endif
                                    @endif
                                </tr>                                   
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')

<script type="text/javascript">

    $('#example23').DataTable( {
    
        "paging":   false,
        "ordering": true,
        "info":     false

    });

</script>



@endsection