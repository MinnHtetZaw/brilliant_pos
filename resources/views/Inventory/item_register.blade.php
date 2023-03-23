@extends('master')

@section('title','Item Register')

@section('place')


@endsection

@section('content')

<div class="container">
      <div class="card">
         <div class="card-body">
             {{-- <h3 class="text-center" style="font-style: italic;" class="badge badge-warning">Item Register Form</h3>
              --}}
              <h2 class="text-center pb-5" style="font-style: italic;">Item Register Form</h2>
            <form  method="post" action="{{route('item_store')}}" enctype='multipart/form-data'>
                @csrf
                <div class="row mt-3 offset-md-1 mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Item Type</h5>
                            </div>
                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="2">
                                    <label class="form-check-label" for="inlineRadio1">Instock</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="3">
                                    <label class="form-check-label" for="inlineRadio2">Preorder</label>
                                  </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold"> Category</h5>
                            </div>
                            <div class="col-md-7">
                                <select name="category" id="" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                                    <option value="">&nbsp; &nbsp;&nbsp;Choose &nbsp; &nbsp;Category</option>
                                    @foreach ($categories as $c)
                                    <option value="{{$c->id}}">{{$c->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>
                    <!--<div class="col-md-6 mt-3">-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-md-5">-->
                    <!--            <label for="font-weight-bold">-->
                    <!--                Sub Category-->
                    <!--            </label>-->
                    <!--        </div>-->
                    <!--        <div class="col-md-7">-->
                    <!--            <select name="sub_category" id="" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">-->
                    <!--                <option value="">Choose Sub Category</option>-->
                    <!--                @foreach ($sub_categories as $s)-->
                    <!--                <option value="{{$s->id}}">{{$s->name}}</option>-->
                    <!--                @endforeach-->
                    <!--            </select>-->
                    <!--        </div>-->
                    <!--    </div>-->


                    <!--</div>-->
                    
                    
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Item Name</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="item_name" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>

                <div class="row mt-3 offset-md-1 mt-3">
                    <!--<div class="col-md-6">-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-md-5">-->
                    <!--            <h5 for="font-weight-bold">Item Name</h5>-->
                    <!--        </div>-->
                    <!--        <div class="col-md-7">-->
                    <!--            <input type="text" name="item_name" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">-->
                    <!--        </div>-->
                    <!--    </div>-->


                    <!--</div>-->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="font-weight-bold">Item Code</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="item_code" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>
                    
                     <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">SKU Code</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="sku_code" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>
                <!--<div class="row mt-3 offset-md-1 mt-3">-->
                <!--    <div class="col-md-6">-->
                <!--        <div class="row">-->
                <!--            <div class="col-md-5">-->
                <!--                <h5 for="font-weight-bold">SKU Code</h5>-->
                <!--            </div>-->
                <!--            <div class="col-md-7">-->
                <!--                <input type="text" name="sku_code" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">-->
                <!--            </div>-->
                <!--        </div>-->


                <!--    </div>-->
                <!--    <div class="col-md-6">-->
                <!--        <div class="row">-->
                <!--            <div class="col-md-5">-->
                <!--                <label for="font-weight-bold">Original Code</label>-->
                <!--            </div>-->
                <!--            <div class="col-md-7">-->
                <!--                <input type="text" name="original_code" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">-->
                <!--            </div>-->
                <!--        </div>-->


                <!--    </div>-->

                <!--</div>-->
                <div class="row mt-3 offset-md-1 mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Selling Price</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="number" name="selling_price" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="font-weight-bold">Stock</label>
                            </div>
                            <div class="col-md-7">
                                <input type="number" name="stock" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>
                <div class="row mt-3 offset-md-1 mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Brand Name</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="brand_name" value="Shein" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Purchase Price</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="purchase_price" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>
                
                <div class="row mt-3 offset-md-1 mt-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Description</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="Description" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Model/Size</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="model_size" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>
                
                <div class="row offset-5 mt-3 d-none">
                    {{-- <div class="col-md-6"> --}}
                    <div class="switch" id="tog">

                      </div>



                {{-- </div> --}}
                </div>
                <div class="row mt-3 offset-md-1 mt-3 d-none" id="show_web">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Photo</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="file" name="photo">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-5">
                                <h5 for="font-weight-bold">Description</h5>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="web_description" style="border-radius: 7px;border-color:#a7adb3;" class="border p-2">
                            </div>
                        </div>


                    </div>

                </div>
                <div class="row mt-5 offset-5">
                    <button type="submit" class="btn btn-success">Save</button>

                    <a href="{{route('item_list','instock')}}" class="btn btn-danger ml-3">Cancel</a>
                </div>

            </form>
         </div>
      </div>
</div>

@endsection

@section('js')
<script>
   $( document ).ready(function() {
    // $('#chek').attr('checked',false);
    $('#show_web').hide();
    var htmltog = "";
    htmltog += `
                <label>
                    <h5>Show On Website
                        <input type="checkbox" >
                        <span class="lever" id="toggle-two"  onclick="show_website()"></span>
                    </h5>
                </label>
                `;
    $('#tog').html(htmltog);
});
function show_website(){
    // alert(val);
    $('#show_web').show();
    var htmltog1 = "";
    htmltog1 += `
                <label>
                    <h5>Show On Website
                        <input type="checkbox" checked>
                        <span class="lever" id="toggle-two"  onclick="show_website1()"></span>
                    </h5>
                </label>
                `;
    $('#tog').html(htmltog1);



};

function show_website1(){
    // alert(val);
    $('#show_web').hide();
    var htmltog = "";
    htmltog += `
                <label>
                    <h5>Show On Website
                        <input type="checkbox" >
                        <span class="lever" id="toggle-two"  onclick="show_website()"></span>
                    </h5>
                </label>
                `;
    $('#tog').html(htmltog);
}


</script>
@endsection
