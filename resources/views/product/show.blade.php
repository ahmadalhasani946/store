
@extends('layouts.cp')

@section('title',$product->name)

@section('SearchBar')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="row SearchBar">
                <div class="col-md-4" ></div>
                <div class="col-md-8" >
                    <a class="btn btn-danger" style="float:right;min-width:100px;margin-right: 10px" href="{{ route('products.index') }}">Go Back To Products</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')
    <table class="table table-bordered data-table" id="myTable" name="myTable">
        <thead>
        <th>ID</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Categories</th>
        <th>Optionts Variants</th>
        </thead>
        <tbody>
        <tr>
            <th>{{ $product->id }}</th>
            <th>{{ $product->name }}</th>
            <th>{!! $product->description !!}</th>
            <th>{{ $categories }}</th>
            <th>{!! $optionVariant !!}</th>
        </tr>
        </tbody>
    </table>
    <hr>
    <table class="table table-bordered data-table" id="myTable" name="myTable">
        <thead>
        <th>ID</th>
        <th>Image</th>
        <th>Priamry</th>
        </thead>
        <tbody>
        @foreach($images as $image)
            <tr>
                <th>{{ $image->id }}</th>
                <th><img style="margin: 10px;" src="{{ asset("storage/images/" . \App\Models\Product::$folderName . '/' . $image->saved_name) }}" max-height="150" width="150" class="img-rounded" align="center" /></th>
                <th>{{ $image->is_primary ? 'Yes' : 'No' }}</th>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
