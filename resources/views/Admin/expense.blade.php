@extends('master')

@section('title','Expenses List')

@section('place')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.expenses') @lang('lang.list')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.expenses') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')
        <div class="row">
            <div class="col-6">
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#add_expenses" >@lang('lang.add_expenses')</a>
                <div class="modal fade" id="add_expenses" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">@lang('lang.expenses')</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                            </div>
                            <div class="modal-body" id="slimtest2">
                                <form action="{{route('store_expense')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.expense_type')</label>
                                                <select class="form-control" onchange="showPeriod(this.value)" name="type">
                                                    <option value="">@lang('lang.select_expense_type')</option>
                                                    <option value="1">@lang('lang.fixed')</option>
                                                    <option value="2">@lang('lang.variable')</option>
                                                </select>
                                            </div>
                                        </div>                              
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.period')</label>
                                                <select class="form-control" id="period" name="period">
                                                    <option value="">@lang('lang.select')</option>
                                                    <option value="1">@lang('lang.daily')</option>
                                                    <option value="2">@lang('lang.weekly')</option>
                                                    <option value="3">@lang('lang.monthly')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.date')</label>
                                                <input type="text" class="form-control" id="mdate" name="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.title')</label>
                                                <input type="text" class="form-control" name="title">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.description')</label>
                                                <input type="text" class="form-control" name="description">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.amount')</label>
                                                <input type="number" class="form-control" name="amount">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">@lang('lang.applied_to_profit_loss')</label>
                                                <select class="form-control" name="profit_loss_flag">
                                                    <option value="">@lang('lang.select')</option>
                                                    <option value="1">@lang('lang.yes')</option>
                                                    <option value="2">@lang('lang.no')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-6 float-right">
                                                <div class="row">
                                                    <div class=" col-md-9">
                                                        <button type="submit" class="btn btn-success">@lang('lang.submit')</button>
                                                        <button type="button" class="btn btn-inverse btn-dismiss" data-dismiss="modal">@lang('lang.cancel')</button>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                   </div>
                                </form>       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br/>
        <div class="card">
            <div class="card-body">
                <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('lang.expense_type')</th>
                                <th>@lang('lang.period')</th>
                                <th>@lang('lang.date')</th>
                                <th>@lang('lang.title')</th>
                                <th>@lang('lang.description')</th>
                                <th>@lang('lang.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 1;
                        ?>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>{{$i++}}</td>
                            @if($expense->type == 1)
                            <td>@lang('lang.fixed')</td>
                            @else
                            <td>@lang('lang.variable')</td>
                            @endif
                            @if($expense->period == 1)
                            <td>@lang('lang.daily')</td>
                            @elseif($expense->period == 2)
                            <td>@lang('lang.weekly')</td>
                            @else
                            <td>@lang('lang.monthly')</td>
                            @endif
                            @if($expense->type == 1)
                            <td>ရက်စွဲမရှိပါ</td>
                            @else
                            <td>{{$expense->date}}</td>
                            @endif
                            <td>{{$expense->title}}</td>
                            <td>{{$expense->description}}</td>
                            <td>{{$expense->amount}}</td>
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
</div>


@endsection

@section('js')

<script src="{{asset('assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>

<script type="text/javascript">
    
    $('.dropify').dropify();
    
    $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
    
    $('#mdate').prop("disabled",true);
    $('#period').prop("disabled",true);
    
    function showPeriod(value){
        
        var show_options = value;

        if( show_options == 1){
            $('#mdate').prop("disabled",true);
            $('#period').prop("disabled",false);
            }

        else{

            $('#mdate').prop("disabled",false);
            $('#period').prop("disabled",true);
        }
    }
    
</script>

@endsection
