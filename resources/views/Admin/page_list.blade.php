@extends('master')

@section('title','Page List')

@section('place')

{{-- <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">@lang('lang.branch')</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('index')}}">@lang('lang.back_to_dashboard')</a></li>
        <li class="breadcrumb-item active">@lang('lang.category') @lang('lang.list')</li>
    </ol>
</div> --}}

@endsection

@section('content')

<div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
        <h4 class="font-weight-normal">@lang('lang.page') @lang('lang.list')</h4>
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
                                <th>@lang('lang.page') @lang('lang.code')</th>
                                <th>@lang('lang.page') @lang('lang.name')</th>
                                <th>Employee Name</th>
                                <th>Logo</th>
                                <th class="text-center">@lang('lang.action')</th>
                            </tr>
                        </thead>
                        <?php $i=1 ?>
                         <tbody>
                             @foreach ($pages as $p)
                                 <tr>
                                     <td>{{$i++}}</td>
                                     <td>{{$p->code}}</td>
                                     <td>{{$p->name}}</td>
                                     <td>{{$p->employee->user->name ?? ""}}</td>
                                     <td><img width="80" src="{{asset($p->logo)}}" alt="logo"></td>
                                     <td class="text-center">
                                        <a href="#" class="btn btn-outline-warning" data-toggle="modal" data-target="#edit_page{{$p->id}}" onclick="update_page({{$p->id}})">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{route('delete_page',$p->id)}}" class="btn btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i></a>
                                    </td>
                                 </tr>
                                 <div class="modal fade" id="edit_page{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Page Form</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-material m-t-40" method="post" action="{{route('store_update_page')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="page_id" value="{{$p->id}}">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">@lang('lang.page')@lang('lang.code')</label>
                                                        <input type="number" value="{{$p->code}}" name="page_code" class="form-control">
                            
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">@lang('lang.page')@lang('lang.name')</label>
                                                        <input type="text" value="{{$p->name}}" name="page_name" class="form-control">
                            
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="" class="font-weight-bold">Employee Name</label>
                                                        <select name="employee_id" id="" class="form-control">
                                                            <option value="">Choose Employee</option>
                                                            @foreach ($users as $user)
                                                            <option 
                                                            
                                                            value="{{$user->id}}">{{$user->name}}</option>
                                                            @endforeach
                                
                                                        </select>
                            
                                                    </div>
                                                    <div class="">
                                                        <label class="font-weight-bold">Logo</label>
                                                        <input type="file" name="logo" class="" placeholder="Choose logo to update">
                            
                                                    </div>
                            
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                             @endforeach
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Update --}}


    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title">Create Page Form</h3>
                <form class="form-material m-t-40" method="post" enctype="multipart/form-data" action="{{route('store_page')}}">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold">@lang('lang.page')@lang('lang.code')</label>
                        <input type="number" name="page_code" class="form-control" placeholder="Enter Page Code" required>

                        @error('category_code')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">@lang('lang.page')@lang('lang.name')</label>
                        <input type="text" name="page_name" class="form-control" placeholder="Enter Page Name" required>

                        @error('category_name')
                            <span class="invalid-feedback alert alert-danger" role="alert"  height="100">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label for="" class="font-weight-bold">Employee Name</label>
                        <select name="employee_id" id="" class="form-control">
                            <option value="">Choose Employee</option>
                            @foreach ($users as $u)
                            <option value="{{$u->id}}">{{$u->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="logo" class="font-weight-bold">Logo</label>
                        <input type="file" name="logo" class="form-control" placeholder="Choose Logo">
                    </div>
                    <input type="submit" name="btnsubmit" class="btnsubmit float-right btn btn-primary" value="Save Page">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>


</script>
@endsection
