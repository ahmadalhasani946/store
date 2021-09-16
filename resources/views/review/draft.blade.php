
@extends('layouts.cp')

@section('title','Create Review')

@section('SearchBar')

@endsection

@section('content')
    <style>




    </style>

    <form method="post" action="{{ route('reviews.store') }}">
        @csrf

        <div class="starrating" style="direction:rtl;float:left;">
            <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 star"></label>
            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 star"></label>
            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 star"></label>
            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 star"></label>
            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"></label>
        </div><br><br><br>

        <div class="starrating" style="direction:rtl;float:left;">

        </div>


        <div>
            <button type="submit">Submit</button>
        </div>
    </form>


@endsection

@push('scripts')

    <script>

    </script>
@endpush
