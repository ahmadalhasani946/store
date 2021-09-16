@extends('layouts.home')

@section('title','Products')

@section('banner')
<div class="page-heading products-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-content">
                    <h4>new arrivals</h4>
                    <h2>Our Products</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
@php
    $url = url()->current();
@endphp
<div class="products">
    <div class="container">
        <div class="row">
            @if($parentDepth > -1)
                <div class="col-md-12">
                    <div class="filters">
                        <ul>
                            @foreach($parentDepthCategories as $category)
                                @if(str_contains($parentID, $category->id))
                                    <a href="{{ route('products', ['category' => $category->id ]) }}"><li class="active" style="color:coral;" data-filter=".des">{{ $category->name }}</li></a>
                                @else
                                    <a href="{{ route('products', ['category' => $category->id ]) }}"><li  data-filter=".des">{{ $category->name }}</li></a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                <div class="filters">
                    <ul>
                        @foreach($categories as $category)
                            @if(str_contains($url, $category->id))
                                <a href="{{ route('products', ['category' => $category->id ]) }}"><li class="active" data-filter=".des">{{ $category->name }}</li></a>
                            @else
                                <a href="{{ route('products', ['category' => $category->id ]) }}"><li  data-filter=".des">{{ $category->name }}</li></a>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @if(count($childDepthCategories) > 0)
                <div class="col-md-12">
                    <div class="filters">
                        <ul>
                            @foreach($childDepthCategories as $category)
                                <a href="{{ route('products', ['category' => $category->id ]) }}"><li data-filter=".des">{{ $category->name }}</li></a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="col-md-12" >
                <div class="filters-content">
                    <div class="row grid" id="products">
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-4 all des">
                                <div class="product-item">
                                    <a href="{{ route('product', ['product' => $product->id ]) }}"><img height="300px" src="{{ $product->ImagePath }}" alt="{{ $product->ImagePath }}"></a>
                                    <div class="down-content">
                                        <a href="#"><h4>{{ $product->name }}</h4></a>
                                        <h6>{{ $product->price }}₺</h6>
                                        <div style="text-align: center;"><p>{!! $product->description !!}</p></div>
                                        <ul class="stars">
                                            @php
                                                $star = 0;
                                            @endphp
                                            @for($star ; $star < $stars[$product->id]; $star++)
                                                <li><i style="color:gold" class="fa fa-star"></i></li>
                                            @endfor
                                            @for($star ; $star < 5; $star++)
                                                <li><i  class="fa fa-star"></i></li>
                                            @endfor
                                        </ul>
                                        <span>Reviews ({{ $reviews[$product->id] }})</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <ul class="pages">
                @php
                    if(app('request')->input('page') != null){
                        $current = app('request')->input('page');
                    }
                    else{
                        $current = 1;
                    }

                @endphp
                @if(app('request')->input('page') != null)
                    @if( $current != 1)
                        <li><a href="{{ $products->url(1) }}#products"><i class="fa fa-angle-double-left"></i></a></li>
                    @endif
                    @if( $current != 1)
                        <li><a href="{{ $products->previousPageUrl() }}#products"><i class="fa fa-angle-left"></i></a></li>
                    @endif
                    <li><a href="{{ $products->url($current) }}#products">{{ $current }}</a></li>
                @else
                    <li><a href="{{ $products->url(1) }}#products">1</a></li>
                @endif
                @if( $current != $products->lastPage())
                    <li><a href="{{ $products->nextPageUrl() }}#products"><i class="fa fa-angle-right"></i></a></li>
                @endif
                @if($current != $products->lastPage())
                    <li><a href="{{ $products->url($products->lastPage()) }}#products"><i class="fa fa-angle-double-right"></i></a></li>
                @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
