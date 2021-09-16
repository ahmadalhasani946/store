@extends('layouts.app')

@section('title', $variant->name)

@section('subject', $variant->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="cover col-md-4">
                <div class="content">
                    <h4>Has Option / Options</h4>
                    <ul>
                    @foreach($options as $option)
                        <li>{{ $option->name }}</li>
                    @endforeach
                    </ul>
                </div>
                <div style="text-align:center;margin-top:10px;">
                    <a id="" href="{{ route('variants.index') }}" class="btn btn-danger">Go Back</a>
                </div>
            </div>
        </div>

    </div>
@endsection

