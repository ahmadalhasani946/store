@extends('layouts.app')

@section('title','Edit Order ' . $order->id)

@section('subject','Edit Order ' . $order->id)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Order Of: {{ $customer->name }} </h3>
                    </div>
                    <div class="panel-body">
                        <h3 STYLE="margin: 0">Total Price: {{ $total }} </h3>
                    </div>
                </div>

                <table class="table table-bordered data-table" id="myTable" name="myTable">
                    <thead>
                    <th>#</th>
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
                            <td>{{ $descriptions[$i] }}</td>
                            <td>{{ $quantities[$i] }}</td>
                        </tr>
                    @endfor

                    </tbody>
                </table>

                <form method="post" action="{{ route('orders.update', ['order' => $order->id ]) }}">
                    @csrf
                    @method('patch')

                    <div class="form-group">
                        <label class="form-label" for="served">Is it Yet Served</label>
                        <div id="served" name="served"  style="margin: 10px 0;">
                            <select name='done' id='done' class='custom-select form-control'>
                                <option @if(old('done', $order->done) == 1) selected @endif value='1'>Yes</option>
                                <option @if(old('done', $order->done) == 0) selected @endif value='0'>No</option>
                            </select>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group" style="text-align:center;">
                        <button class="btn btn-success" type="submit">Edit Record</button>
                        <a class="btn btn-danger " href='{{ route('orders.index') }}' style="text-align:center;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
