
@extends('layouts.cp')

@section('title','Order: ' . $order->id)

@section('SearchBar')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="row SearchBar">
                <div class="col-md-4" ></div>
                <div class="col-md-8" >
                    <a class="btn btn-danger" style="float:right;min-width:75px;" href="{{ route('orders.index') }}">Go Back To Orders</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-default" style="width: 300px">
        <div class="panel-heading">
            <h3 class="panel-title"> Order Of: {{ $customer->name }} </h3>
        </div>
        <div class="panel-body">
            <h3 STYLE="margin: 0">Total Price: {{ $total }}₺ </h3>
        </div>
    </div>

    <table class="table table-bordered data-table" id="myTable" name="myTable">
        <thead>
        <th>#</th>
        <th>Product Image</th>
        <th>Description</th>
        <th>Quantity</th>
        </thead>
        <tbody>
            @php
                $total = count($products);
            @endphp
            @for($i = 0 ; $i < $total; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><img src="{{ $products[$i]->ImagePath }}" border="0" width="150" class="img-rounded" alt="{{ $products[$i]->ImageAlt }}" align="center" /></td>
                    <td>{{ $descriptions[$i] }}</td>
                    <td>{{ $quantities[$i] }}</td>
                </tr>
            @endfor

        </tbody>
    </table>
@endsection

@push('scripts')
<script>

</script>
@endpush
