<!DOCTYPE html>
<html lang="en-US" dir="ltr">






    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- ===============================================-->
        <!--    Document Title-->
        <!-- ===============================================-->

        <title>Brilliant</title>



        <!-- ===============================================-->
        <!--    Favicons-->
        <!-- ===============================================-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('frontend/assets/img/favicons/apple-touch-icon.png')}}">

        {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{asset('frontend/assets/img/favicons/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('frontend/assets/img/favicons/favicon-16x16.png')}}">
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend/assets/img/favicons/favicon.ico')}}"> --}}
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend/assets/img/logo1update.jpg')}}">

        <link rel="manifest" href="{{asset('frontend/assets/img/favicons/manifest.json')}}">
        <meta name="msapplication-TileImage" content="{{asset('frontend/assets/img/favicons/mstile-150x150.png')}}">
        <meta name="theme-color" content="#ffffff">


        <!-- ===============================================-->
        <!--    Stylesheets-->
        <!-- ===============================================-->
        <link href="{{asset('frontend/assets/css/theme.css')}}" rel="stylesheet" />
        <link href="{{asset('frontend/assets/css/style.css')}}" rel="stylesheet" />
        {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}"> --}}
        {{-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/css/bootstrap.min.css'> --}}
        <!-- Font Awesome CSS -->
        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.3.1/css/all.css'>

    <style>
        /* .preloader{
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('../../../public/image/Profile/loader.gif') 50%50% no-repeat rgb(249, 249, 249);
            /* background: url('../../image/Profile/loader.gif') 50%50% no-repeat rgb(249, 249, 249); */
            /* opacity: 0.9;
        } */ */
        /* .plaintext {
            outline:0;
            border-width:0 0 1px;
        } */
    </style>
    <title>@yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"> --}}



</head>


<body>
    <div class="preloader" id="preloaders"></div>

    @include('sweet::alert')
   {{-- sidebar --}}

   <main class="main" id="top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" data-navbar-on-scroll="data-navbar-on-scroll">
      <div class="container"><a class="navbar-brand" href="#">
          {{-- <img src="{{asset('frontend/assets/img/logo10.jpg')}}" alt="" width="200"/> --}}
          <h1 class="font-weight-bold" style="color: #fff">Brilliant</h1>
          {{-- <span class="text-1000 fs-1 ms-2 fw-medium"></span> --}}
        </a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mx-auto border-bottom border-lg-bottom-0 pt-2 pt-lg-0">

            <li class="nav-item px-4"><a class="nav-link" href="#about"><span style="color:#fff;font-size:17px;">About</span></a></li>
            <li class="nav-item px-4"><a class="nav-link" href="#features"><span style="color:#fff;font-size:17px;">Features</span></a></li>
            <li class="nav-item px-4"><a class="nav-link" href="#plan"><span style="color:#fff;font-size:17px;">Pricing</span> </a></li>
            <li class="nav-item px-4"><a class="nav-link" href="#test"><span style="color:#fff;font-size:17px;">Network</span></a></li>
            <li class="nav-item px-4"><a class="nav-link" href="#help"><span style="color:#fff;font-size:17px;">Contact Us </span></a></li>

          </ul>
          {{-- <form class="d-flex py-3 py-lg-0"> --}}
            {{-- <button class="btn btn-link text-1000 fw-medium order-1 order-lg-0" type="button">Loi in</button> --}}
            {{-- <a href="" class="btn outline rounded-pill order-0" data-toggle="modal" data-target="#track">Track Your Parcel</a> --}}

            <a href="" class="btn btn-outline-warning rounded-pill order-0" style="color: #fff">Track Your Parcel</a>


            {{-- <a href=""><button type="button" class="btn outline rounded-pill order-0" data-toggle="modal"  data-target="#homee" onclick="show()">
                Login
            </button></a> --}}



            {{-- <button class="btn btn-outline-danger rounded-pill order-0" type="submit">Sign Up</button> --}}


          {{-- </form> --}}
        </div>
      </div>
    </nav>
    <div class="modal fade trackmo" id="track" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"  style="color:#176cdb">Track</h5>
              <button type="button" class="close border-0" style="background-color:red;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center" style="color:#ae17db">Track Your Parcel</h5>
                <div class="container mt-3">
                <form class="text-center">
                    @csrf
                    <label class="radio-inline"  style="color:#176cdb">
                      <input type="radio" name="optradio" checked>Track Id
                    </label>
                    <label class="radio-inline"  style="color:#176cdb">
                      <input type="radio" name="optradio">Phone
                    </label>
                    <input type="text" name="trac_ph" class="border border-primary"><br>

                </form>
                </div>
            </div>
            <div class="modal-footer text-center">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchh" onclick="hidee()">Search</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade searchmo" id="searchh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel" style="color:#176cdb">Detail Infomation</h5>
              <button type="button" class="close border-0" style="background-color:red;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <div class="container">
                  <div class="row">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Track Id</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="track_id"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Customer Name</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="customer_name"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Customer Phone</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="customer_ph"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Paecel Quantity</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="parcel_qty"  class="border border-primary">
                    </div>
                  </div>
                  <hr>
                  <h5><span  style="background-color:#ae17db;color:#fff;border-radius:7px;" class="pb-1">&nbsp;&nbsp;Receive&nbsp;&nbsp;</span></h5>
                  <div class="row">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Receive Point</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="receive_point"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Receive Status</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="receive_status"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Final Receive Date</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="date" name="receive_date"  class="border border-primary">
                    </div>
                  </div>
                  <hr>
                  <h5><span  style="background-color:#ae17db;color:#fff;border-radius:7px;" class="pb-1">&nbsp;&nbsp;Drop Off&nbsp;&nbsp;</span></h5>
                  <div class="row">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Drop Off Point</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="dropoff_point"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Drop Off Status</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="dropkoff_status"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Final Drop Off Date</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="date" name="dropoff_date"  class="border border-primary">
                    </div>
                  </div>
                  <hr>
                <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb" class="ml-4">Rider Name</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="rider_name"  class="border border-primary">
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="offset-md-1 col-md-5">
                        <h5 style="color:#175cdb">Rider Phone</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="rider_ph"  class="border border-primary">
                    </div>
                  </div>
                <div class="row mt-4 mb-3">
                    <div class="offset-md-3 col-md-2 mr-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-md-1">

                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary">Contact</button>
                    </div>
              {{-- <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-sm btn-primary">Save changes</button> --}}
               </div>

             </div>
            </div>


          </div>
        </div>
      </div>

    @yield('content')

   </main>

    <script src="{{asset('frontend/vendors/@popperjs/popper.min.js')}}"></script>
    <script src="{{asset('frontend/vendors/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('frontend/vendors/is/is.min.js')}}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="{{asset('frontend/assets/js/theme.js')}}"></script>

    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="{{asset('../assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('../assets/js/popper.min.js')}}"></script>
    <script src="{{asset('../assets/js/app.js')}}"></script>

    <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
<!-- Popper JS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
<!-- Bootstrap JS -->
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>

    {{-- <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet"> --}}
    @yield('js')



</body>

</html>



<script>

// $( document ).ready(function() {
//     $("#searchh").hide();
// });

function hidee(){
    // alert('hello');
    // $('.trackmo').modal("hide");
    // $('#searchh').modal("hide");
    // alert('hello');
    // $(".trackmo").hide();
    // $("#searchh").show();
    // $('#searchh').modal('show');
}

// function back(){
//     alert('back');
//     $('#top').show();
// }


</script>







<!-- Modal -->
{{-- <div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div> --}}




