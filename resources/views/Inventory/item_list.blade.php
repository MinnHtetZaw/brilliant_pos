@extends('master')

@section('title','Item List')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.branch')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.item') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')

{{-- <div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">@lang('lang.item') @lang('lang.list')</h4>
    </div>
</div> --}}
<form action="{{ route('item_import') }}" method="POST" enctype="multipart/form-data" id="excelitem">
    @csrf

        <input type="file" id="itemfile" name="file" class="form-control input-sm mr-8 bg-info d-none">


    </form>


<div class="row">
    <div class="col-md-12">

            <div class="card-body">

                            <div class="clearfix"></div>

                                <div class="row">



                                    <div class="col-md-12">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-9">

                                                    </div>

                                                    <div class="col-md-3">
                                                        <button class="btn btn-outline-primary ml-5" onclick="excel_import()">
                                                        Import
                                                        </button>
                                                        <a href="{{route('item_register')}}" class="btn btn-outline-primary ml-4">
                                                            <i class="fas fa-plus"></i>
                                                            @lang('lang.add_item')
                                                        </a>
                                                        
                                                    </div>

                                                </div>
                                                @if (session()->get('user')->role == 'Owner' || session()->get('user')->role == 'Stock')
                                                <div class="row">
                                                   <div class="col-md-2 m-t-30">
                                                    <select class="form-control" id="item_type">
                                                            <option value="instock"
                                                            @if ($instockOrPreorder == 'instock')
                                                                selected
                                                            @endif
                                                            >Instock</option>
                                                            <option value="preorder"
                                                            @if ($instockOrPreorder == 'preorder')
                                                                selected
                                                            @endif
                                                            >Preorder</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2 m-t-30">
                                                    <select class="form-control" id="category_type" onchange="searchItem(this.value)">
                                                            <option value=0>All Category</option>
                                                    @foreach($categories as $cat)
                        <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                    @endforeach    
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2 m-t-30">
                                                    <button class="btn btn-success" id="print">
                                                        Print
                                                        </button>
                                                </div>
                                                
                                                </div>
                                                @endif
                                          
                                                <h3 class="card-title text-center mt-4" style="font-style: italic;">@lang('lang.item') @lang('lang.list') </h3>


                                                <div class="table-responsive text-black" id="slimtest2">
                                                    <table class="table" id="item_table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th style="overflow:hidden;white-space: nowrap;"> @lang('lang.itemname')</th>
                                                                <th>Item Code</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">SKU Code</th>

                                                                <th style="overflow:hidden;white-space: nowrap;">Stock Quantity</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">Reserve Qty</th>
                                                                <th style="overflow:hidden;white-space: nowrap;" class="px-5">Action</th>


                                                            </tr>
                                                        </thead>
                                                        <br>
                                                        <tbody id="item_table_body">
                                                           <?php $i=1;?>

                                                            @foreach($item_lists as $item)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$item->item_name}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$item->item_code}}</td>
                                                                <td>{{$item->sku_code}}</td>
                                                                <td>{{$item->stock}}</td>
                                                                <td>{{$item->reserve_qty}}</td>
                                                                <!-- <td class="groundqty"></td>
                                                                <td class="groundqty"></td> -->
                                                                <td><a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#org_code">
                                                                    Add Original Code</a>
                                                                    <button data-itemid="{{$item->id}}" class="btn btn-outline-success add_reserve_qty_btn" >
                                                                    Add Reserve Qty</button>
                                                                </td>

                                                            </tr>
                                                            @endforeach
                                                          <input type="hidden" id="alltotal" value="">
                                                        </tbody>
                                                    </table>

                                                <div class="modal fade" id="org_code" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                          <div class="modal-content">
                                                            <div class="modal-header">
                                                              <h5 class="modal-title" id="exampleModalLongTitle">ADD ORIGINAL CODE</h5>
                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                              </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <label class="font-weight-bold">Add Original Code</label>
                                                                <input type="text" name="or_code" class="ml-5 p-2" style="border-radius: 7px;">

                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                              <button type="button" class="btn btn-primary">ADD</button>
                                                            </div>
                                                          </div>
                                                        </div>
                                                </div>
                                                <div class="modal fade" id="add_reserve_qty_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLongTitle">ADD RESERVE QTY</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <form action="{{route('addReserveQty')}}" method="POST" >
                                                            @csrf
                                                            <input type="hidden" id="item_id" name="item_id">
                                                        <div class="modal-body text-center">
                                                            <label class="font-weight-bold">Reserve Qty</label>
                                                            <input type="number" required name="reserve_qty" class="ml-5 p-2" style="border-radius: 7px;">

                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                          <button type="submit" class="btn btn-primary">ADD</button>
                                                        </div>
                                                    </form>

                                                      </div>
                                                    </div>
                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


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
                            <p class="font-weight-bold mt-2" style="font-size: 28px">Instock Inventory Report</p> 
                        </div>
                        <div class="col-md-6 ml-3">
                            <h3 class="mt-2" id="category_name">Category Name   : All</h3> 
                        </div>
                        <div class="col-md-6  ml-3">
                            <h3 class="mt-2" id="report_date">Report Date  :</h3> 
                        </div>
                        <div class="table-responsive text-black">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                                                <th style="overflow:hidden;white-space: nowrap;"> @lang('lang.itemname')</th>
                                                                <th>Item Code</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">SKU Code</th>

                                                                <th style="overflow:hidden;white-space: nowrap;">Stock Quantity</th>
                                                                <th style="overflow:hidden;white-space: nowrap;">Reserve Qty</th>
                                                                
                                    </tr>
                                </thead>
                                <tbody class="text-black" id="printstock_unit_table">
                                    @php
                                    $j = 1;
                                    $total_qty = 0;
                                    $total_value = 0;
                                @endphp
                                    @foreach($item_lists as $item)
                                    @php
                                    $total_qty += $item->stock;
                                    $total_value+= $item->purchase_price
                                    @endphp
                                    @if($item->stock != 0)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$item->item_name}}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">{{$item->item_code}}</td>
                                                                <td>{{$item->sku_code}}</td>
                                                                <td>{{$item->stock}}</td>
                                                                <td>{{$item->reserve_qty}}</td>
                                                                <!-- <td class="groundqty"></td>
                                                                <td class="groundqty"></td> -->
                                                                
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row offset-3">
                    
                            <div class="col-md-2 ml-3 " >
                            
                            <div class="col-md-2 ml-3 " >
                                <p class="font-weight-bold mt-2" style="font-size: 20px;" id="inv_total_qty">Total Quantity:{{$total_qty}}</p> 
                            </div>
                            
                            <div class="col-md-2 ml-3 " >
                                <p class="font-weight-bold mt-2" style="font-size: 20px;" id="inv_total_value">Total Inventory Value:{{$total_value}} MMK</p> 
                            </div>
                            
                            <div class="col-md-2 ml-3 " >
                                <p class="font-weight-bold mt-2" style="font-size: 20px;">CEO Sign:</p> 
                            </div>
                            
                            </div>
                            
                        </div>
                    </div>
            
            
            
        </div>
    </div>

    @endsection

    @section('js')
    <script src="{{asset('js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>

    <script>
        $(document).ready(function() {
            var inventorytotal=$("#alltotal").val();
            console.log(inventorytotal);
            $('#inventorytotal').text(inventorytotal);

                //print button
            $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $('.printableArea').removeClass('d-none');
            $('.printableArea').addClass('d-block');
            $("div.printableArea").printArea(options);
            setInterval(() => {
                $('div.printableArea').addClass('d-none');
                }, 1000);
        });


            $(".select2").select2();

            $('#example23').DataTable({

                "paging": false,
                "ordering": true,
                "info": false,

            });
        });

        function ApproveLeave(value) {

            var item_id = value;

            swal({
                    title: "@lang('lang.confirm')",
                    icon: 'warning',
                    buttons: ["@lang('lang.no')", "@lang('lang.yes')"]
                })

                .then((isConfirm) => {

                    if (isConfirm) {

                        $.ajax({
                            type: 'POST',
                            url: 'item/delete',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "item_id": item_id,
                            },

                            success: function() {

                                swal({
                                    title: "Success!",
                                    text: "Successfully Deleted!",
                                    icon: "success",
                                });

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);


                            },
                        });
                    }
                });
        }

        // function showSubCategory(value) {

        //     var category_id = value;

        //     $('#sub_category').empty();

        //     $.ajax({
        //         type: 'POST',
        //         url: '/showSubCategory',
        //         dataType: 'json',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "category_id": category_id,
        //         },

        //         success: function(data) {

        //             console.log(data);

        //             $.each(data, function(i, value) {

        //                 $('#sub_category').append($('<option>').text(value.name).attr('value', value.id));
        //             });

        //         }

        //     });

        // }

        // function showRelatedSubCategory(value) {

        //     console.log(value);

        //     $('#subcategory').prop("disabled", false);

        //     var category_id = value;

        //     $('#subcategory').empty();

        //     $.ajax({
        //         type: 'POST',
        //         url: '/showSubCategory',
        //         dataType: 'json',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "category_id": category_id,
        //         },

        //         success: function(data) {

        //             console.log(data);

        //             $.each(data, function(i, value) {

        //                 $('#subcategory').append($('<option>').text(value.name).attr('value', value.id));
        //             });

        //         }

        //     });
        // }

        // function showRelatedItemList(value) {

        //     $('#item').empty();

        //     console.log(value);

        //     var sub_category_id = value;

        //     var items = @json($item_lists);

        //     var html = "";

        //     console.log(items);

        //     $.each(items, function(i, v) {

        //         if (v.sub_category_id == sub_category_id) {

        //             var related_category = v.category.category_name;

        //             if (v.sub_category) {

        //                 var related_subcategory = v.sub_category.name;

        //             } else {

        //                 var related_subcategory = "";
        //             }

        //             var url1 = '{{route('count_unit_list',":item_id")}}';

        //             url1 = url1.replace(':item_id', v.id);


        //             var url2 = '{{route('unit_relation_list',":item_id")}}';

        //             url2 = url2.replace(':item_id', v.id);

        //             html += `
        //                 <tr>
        //                     <td>${v.item_code}</td>
        //                     <td>${v.item_name}</td>
        //                     <td>${related_category}</td>
        //                     <td>${related_subcategory}</td>
        //                     <td>
        //                         <a href="${url1}" class="btn btn-outline-info">
        //                         @lang('lang.check')</a>
        //                     </td>
        //                     <td>
        //                         <a href="${url2}" class="btn btn-outline-info">
        //                         @lang('lang.change_unit')</a>
        //                     </td>
        //                     <td class="text-center">
        //                         <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_item${v.id}">
        //                             <i class="fas fa-edit"></i></a>

        //                         <a href="#" class="btn btn-outline-danger" onclick="ApproveLeave('${v.id}')">
        //                     <i class="fas fa-trash-alt"></i></a>
        //                     </td>
        //                 </tr>`

        //             $('#item').html(html);
        //         }

        //     });
        // }

