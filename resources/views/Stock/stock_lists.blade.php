@extends('master')

@section('title','Stock Lists')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.total_inventory_value')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.total_inventory_value')</li>
    </ol>
</div> --}}

@endsection

@section('content')
@php
$from_id = session()->get('from')
@endphp 
<input type="hidden" id="isowner" value="{{session()->get('user')->role}}">
<input type="hidden" id="isshop" value="{{session()->get('from')}}">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-black">@lang('lang.total_inventory_value')</h4>
    </div>
</div>


<div class="row">
    @if(session()->get('user')->role == "Owner")
    {{-- <div class="col-md-6">
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
    </div> --}}
    <div class="col-md-6 offset-6">
        <button id="print" class="btn btn-warning  float-right px-4" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
    </div>
    @endif
    <!--/span-->
</div>

<div class="row">
    <div class="col-lg-12">

        <div class="card card-outline-info">
            <div class="card-header">
                <h4 class="m-b-0 text-white">Items List</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive text-black printableArea">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>@lang('lang.item') @lang('lang.code')</th>
                                <th>@lang('lang.item') @lang('lang.name')</th>
                                <th>SKU Code</th>
                                <th>@lang('lang.current') @lang('lang.quantity')</th>
                                <th>@lang('lang.purchase_price')</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody id="units_table">
                        @php
                            $allTotal = 0;
                            $stockTotal = 0;
                            $i=1;
                        @endphp
                            @foreach($items as $item)
                         
                                
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$item->item_code ?? ''}}</td>
                                    <td>{{$item->item_name}}</td>
                                    <td>{{$item->sku_code}}</td>
                                    <td>
                                      {{$item->stock}}  
                                      
                                    </td>
                                    <td>
                                        {{$item->purchase_price}}
                                        @php
                                            $allTotal+= $item->stock * $item->purchase_price;
                                            $stockTotal+= $item->stock;
                                      @endphp
                                    </td>
                                    
                                    <td class="text-right">{{$item->stock * $item->purchase_price}}</td>
                                </tr>
                           
                               
                       
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-info"><b>Stock Total</b></td>
                                <td class="text-left font-bold">{{$stockTotal}}</td>
                                <td class="text-info"><b>Total Inventory</b></td>
                                <td class="text-right font-bold">{{$allTotal}} USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

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
                var allTotal = 0 ;
                var stockTotal = 0 ;
                $.each(data, function(i, value) {

                $('#item_list').append($('<option>').text(value.item_name).attr('value', value.id));
                
                $.each(value.counting_units,function(j,unit){
                    var stockcountt=0;
                    $.each(unit.stockcount,function(k,stock){
                        if(stock.from_id==shop_id){
                             stockcountt= unit.stockcount[k].stock_qty;
                        }
                    })

                    allTotal += unit.purchase_price * stockcountt;
                    stockTotal += stockcountt;
                    html += `
                    <tr>
                                    <td>${unit.unit_code ?? ""}</td>
                                    <td>${value.item_name}</td>
                                    <td>${unit.unit_name}</td>
                                    <td>${stockcountt}
                                        </td>
                                    <td>${unit.purchase_price}</td>
                                    <td class="text-right">${unit.purchase_price * stockcountt}</td>
                                </tr>
                    `;
                });    
             

            }),
            html+= `
                <tr>
                                <td></td>
                                <td></td>
                                <td class="text-info">Stock Total</td>
                                <td>${stockTotal}</td>
                                <td class="text-info">Total</td>
                                <td class="text-right font-bold">${allTotal}</td>
                            </tr>
                `;
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



$("#print").click(function() {

    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
        mode: mode,
        popClose: close
    };

    $("div.printableArea").printArea(options);

    });

</script>
@endsection