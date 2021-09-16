@extends('layouts.home')

@section('title')
    {{ auth()->user()->name }}'s Profile
@endsection

@section('banner')
    <div class="page-heading products-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <img style="border-radius: 50%; height: 300px;width: 300px;" src="{{ auth()->user()->ImagePath }}">
                        <h2>{{ auth()->user()->name }}'s Profile</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="container" id="container" style="max-width:500px;">
        <form method="post" action="{{route('users.update', ['user' => auth()->user()->id ])}}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="form-group">
                <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                <input type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The User" value="{{ old('name', auth()->user()->name) }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label class="form-label @error('image') error @enderror" for="image">User Image</label>
                <input type="file" class="form-control-file @error('image') error @enderror" id="image" name="image">
            </div>
            @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label class="form-label @error('email') error @enderror" for="email">The Email </label>
                <input type="email" class="form-control @error('email') error @enderror" id="email" name="email" placeholder="Enter The Email" value="{{ old('email', auth()->user()->email) }}">
            </div>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label class="form-label @error('password') error @enderror" for="password">The password </label>
                <input type="password" class="form-control @error('password') error @enderror" id="password" name="password" placeholder="Enter The Password">
                <small style="color:red;">If You want to change the password Then Type</small>
            </div>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label class="form-label @error('password_confirmation') error @enderror" for="password_confirmation">Re-enter The Password</label>
                <input type="password" class="form-control @error('password_confirmation') error @enderror" id="password_confirmation" name="password_confirmation" placeholder="Re-enter The Password">
            </div>
            @error('password_confirmation')
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

            <div class="form-group" style="text-align:center;">
                <button class="btn btn-success" type="submit">Save Change</button>
                <a class="btn btn-danger " href='{{ route('home') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
