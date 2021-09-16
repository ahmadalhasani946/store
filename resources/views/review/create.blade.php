@extends('layouts.app')

@section('title','Create Review')

@section('subject','Create New Review')

@section('content')
    <div class="container" style="max-width:500px;">
        <form method="post" action="{{route('reviews.store')}}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="customers">The customer</label>
                <div id="customers" name="customers"  style="margin: 10px 0;">
                    <select name='customer_id' id='customer_id' class='custom-select form-control'>
                        @foreach ($customers as $customer) {
                            <option @if(old('customer_id') == $customer->id) selected @endif value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label" for="products">The Product</label>
                <div id="products" name="products"  style="margin: 10px 0;">
                    <select name='product_id' id='product_id' class='custom-select form-control'>
                        @foreach ($products as $product) {
                            <option @if(old('product_id') == $product->id) selected @endif value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label @error('comment') error @enderror" for="comment">The Comment</label>
                <textarea class="form-control @error('comment') error @enderror" rows="5" id="comment" name="comment" placeholder="Enter The Comment Here">{{ old('comment') }}</textarea>
            </div>
            @error('comment')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div class="form-group" style="margin:auto;">
                <label class="form-label @error('stars') error @enderror" for="stars">The Rating</label>
                <div style="text-align: left">
                    <div class="starrating" style="direction:rtl;float:left;">
                        @for($i = 5; $i > 0; $i--)
                            <input @if(old('stars') == $i) checked @endif type="radio" id="star{{$i}}" name="stars" value="{{$i}}" /><label for="star{{$i}}" title="{{$i}} star"></label>
                        @endfor
                    </div>
                </div>
            </div><br><br>
            @error('stars')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div class="form-group">
                <label class="form-label" for="show">Should it be Displayed</label>
                <div id="show" name="show"  style="margin: 10px 0;">
                    <select name='to_show' id='to_show' class='custom-select form-control'>
                        <option @if(old('to_show') == 1) selected @endif value='1'>Yes</option>
                        <option @if(old('to_show') == 0) selected @endif value='0'>No</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group" style="text-align:center;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('reviews.index') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
