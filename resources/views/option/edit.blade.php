@extends('layouts.app')

@section('title','Edit ' . $option->name)

@section('subject','Edit ' . $option->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">

            <form method="post" action="{{ route('options.update', ['option' => $option->id ]) }}">
                @csrf
                @method('patch')
                <div class="form-group">
                    <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                    <input type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The Option" value="{{ old('name') ? old('name') : $option->name }}">
                </div>
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-group" style="text-align:center;">
                    <button class="btn btn-success" type="submit">Edit Record</button>
                    <a class="btn btn-danger " href='{{ route('options.index') }}' style="text-align:center;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
