@extends('layouts.app')

@section('title','Create Options')

@section('subject','Create New Option')

@section('content')
    <div class="container" style="max-width:500px;">
        <form method="post" action="{{route('options.store')}}">
            @csrf
            <div class="form-group">
                <label class="form-label @error('name') error  @enderror" for="name">The Name </label>
                <input type="text" class="form-control  @error('name') error  @enderror " id="name" name="name" placeholder="Enter The Name Of The Option" value="{{ old('name') }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group" style="text-align:center;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('options.index') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>
        </form>

    </div>
@endsection
