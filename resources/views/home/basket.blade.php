@extends('layouts.home')

@section('title')
    {{ auth()->user()->name }}'s Basket
@endsection


@section('banner')
    <div class="page-heading products-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h2>{{ auth()->user()->name }}'s Basket</h2>
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
    <table class="table table-bordered data-table" id="myTable" name="myTable">
        <thead>
        <th>#</th>
        <th>Product Image</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>total</th>
        <th>Tools</th>
        </thead>
        <tbody id="content">
        @php
            $i = 1;
        @endphp
        @foreach($content as $record)
            <tr>
                <td>{{ $i++ }}</td>
                <td><img style="height: 125px;" title="{{ $record->product->name }}" alt="{{ $record->product->name }}" src="{{ $record->product->ImagePath }}"></td>
                <td><a href="{{ route('product',['product' => $record->product->id ]) }}">{{ $record->product->name }}</a></td>
                <td>{!! $record->description !!}</td>
                <td><input style="width: 50px;" type="number" value="{{ $record->quantity }}" id="{{ $record->id }}quantity" onchange="changeQuantity({{ $record->id }}, value)" min=1></td>
                <td>{{ $record->product->price }}₺</td>
                <td id="{{ $record->id }}total">{{ $record->product->price * $record->quantity }}₺</td>
                <td><button type="submit" style="width:130px;margin-left:10px;" class="btn btn-danger" onclick="deleteRecord({{ $record->id }})">Delete</button></td>
            </tr>
        @endforeach
        @if($basketTotal != 0)
            <tr>
                <td>Total:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td id="basketTotal">{{ $basketTotal }}₺</td>
                <td><a href="{{ route('checkout') }}" style="width:130px;margin-left:10px;" class="btn btn-info" onclick="finalizeOrder()">Submit Order</a></td>
            </tr>
        @else
            <tr>
                <td style="text-align: center" colspan="8">No Products</td>
            </tr>
        @endif

        </tbody>
    </table>

    <div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align:center;">
                    <p style="font-size:20px;">Are You Sure You Want To Delete The Product.</p>
                </div>
                <div class="modal-footer">
                    <form id="DeletionForm" method="post" action="">
                        @csrf
                        @method('delete')
                        <div hidden id="HiddenID"></div>
                        <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align:center;">
                    <p style="font-size:20px;">Are You Sure You Want To Delete The Product.</p>
                </div>
                <div class="modal-footer">
                    <form id="DeletionForm" method="post" action="">
                        @csrf
                        @method('delete')
                        <div hidden id="HiddenID"></div>
                        <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteRecord(id){
            document.getElementById("HiddenID").innerHTML = id;
            document.getElementById("DeletionForm").action = "{{route('baskets.destroy', '')}}"+"/"+id;
            $('#DeleteModal').modal('toggle');
        }

        function changeQuantity(id, value){
            url = "{{ route('baskets.update' , ['basket' => ':id']) }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: 'put',
                dataType: 'JSON',
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: value
                },
                success: function (response) {
                    if(response){
                        var ColumnID = id + "total";
                        document.getElementById(ColumnID).innerHTML = response.total + "₺";
                        document.getElementById('basketTotal').innerHTML = response.basketTotal + "₺";
                    }
                }
            });
        }

    </script>
@endsection
