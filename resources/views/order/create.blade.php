@extends('layouts.app')

@section('title','Create Order')

@section('subject','Create New Order')

@section('content')
    <div class="container" style="max-width:500px;">
        <h1>{{ old('customer_id') }}</h1>
        <form method="post" action="{{route('orders.store')}}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="customers">The customer</label>
                <div id="customers" name="customers"  style="margin: 10px 0;">
                    <select name='customer_id' id='customer_id' onchange="ChangeContent()" class='custom-select form-control'>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label" for="products">The Product</label>
                <div id="products" name="products"  style="margin: 10px 0;">
                    <select name='product_id' onchange='getOptionVariant(this.value)' id='product_id' class='custom-select form-control'>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="quantity">The Amount</label>
                <div style="margin: 10px 0;">
                    <input  class='custom-select form-control' value="1" min="1" type="number" name="quantity" id="quantity">
                </div>
            </div>
            <div id="VP" name="VP"></div>
            <hr>
            <div class="form-group" style="text-align:center;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('orders.index') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>
        </form>

    </div>

    <div style="">
        <h2 style="color:black;text-align: center;">Products in <span style="color:#808080;" id="UserSpan"></span> Basket</h2>
        <table class="table table-bordered data-table" id="myTable" name="myTable">
            <thead>
            <th>ID</th>
            <th>Product Image</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Tools</th>
            </thead>
            <tbody id="content">
            </tbody>
        </table>

        <div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="text-align:center;">
                        <p style="font-size:20px;">Are You Sure You Want To Delete The Product From The Basket.</p>
                    </div>
                    <div class="modal-footer">
                        <form id="DeletionForm" method="post" action="">
                            <div hidden id="HiddenID"></div>
                            @csrf
                            @method('delete')
                            <button type="submit" id="DeletionButton" class="btn btn-danger">Confirm Deletion</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script>
        const VP = document.getElementById('product_id').value;
        $(document).ready(function() {
            ChangeContent();
            getOptionVariant(VP);
        });

        function ChangeContent(){
            const customer = document.getElementById('customer_id');
            var name =customer.options[customer.selectedIndex].text;
            document.getElementById('UserSpan').innerHTML = name;
            var id = document.getElementById('customer_id').value;

            $.ajax({
                url: "{{ route('baskets.show', ['basket' => 1]) }}",
                type: 'GET',
                data: {
                    _token: $("input[name=_token]").val(),
                    id: id,
                },
                dataType: 'JSON',
                success: function (response) {
                    if(response){
                        console.log(response);
                        var body = document.querySelector('tbody');
                        while (body.firstChild) {
                            body.removeChild(body.firstChild);
                        }
                        var ids = response.ids;
                        var productImages = response.productImages;
                        var orderDescriptions = response.orderDescriptions;
                        var quantities = response.quantities;
                        var alts = response.alts;
                        for(var $i =0; $i < response.ids.length; $i++){
                            var tr = '<tr id="row' + ids[$i] + '">';
                            tr += '<td>' + ids[$i] + '</td>';
                            tr += '<td><img src="' + productImages[$i] + '" border="0" width="150" class="img-rounded" alt="' + alts[$i] + '" align="center" /></td>';
                            tr += '<td>' + orderDescriptions[$i] + '</td>';
                            tr += '<td>' + quantities[$i] + '</td>';
                            tr += '<td><button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' + ids[$i] + ')">Delete</button></td>';
                            tr += '</tr>';
                            $("#myTable").append(tr);
                        }
                    }
                }
            });
        }

        function getOptionVariant(id) {
            $.ajax({
                url: "{{route('orders.getOptionVariant')}}",
                type: 'POST',
                data: {
                    _token: $("input[name=_token]").val(),
                    id: id,
                },
                dataType: 'JSON',
                success: function (response) {
                    if(response){
                        document.getElementById('VP').innerHTML = response.data;
                    }
                }
            });
        }

        function addToBasket(id){
            var children = document.getElementById("VP").children;
            var idArr = [];
            for (var i = 1; i < children.length;i = i + 2 ) {
                idArr.push(children[i].id);
            }
            var variants = new Array();
            var options = new Array();
            for(var i = 0; i < idArr.length;i++ ){
                variants.push(idArr[i]);
                options.push(document.getElementById(idArr[i]).value);
            }
            $.ajax({
                url: "{{route('baskets.store')}}",
                type: 'POST',
                data: {
                    _token: $("input[name=_token]").val(),
                    product: id,
                    options: options,
                    variants: variants,
                    quantity: document.getElementById('quantity').value,
                    customer: document.getElementById('customer_id').value,
                },
                dataType: 'JSON',
                success: function (response) {
                    if(response){
                        ChangeContent();
                    }
                }
            });
        }


        document.getElementById("DeletionButton").addEventListener("click", function(event){
            event.preventDefault();
            var id = document.getElementById("HiddenID").innerHTML;
            var url = document.getElementById("DeletionForm").action;
            var row = document.getElementById("row"+id);
            row.hidden = true;
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $("input[name=_token]").val(),
                    id: id,
                },
                dataType: 'JSON',
                success: function (response) {
                    console.log(response)
                    if(response){
                        $("#DeleteModal").modal("hide");
                        $(".modal-backdrop").hide();

                    }
                }
            });
        });
    </script>
@endsection

@push('scripts')
    <script>
        function refreshTheQuantity(){
            document.getElementById('quantity').value = 1;
        }

        function deleteRecord(id){
            document.getElementById("HiddenID").innerHTML = id;
            document.getElementById("DeletionForm").action = "{{route('baskets.destroy', '')}}"+"/"+id;
        }
    </script>
@endpush


