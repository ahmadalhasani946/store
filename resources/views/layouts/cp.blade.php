<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="{{asset('assets/css/font-awesome.css')}}" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="{{asset('assets/js/morris/morris-0.4.3.min.css')}}" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="{{asset('css/cp.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('css/star.css') }}" rel="stylesheet">
    <style>
        table{
            text-align: center;
        }

        th{
            text-align: center;
        }
    </style>
@php
    $url = url()->current();
    $optionClass = "";
    $variantClass = "";
    $categoryClass = "";
    $productClass = "";
    $reviewClass = "";
    $orderClass = "";
    $userClass = "";
    if (str_contains($url,'options')){
        $optionClass = "active-menu";
    }
    else if (str_contains($url,'users')){
        $userClass = "active-menu";
    }
    else if (str_contains($url,'variants')){
        $variantClass = "active-menu";
    }
    else if (str_contains($url,'categories')){
        $categoryClass = "active-menu";
    }
    else if(str_contains($url,'products')){
        $productClass = "active-menu";
    }
    else if(str_contains($url,'reviews')){
        $reviewClass = "active-menu";
    }
    else if(str_contains($url,'orders')){
        $orderClass = "active-menu";
    }
@endphp
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/cp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
                    </svg>  Store Cpanel</a>
            </div>
  <div style="color: white;padding: 15px 50px 5px 50px;float: right;font-size: 16px;">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto" style="list-style: none;color:white;">
                        <!-- Authentication Links -->
                        @guest
                            <div class="dropdown show">
                                <button class="btn btn-secondary dropdown-toggle" style="color: black;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Login/Register
                                </button>
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
                            </div>
                        @else
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" style="color: black;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li class="dropdown-item">
                                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </div>
                            </div>
                        @endguest<i class="fas fa-angry"></i>
                    </ul>
                </div>
        </nav>
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center" style="margin-bottom:10px;">
				</li>

                <li>
                    <a class=" {{$userClass}} cp-menu"  href="{{ route('users.index') }}"> Users </a>
                </li>
                <li>
                    <a class=" {{$optionClass}} cp-menu"  href="{{ route('options.index') }}"> Options </a>
                </li>
                <li>
                    <a class=" {{$variantClass}} cp-menu"  href="{{ route('variants.index') }}"> Variants </a>
                </li>
                <li>
                    <a class=" {{$categoryClass}} cp-menu" href="{{ route('categories.index') }}"> Categories </a>
                </li>
                <li>
                    <a class=" {{$productClass}} cp-menu" href="{{ route('products.index') }}"> Products </a>
                </li>
                <li>
                    <a class=" {{$reviewClass}} cp-menu" href="{{ route('reviews.index') }}"> Reviews </a>
                </li>
                <li>
                    <a class=" {{$orderClass}} cp-menu" href="{{ route('orders.index') }}"> Orders </a>
                </li>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12" style="text-align:center;">
                     <h2>@yield('subject')</h2>
                    </div>
                </div>
                  @yield('SearchBar')
                 <!-- /. ROW  -->
                  <hr />

    <div>

        @if(session('message'))
            <div class="alert alert-success manipulated" style="text-align:center;">
                {{ session('message') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger manipulated" style="text-align:center;">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')

    </div>

            @yield('modal')


     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="{{asset('assets/js/jquery-1.10.2.js')}}"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="{{asset('assets/js/jquery.metisMenu.js')}}"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="{{asset('assets/js/morris/raphael-2.1.0.min.js')}}"></script>
    <script src="{{asset('assets/js/morris/morris.js')}}"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    @stack('scripts')
</body>
</html>
