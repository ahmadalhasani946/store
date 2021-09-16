@extends('layouts.home')

@section('title','Product')

@section('banner')
    <div class="page-heading products-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="filters-content">
                        <div class="row grid">
                            <div class="col-lg-4 col-md-4 all des">
                                <img src="{{ asset('assets2/images/product_01.jpg') }}" width="100%" height="350px" alt="">
                            </div>
                            <div class="col-lg-8 col-md-8 all des">
                                <div class="product-item">
                                    <div class="down-content">
                                        <a href="#"><h4>Tittle goes here</h4></a>
                                        <h6>$18.25</h6>
                                        <p>Lorem ipsume dolor sit amet, adipisicing elite. Itaque, corporis nulla aspernatur.</p>
                                        <ul class="stars">
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                            <li><i class="fa fa-star"></i></li>
                                        </ul>
                                        <span>Reviews (12)</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="filters-content">
                        <div class="row">
                            <div class="product-item">
                                <div class="down-content">
                                    <a href="#"><h4>Tittle goes here</h4></a>
                                    <h6>$18.25</h6>
                                    <p>Lorem ipsume dolor sit amet, adipisicing elite. Itaque, corporis nulla aspernatur.</p>
                                    <ul class="stars">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
