@extends('master')

@section('title','Reorder Items')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Reorder Items</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">Reorder Items</li>
    </ol>
</div> --}}

@endsection

@section('content')
@php
$from_id = session()->get('from')
@endphp 
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal">Reorder Items</h4>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">               
                <div class="row">
                    {{-- @if(session()->get('user')->role == "Owner")
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-black">ဆိုင်ရွေးရန်</label>
                            <select class="form-control select2" onchange="getItems(this.value)" id="shop_id">
                                @foreach($shops as $shop)
                                <option value="{{$shop->id}}"
                                @if ($from_id==$shop->id)
                                    selected
                                @endif
                                >{{$shop->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif --}}
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-black">@lang('lang.select_item')</label>
                            <select class="form-control select2" id="item_list">
                                <option></option>
                                @foreach ($items as $item)
                                <option value="{{$item->id}}">{{$item->item_name}}</option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <button class="btn btn-success" onclick="checkUnit()"> 
                        <i class="fa fa-check"></i> @lang('lang.check_unit')
                    </button>
                </div>
    
            </div>        
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <div class="card card-outline-info">
            <div class="card-header">
                <h4 class="m-b-0 text-white">@lang('lang.counting_unit') @lang('lang.list')</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('lang.category') @lang('lang.name')</th>
                                <th>@lang('lang.item') @lang('lang.name')</th>
                                <th>@lang('lang.unit') @lang('lang.name')</th>
                                <th>@lang('lang.current') @lang('lang.quantity')</th>
                                <th>@lang('lang.reorder_quantity')</th>
                            </tr>
                        </thead>
                        <tbody id="units_table">
                            @foreach($items as $item)
                                @foreach ($item->counting_units as $unit)
                                @foreach ($unit->stockcount as $key=>$stockcount)
                                @if($unit->stockcount[$key]->from_id== $from_id && $unit->stockcount[$key]->stock_qty < $unit->reorder_quantity)
                                @php
                                    $stockcountt= $unit->stockcount[$key]->stock_qty;
                                @endphp
                                        <tr>
                                            <td>{{$item->category->category_name ?? "Default Category"}}</td>
                                            <td>{{$item->item_name}}</td>
                                            <td>{{$unit->unit_name}}</td>
                                        
                                            <td>{{$stockcountt}}</td>
                                            <td>{{$unit->reorder_quantity}}</td>
                                           
                                        </tr>        
                                    
                                @endif
                    
                            @endforeach
                                @endforeach
                       
                            @endforeach

                            <div class="modal fade" id="edit_unit_qty" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">@lang('lang.update_counting_unit_quantity') @lang('lang.form')</h4>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                        </div>

                                        <div class="modal-body">
                                            <form class="form-horizontal m-t-40" method="post" action="{{route('update_stock_count')}}">
                                                @csrf
                                                <input type="hidden" name="unit_id" id="unit_id">
                                                <div class="form-group row">
                                                    <label class="control-label text-black text-right col-md-6">@lang('lang.counting_unit') @lang('lang.quantity')</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="quantity"> 
                                                        
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label text-black text-right col-md-6">@lang('lang.reorder_level')</label>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" name="reorder"> 
                                                        
                                                    </div>
                                                </div>

                                                <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                                            </form>           
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('js')

<script>

    $(document).ready(function(){

        $(".select2").select2();
        $("#item_list").select2({
            placeholder:"ကုန်ပစ္စည်း ရှာရန်",
        });
    });

    function getItems(value){

        var shop_id = value;

        $.ajax({

            type:'POST',

            url:'{{route('AjaxGetItem')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "shop_id": shop_id,           
            },

            success:function(data){
                console.log(data);
                $('#item_list').empty();             

                $('#item_list').append($('<option>').text("ရှာရန်").attr('value', ""));
                var html = "";
                $.each(data, function(i, value) {

                $('#item_list').append($('<option>').text(value.item_name).attr('value', value.id));
                
                $.each(value.counting_units,function(j,unit){
                    var stockcountt=0;
                    $.each(unit.stockcount,function(k,stock){
                        if(stock.from_id==shop_id && unit.stockcount[k].stock_qty<= unit.reorder_quantity){
                             stockcountt= unit.stockcount[k].stock_qty;
                             html += `
                                    <tr>
                                                    <td>${value.category.category_name}</td>
                                                    <td>${value.item_name}</td>
                                                    <td>${unit.unit_name}</td>
                                                    <td>${stockcountt}</td>
                                                    <td>${unit.reorder_quantity}</td>
                                                </tr>
                                    `;

                        }
                    })
                  
                });    
                

            }),
            $('#units_table').empty();
            $('#units_table').html(html); 
            swal({
                toast:true,
                position:'top-end',
                title:"Success",
                text:"Shop Changed!",
                button:false,
                timer:500  
            }); 
        }

    })
}


    function checkUnit(){

        let shop_id = $('#shop_id').val();

        let item = $('#item_list').val();

        $('#units_table').empty();

        $.ajax({

            type:'POST',

            url:'{{route('AjaxGetCountingUnit')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "item": item,
                "shop_id":shop_id
            },

            success:function(data){
                $.each(data , function(i, value) {                 

                    var stockcountt=0;
                    $.each(value.stockcount,function(k,stock){
                        if(stock.from_id==shop_id && stock.stock_qty<= value.reorder_quantity){
                             stockcountt= stock.stock_qty;

                            $('#units_table').append($('<tr>')).append($('<td>').text(value.item.category.category_name)).append($('<td>').text(value.item.item_name)).append($('<td>').text(value.unit_name)).append($('<td>').append(stockcountt)).append($('<td>').append(value.reorder_quantity));
                        }
                    })
                    
                
               
                });


                
            },
        });

    }

    function getModal(value){

        event.preventDefault()

        $("#edit_unit_qty").modal("show");

        $("#unit_id").attr('value', value);
    }
  

</script>
@endsection