@extends('master')

@section('title','Sale Page')

@section('place')
  
@endsection
@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h4 class="text-themecolor m-b-0 m-t-0">Sale Customer Lists</h4>
               
            </div>
        </div>
        <section id="plan-features">
            <div class="container">
                <div class="card">
                    <div class="card-body shadow">
                        <div class="tab-content br-n pn">
                            <div id="navpills-1" class="tab-pane active">
                            <table class="table table-striped text-black">
                                <thead>
                                <tr>
                                <th>No</th>
                                    <th>id</th>
                                    <th>Name</th>
                                    
                                    <th>Phone</th>
                                    <th>Credit Amount</th>
                                    <th>Detail</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $j=1;
                                ?>
                                @foreach($sale_customer as $list)
                                
                                <tr>
                                    <td>{{$j++}}</td>
                                <td>{{$list->id}}</td>
                                <td>{{$list->name}}</td>
                                <td>{{$list->phone}}</td>
                                <td>{{$list->credit_amount}}</td>
                                <td><a href="{{route('credit',$list['id'])}}"><button type="button" class="btn btn-primary">Credit Detail</button></a></td>

                                </tr>
       
                                @endforeach
                                </tbody>
                            </table>
                            @endsection


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
</div>











 

