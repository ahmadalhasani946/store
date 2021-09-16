@extends('layouts.home')

@section('title', 'Ahmad Store')

@section('banner')
    <div class="banner header-text">
        <div class="owl-banner owl-carousel">
            <div class="banner-item-01">
                <div class="text-content">
                    <h4>Best Offer</h4>
                    <h2>New Arrivals</h2>
                </div>
            </div>
            <div class="banner-item-02">
                <div class="text-content">
                    <h4>Flash Deals</h4>
                    <h2>Get your best products</h2>
                </div>
            </div>
            <div class="banner-item-03">
                <div class="text-content">
                    <h4>Last Minute</h4>
                    <h2>Grab last minute deals</h2>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="section-heading">
                <h2>Our Categories</h2>
                <a href="{{ route('products') }}">view all products <i class="fa fa-angle-right"></i></a>
            </div>
        </div>
        @foreach($categories as $category)
            <div class="col-md-4">
                <div class="product-item">
                    <a href="{{ route('products', ['category' => $category->id ]) }}"><img height="300px" src="{{ $category->ImagePath }}" alt="{{ $category->ImagePath }}"></a>
                    <div class="down-content" style="text-align: center;">
                        <a href="{{ route('products', ['category' => $category->id ]) }}"><h4>{{ $category->name }}</h4></a>
                        {!! $category->description !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