$('#item_table').DataTable( {

"paging":   true,
"ordering": true,
"info":     true

});


// $('#slimtest2').slimScroll({
//         color: '#00f',
//         height: '600px'
//     });
    function excel_import()
  {
      // $( "#excelwayfile" ).addClass( "d-block" );

      $('#itemfile').click();
  }
  $('#itemfile').change(function(){
    $('#excelitem').submit();
  });

  $('.add_reserve_qty_btn').click(function(){
      console.log('clicked');
      var item_id = $(this).data('itemid');
      $('#item_id').val(item_id);
      $('#add_reserve_qty_modal').modal('show');
  })
  $('#item_type').change(function(){
      let item_type = $(this).val();
      let url = `/items/${item_type}`;
      window.location.href = url;
  })
  
  function searchItem(value){

                let category_id = value;
                let category_name = "Category Name :   " + $('#category_type option:selected').text();
                let item_type = 0;
                if($('#item_type').val() == "instock"){
                    item_type = 2;
                }else if($('#item_type').val() == "preorder"){
                    item_type = 3;
                }
                console.log(category_id,item_type,category_name);
                
                var d = new Date();
var strDate = "Report Date: " + d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear() ;
               
                $.ajax({
                    type: 'POST',
                    url: '/item_search',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "category_id" : category_id,
                        "item_type": item_type,
                    },


                    success: function(data) {
                        console.log(data);
                        var html="";
                        var print_html = "";
                        var total_qty = 0;
                        var total_value = 0;
                        $.each(data,function(i,item){
                            total_qty += item.stock;
                            total_value += item.purchase_price;
                            html += `
                    <tr>
                                    <td>${++i}</td>
                                    <td style="overflow:hidden;white-space: nowrap;">${item.item_name}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">${item.item_code}</td>
                                                                <td>${item.sku_code}</td>
                                                                <td>${item.stock}</td>
                                                                <td>${item.reserve_qty}</td>
                                                                
                                                                <td><a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#org_code">
                                                                    Add Original Code</a>
                                                                    <button data-itemid="${item.id}" class="btn btn-outline-success add_reserve_qty_btn" >
                                                                    Add Reserve Qty</button>
                                                                </td>

                                </tr>
                    `;
                    print_html += `
                    <tr>
                                    <td>${++i}</td>
                                    <td style="overflow:hidden;white-space: nowrap;">${item.item_name}</td>
                                                                <td style="overflow:hidden;white-space: nowrap;">${item.item_code}</td>
                                                                <td>${item.sku_code}</td>
                                                                <td>${item.stock}</td>
                                                                <td>${item.reserve_qty}</td>
                                                                
                                </tr>
                    `;
                        })
                        $('#item_table_body').empty();
            $('#item_table_body').html(html);
                    
                        $('#printstock_unit_table').empty();
            $('#printstock_unit_table').html(print_html);
                        
                        $('#category_name').text(category_name);
                        $('#report_date').text(strDate);
                        $('#inv_total_qty').text("Total Quantity: " + total_qty);
                        $('#inv_total_value').text("Total Inventory Value: " + total_value + " MMK");
                    },
                    error: function(status) {
                        console.log(status);
                        swal({
                            title: "Something Wrong!",
                            text: "Error in searching units",
                            icon: "error",
                        });
                    }
                });
            }
  
</script>
 @endsection
