@extends('master')

@section('title','SubCategory List')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.branch')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.subcategory') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">        
        <h2 class="font-weight-bold">@lang('lang.subcategory') @lang('lang.list')</h2>
    </div>
</div>


<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive text-black">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('lang.subcategory') @lang('lang.code')</th>
                                <th>@lang('lang.related_category')</th>
                                <th>@lang('lang.subcategory') @lang('lang.name')</th>
                                <th class="text-center">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1;?>
                            @foreach($sub_categories as $sub_category)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$sub_category->subcategory_code}}</td>
                                <td>{{$sub_category->category->category_name ?? null}}</td>
                                <td>{{$sub_category->name}}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_subcategory{{$sub_category->id}}"><i class="far fa-edit"></i>
                                    </a>

                                    <a href="#" class="btn btn-outline-danger" onclick="ApproveLeave('{{$sub_category->id}}')">
                                        <i class="fas fa-trash-alt"></i></a>
                                </td>
                                
                                <div class="modal fade" role="dialog" id="edit_subcategory{{$sub_category->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title">@lang('lang.edit_subcategory_form')</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>

                                    <div class="modal-body">
                                        <form class="form-material m-t-40" method="post" action="{{route('sub_category_update', $sub_category->id)}}">
                                            @csrf
                                            <div class="form-group">    
                                                <label class="font-weight-bold">@lang('lang.code')</label>
                                                <input type="number" name="sub_category_code" class="form-control" value="{{$sub_category->subcategory_code}}"> 
                                            </div>
                                            <div class="form-group">    
                                                <label class="font-weight-bold">@lang('lang.name')</label>
                                                <input type="text" name="sub_category_name" class="form-control" value="{{$sub_category->name}}"> 
                                            </div>
                                            <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save')">
                                        </form>           
                                    </div>
                               
                              </div>
                                    </div>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title">@lang('lang.create_subcategory_form')</h3>
                <form class="form-material m-t-40" method="post" action="{{route('sub_category_store')}}">
                    @csrf
                    <div class="form-group">    
                        <label class="font-weight-bold">@lang('lang.code')</label>
                        <input type="number" name="sub_category_code" class="form-control @error('category_code') is-invalid @enderror" placeholder="@lang('lang.enter_subcategory_code')" required>

                        @error('sub_category_code')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                    
                    <div class="form-group">    
                        <label class="font-weight-bold">@lang('lang.category')</label>
                        <select class="form-control" name="category" required>
                            <option value="">@lang('lang.select')</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>

                        @error('sub_category_code')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                    
                    <div class="form-group">    
                        <label class="font-weight-bold">@lang('lang.name')</label>
                        <input type="text" name="sub_category_name" class="form-control @error('sub_category_name') is-invalid @enderror" placeholder="@lang('lang.enter_subcategory_name')" required>

                        @error('sub_category_name')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror 

                    </div>
                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="@lang('lang.save_subcategory')">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>

    function ApproveLeave(value){

        var subcategory_id = value;

        swal({
            title: "@lang('lang.confirm')",
            icon:'warning',
            buttons: ["@lang('lang.no')", "@lang('lang.yes')"]
        })

      .then((isConfirm)=>{

        if(isConfirm){

            $.ajax({
                type:'POST',
                url:'subcategory/delete',
                dataType:'json',
                data:{
                  "_token": "{{ csrf_token() }}",
                  "subcategory_id": subcategory_id,
                },

                success: function(){

                    swal({
                        title: "Success!",
                        text : "Successfully Deleted!",
                        icon : "success",
                    });

                    setTimeout(function(){window.location.reload()}, 1000);


                },
            });
        }
      });


    }

</script>
@endsection

