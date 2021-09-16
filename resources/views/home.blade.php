@extends('layouts.cp')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ Auth::user()->name }}</div>

                <div class="card-body">
                    {{ 'Welcome, You are logged in!' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
