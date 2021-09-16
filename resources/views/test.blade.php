@extends('layouts.cp')

@section('content')
<div class="container" style="text-align:center;margin-top:100px;margin-left:350px">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="color:grey;font-size:25px" > Welcome {{ Auth::user()->name }}</div>

                <div class="card-body" style="font-size:20px">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection