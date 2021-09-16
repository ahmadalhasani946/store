<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/star.css') }}" rel="stylesheet">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor2/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--

    TemplateMo 546 Sixteen Clothing

    https://templatemo.com/tm-546-sixteen-clothing

    -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets2/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/css/templatemo-sixteen.css') }} ">
    <link rel="stylesheet" href="{{ asset('assets2/css/owl.css') }}">
    <style>
        img:hover{
            opacity: 0.8;
            transform: scale(0.9);
        }

        .dropdown2 {
            position: relative;
            display: inline-block;
        }

        .dropdown2-content {
            display: none;
            position: absolute;
            background-color: #212529;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .dropdown2:hover .dropdown2-content {
            display: block;
        }


        .background-List {
            color: red;
            background: whitesmoke;
        }

        .avatar{
            height: 50px;
            width: 50px;
            border-radius: 50%;
        }

        #OptionList{
            top: 50px;
        }
    </style>
</head>

<body>

<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- ***** Preloader End ***** -->
@php
    $url = url()->current();
    $home = "active";
    $products = "";
    $about = "";
    $contact = "";
    $product = "";

    if(str_contains($url,'home')){
        $home = 'active';
    }
    if(str_contains($url,'products')){
        $products = 'active';
        $home = "";
    }
    if(str_contains($url,'about')){
        $about = 'active';
        $home = "";
    }
    if(str_contains($url,'contact')){
        $contact = 'active';
        $home = "";
    }
    if(str_contains($url,'product')){
        $home = "";
    }
@endphp
<!-- Header -->
<header class="">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><h2>Ahmad <em>Store</em></h2></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ $home }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $products }}" href="{{ route('products') }}">Our Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $about }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $contact }}" href="{{ route('contact') }}">Contact Us</a>
                    </li>
                    <li style="margin-left:100px;float:right;" class="nav-item">
                        @guest()
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                <div class="dropdown-divider"></div>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            </div>
                        @else
                            <div class="flex">
                                <div class="cart" style="float:left;font-size: 20px;margin-top: 10px;margin-right: 10px">
                                    <a class="cart-button responsive-1" style="color:#f33f3f;float:left;" href="{{ route('basket') }}">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span style="color:#f33f3f;float:right;margin-left: 10px" id="cart_count" class="item-number ">0</span>
                                    </a>

                                </div>
                                <div class="dropdown2">
                                    <img style="float:right" class="avatar" src="{{ auth()->user()->ImagePath }}">
                                    <a style="float:left" class="nav-link">{{ auth()->user()->name }}</a>
                                    <div id="OptionList" class="dropdown2-content">
                                        <ul>
                                            <li>
                                                <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Page Content -->
<!-- Banner Starts Here -->
@yield('banner')
<!-- Banner Ends Here -->

<div class="latest-products">
    <div class="container">
        @if(session('message'))
            <div class="alert alert-success manipulated" style="text-align:center;">
                {{ session('message') }}
            </div>
        @endif
        @yield('content')
    </div>
</div>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner-content">
                    <p>Copyright &copy; 2020 Sixteen Clothing Co., Ltd.

                        - Design: <a rel="nofollow noopener" href="https://templatemo.com" target="_blank">TemplateMo</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
@if(auth()->user())
    <div hidden id="CurrentUser">{{ auth()->user()->id }}</div>
    <div hidden id="countOfProducts">{{ route('countOfProducts') }}</div>
@else
    <div hidden id="CurrentUser"></div>
    <div hidden id="countOfProducts"></div>
@endif



<!-- Bootstrap core JavaScript -->
<script src="{{ asset('vendor2/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor2/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
    $(document).ready(function () {
        if(document.getElementById("CurrentUser").innerHTML != ''){
            $.ajax({
                url: "{{ route('countOfProducts') }}",
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    if(response){
                        document.getElementById('cart_count').innerHTML = response.basketProducts;
                    }
                }
            });
        }
    });
</script>

<!-- Additional Scripts -->
<script src="{{ asset('assets2/js/custom.js') }}"></script>
<script src="{{ asset('assets2/js/owl.js') }}"></script>
<script src="{{ asset('assets2/js/slick.js') }}"></script>
<script src="{{ asset('assets2/js/isotope.js') }}"></script>
<script src="{{ asset('assets2/js/accordions.js') }}"></script>

<script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script language = "text/Javascript">
    cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
    function clearField(t){                   //declaring the array outside of the
        if(! cleared[t.id]){                      // function makes it static and global
            cleared[t.id] = 1;  // you could use true and false, but that's more typing
            t.value='';         // with more chance of typos
            t.style.color='#fff';
        }
    }


</script>

</body>

</html>
