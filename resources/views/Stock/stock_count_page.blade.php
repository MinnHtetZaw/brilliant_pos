@extends('master')

@section('title','Stock Count')

@section('place')

@endsection

@section('content')
@php
$from_id = session()->get('from')
@endphp 
<input type="hidden" id="isowner" value="Owner">
<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h4 class="font-weight-normal">@lang('lang.stock_count')</h4>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">               
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-black">@lang('lang.select_item')</label>
                            <select class="form-control select2" id="item_list">
                                <option></option>
                                @foreach ($items as $item)
                                        <option value="{{$item->id}}"
                                            >{{$item->item_code}} - {{$item->item_name}}</option>
                                        
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                    
                    <div class="col-md-1 m-t-30">
                        <button class="btn btn-info px-4" id="reset_rstockqty"onclick='reset_reserveqty()'>Reset Reserve Qty</button>
                    </div>
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
                                <th>No.</th>
                                <!--<th></th>-->
                                <th>Item Code</th>
                                <th>@lang('lang.item') @lang('lang.name')</th>
                                <th>SKU Code</th>
                                <th>@lang('lang.current') @lang('lang.quantity')</th>
                                <th>Reserve @lang('lang.quantity')</th>
                                <th>Purchase Price(MMK)</th>
                                @if(session()->get('user')->role == "Owner")
                                <th>@lang('lang.action')</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="units_table">
                            @php
                            $jj=1;
                            @endphp
                          
                                @foreach ($items as $unit)
                                <tr>
                                    <td>{{$jj++}}</td>
                                    <!--<td>-->

                                    <!--    <div class="col-6 form-check form-switch">-->
                                    <!--        <input class="form-check-input" name="assign_check" type="checkbox" value="{{$unit->id}}" id="assign_check{{$unit->id}}">-->
                                    <!--        <label class="form-check-label" for="assign_check{{$unit->id}}"></label>-->
                                    <!--    </div>-->

                                    <!--</td>-->
                                    <td>{{$unit->item_code}}</td>
                                    <td>{{$unit->item_name}}</td>
                                    <td>{{$unit->sku_code}}</td>
                               
                                    
                                    <td>
                                        <input type="number" class="form-control w-50 stockinput text-black" data-stockinputid="stockinput{{$unit->id}}" id="stockinput{{$unit->id}}" data-id="{{$unit->id}}"value="{{$unit->stock}}">
                                    </td>
                                    
                                    <td>
                                        <input type="number" class="form-control w-50 rstockinput text-black" data-rstockinputid="rstockinput{{$unit->id}}" id="rstockinput{{$unit->id}}" data-id="{{$unit->id}}"value="{{$unit->reserve_qty}}">
                                    </td>
                                    
                                    <td>
                                        <input type="number" class="form-control w-50 priceinput text-black" data-priceinputid="priceinput{{$unit->id}}" id="priceinput{{$unit->id}}" data-id="{{$unit->id}}"value="{{$unit->purchase_price}}">
                                    </td>
                                    
                                    
                               
                                    <!--<td>{{$unit->reserve_qty}}</td>-->
                                    @if(session()->get('user')->role == "Owner")
                                    <td> 
                                        <div class="row">
                                            <a href="#" class="btn btn-warning unitupdate" 
                                            data-unitid="{{$unit->id}}" data-code="{{$unit->item_code}}" data-unitname="{{$unit->item_name}}"
                                                >                      
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger delete_stock" data-id="{{$unit->id}}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                   
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                       

                            <div class="modal fade" id="edit_unit_qty" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Update Item @lang('lang.form')</h4>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                        </div>

                                        <div class="modal-body">
                                            <form class="form-horizontal m-t-40" method="post" action="{{route('update_stock_count')}}">
                                                @csrf
                                                <input type="hidden" name="unit_id" id="unit_id">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-6 text-black">Code </label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" id="unique_item_code" name="item_code"> 
                                                        
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-6 text-black">ပစ္စည်း အမည်</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="unit_name" id="unique_unit_name"> 
                                                        
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
    
    function reset_reserveqty(){
        swal({
                            title: "Are you sure?",
                            text: "The reserve quantity of all items will be set to zero",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            cancelButtonText: "No",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }).then(
                            function(isConfirm) {
                                if (isConfirm) {
                                    $.ajax({

                type:'POST',

                url:'{{route('reserve_stockreset-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Reserve Quantity of all items reset to zero!",
                            button:false,
                            timer:500,
                            icon:"success"  
                        });
                        
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500  
                        });
                       
                    }
                },
                });
                                   

                                } else {

                                   
                                }
                            });
    }

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
                        if(stock.from_id==shop_id){
                             stockcountt= unit.stockcount[k].stock_qty;
                        }
                    })
                    html += `
                    <tr>
                                    <td>${unit.unit_code}</td>
                                    <td>${unit.unit_name}</td>
                                    <td>
                                        <input type="number" class="form-control w-25 stockinput text-black" data-stockinputid="stockinput${unit.id}" id="stockinput${unit.id}" data-id="${unit.id}" value="${stockcountt}">
                                        </td>
                                    <td>${unit.reorder_quantity}</td>
                                    <td> 
                                        <div class="row">
                                            <a href="#" class="btn btn-warning unitupdate" 
                                            data-unitid="${unit.id}" data-code="${unit.unit_code}" data-unitname="${unit.unit_name}"

                                            >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger delete_stock" data-id="${unit.id}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    
                                    </td>
                                </tr>
                    `;
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
    $('.delete_stock').click(function(){
        var id = $(this).data('id');
        // var idArray= [];
        // $("input:checkbox[name=assign_check]:checked").each(function(){
        // idArray.push(parseInt($(this).val()));
        // });
        // if(idArray.length >0){
        //     var unit_ids = idArray;
        //     var multi_delete = 1;
        // }else{
            var unit_ids = id;
            var multi_delete = 0;
        //}
        $.ajax({

            type:'POST',

            url:'{{route('delete_units')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "unit_ids": unit_ids,
                "multi_delete":multi_delete
            },

            success:function(data){
                swal({
                    title: "@lang('lang.success')!",
                    text : "@lang('lang.successfully_deleted')!",
                    icon : "success",
                        });

                setTimeout(function(){
                window.location.reload();
            }, 1000);
                
            },
            });
    })
    $('#item_list').change(function(){

        let item_id = $('#item_list').val();

        $('#units_table').empty();

        $.ajax({

            type:'POST',

            url:'{{route('AjaxGetCountingUnit')}}',

            data:{
                "_token":"{{csrf_token()}}",
                "item_id": item_id,
            },

            success:function(data){
                    var value = data;
                    let button = `
                    <div class="row">
                        <a  href="#" class="btn btn-warning unitupdate" 
                        
                        data-unitid="${value.id}" data-code="${value.item_code}" data-unitname="${value.item_name}"

                        >Edit</a>
                        <button class="btn btn-danger delete_stock" data-id="${value.id}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                        </div>
                    
                    `;
                    
                    let inputstock = `<input type="number" class="form-control w-25 stockinput text-black" data-stockinputid="stockinput${value.id}" id="stockinput${value.id}" data-id="${value.id}" value="${value.stock}">`;
                    
                    let reservestock = `<input type="number" class="form-control w-50 rstockinput text-black" data-rstockinputid="rstockinput${value.id}" id="rstockinput${value.id}" data-id="${value.id}" value="${value.reserve_qty}">`;
                    
                    let purchaseprice = `<input type="number" class="form-control w-50 priceinput text-black" data-priceinputid="priceinput${value.id}" id="priceinput${value.id}" data-id="${value.id}}"value="${value.purchase_price}">`;
                    // if(isowner == "Owner"){
                        $('#units_table').append($('<tr>')).append($('<td>').text(1)).append($('<td>').text(value.item_code)).append($('<td>').text(value.item_name)).append($('<td>').text(value.sku_code)).append($('<td>').append(inputstock)).append($('<td>').append(reservestock)).append($('<td>').append(purchaseprice)).append($('<td>').append($(button)));
                    // }
                    // else{
                    //     $('#units_table').append($('<tr>')).append($('<td>').text(value.item.category.category_name)).append($('<td>').text(value.item.item_name)).append($('<td>').text(value.unit_name)).append($('<td>').append(stockcountt)).append($('<td>').append(value.reorder_quantity));
                    // }
         


                
            },
        });

    })
    
        $('.row').on('click','.unitupdate',function(){
              event.preventDefault()
        var id = $(this).data('unitid');
        var code = $(this).data('code');
        var name = $(this).data('unitname');
        console.log(id,code,name);
        $("#unit_id").val(id);   
        $("#unique_item_code").val(code);   
        $("#unique_unit_name").val(name);   
        $("#edit_unit_qty").modal("show");  
        })
    
  
    
    
    $('#units_table').on('keypress','.stockinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){
            // var shop_id = $('#shop_id option:selected').val();
            var stock_qty = $(this).val();
            var item_id= $(this).data('id');
            var stockinputid = $(this).data('stockinputid');
            $.ajax({

                type:'POST',

                url:'{{route('stockupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "stock_qty": stock_qty,
                    "item_id":item_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Stock Changed!",
                            button:false,
                            timer:500,
                            icon:"success"  
                        });
                        $(`#${stockinputid}`).addClass("is-valid");
                        $(`#${stockinputid}`).blur();
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500  
                        });
                        $(`#${stockinputid}`).addClass("is-invalid");
                    }
                },
                });
        }
    })
    
    $('#units_table').on('keypress','.rstockinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){
            // var shop_id = $('#shop_id option:selected').val();
            var reserve_qty = $(this).val();
            var item_id= $(this).data('id');
            var rstockinputid = $(this).data('rstockinputid');
            $.ajax({

                type:'POST',

                url:'{{route('reserve_stockupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "reserve_qty": reserve_qty,
                    "item_id":item_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Reserve Stock Changed!",
                            button:false,
                            timer:500,
                            icon:"success"  
                        });
                        $(`#${rstockinputid}`).addClass("is-valid");
                        $(`#${rstockinputid}`).blur();
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500  
                        });
                        $(`#${rstockinputid}`).addClass("is-invalid");
                    }
                },
                });
        }
    })
    
    $('#units_table').on('keypress','.priceinput',function(){
        var keycode= (event.keyCode ? event.keyCode : event.which);
        if(keycode=='13'){
            // var shop_id = $('#shop_id option:selected').val();
            var purchase_price = $(this).val();
            var item_id= $(this).data('id');
            var priceinputid = $(this).data('priceinputid');
            $.ajax({

                type:'POST',

                url:'{{route('priceupdate-ajax')}}',

                data:{
                    "_token":"{{csrf_token()}}",
                    "purchase_price": purchase_price,
                    "item_id":item_id
                },

                success:function(data){
                    if(data){
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Success",
                            text:"Price Changed!",
                            button:false,
                            timer:500,
                            icon:"success"  
                        });
                        $(`#${priceinputid}`).addClass("is-valid");
                        $(`#${priceinputid}`).blur();
                    }
                    else{
                        swal({
                            toast:true,
                            position:'top-end',
                            title:"Error",
                            button:false,
                            timer:1500  
                        });
                        $(`#${priceinputid}`).addClass("is-invalid");
                    }
                },
                });
        }
    })
  

</script>
@endsection