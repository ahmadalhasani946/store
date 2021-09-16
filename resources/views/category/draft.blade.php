

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Binary Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- MORRIS CHART STYLES-->
    <link href="{{ asset('assets/js/morris/morris-0.4.3.min.css') }}" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<div id="wrapper">
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
            <li>
                @foreach ($categories as $category)
                    @if(count($category->children) > 0)
                        <a href="#">{{ $category->name }}<span class="fa arrow"></span></a>
                    @else
                        <a href="#">{{ $category->name }}</a>
                    @endif
                    <ul class="nav" >
                        @forelse($category->children as $childCategory)
                            @include('category.child_category', ['child_category' => $childCategory])
                        @empty
                        @endforelse
                    </ul>
                @endforeach
            </li>
            </ul>
        </div>
    </nav>
</div>
<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="{{ asset('assets/js/jquery-1.10.2.js') }}"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!-- METISMENU SCRIPTS -->
<script src="{{ asset('assets/js/jquery.metisMenu.js') }}"></script>
<!-- MORRIS CHART SCRIPTS -->
<script src="{{ asset('assets/js/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('assets/js/morris/morris.js') }}"></script>
<!-- CUSTOM SCRIPTS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>


</body>
</html>
