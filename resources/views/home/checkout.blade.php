@extends('layouts.home')

@section('title')
    {{ auth()->user()->name }}'s Order
@endsection

@section('banner')
    <div class="page-heading products-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h2>{{ auth()->user()->name }}'s Order</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        img:hover{
            opacity: 1;
            transform: scale(1);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <form class="col-md-12" method="post" action="{{route('confirmOrder')}}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    @csrf
                    <input hidden name="customer_id" value="{{ auth()->user()->id }}">
                    <div class="form-group">
                        <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                        <input disabled type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The User" value="{{ old('name', auth()->user()->name) }}">
                    </div>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label class="form-label @error('email') error @enderror" for="email">The Email </label>
                        <input disabled type="email" class="form-control @error('email') error @enderror" id="email" name="email" placeholder="Enter The Email" value="{{ old('email', auth()->user()->email) }}">
                    </div>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror


                    <div class="form-group">
                        <label class="form-label @error('phone') error @enderror" for="phone">The Phone </label>
                        <input type="text" class="form-control @error('phone') error @enderror" maxlength="11" id="phone" name="phone" placeholder="0xxxxxxxxxx" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                    @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label class="form-label @error('address') error @enderror" for="address">The Address </label>
                        <input type="text" class="form-control @error('address') error @enderror" id="address" name="address" placeholder="Enter The Address" value="{{ old('address', auth()->user()->address) }}">
                    </div>
                    @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4" style="margin-top: 55px;">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Total Cost</h5>
                            <p class="card-text"><b>The Total:</b> <span style="float: right;">{{ $basketTotal }}₺</span></p>
                            <p class="card-text"><b>Shipment Expenses:</b> <span style="float: right;">10.0₺</span></p>
                            <p class="card-text"><b>taxes:</b> <span style="float: right;">0.0₺</span></p>
                            <hr>
                            <p class="card-text"><b>Total:</b> <span style="float: right;">{{ $basketTotal + 10.0 }}₺</span></p>
                            <div style="text-align: center;margin-top: 10px;">
                                <button type="submit" class="btn btn-info">Confirm Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
