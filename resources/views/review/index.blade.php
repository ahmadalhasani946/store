@extends('layouts.cp')

@section('title','Reviews')

@section('SearchBar')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="row SearchBar">
                <div class="col-md-4" ></div>
                <div class="col-md-8" >
                    <a class="btn btn-success" style="float:right;min-width:75px;" href="{{ route('reviews.create') }}">Add</a>
                    <a class="btn btn-danger" style="float:right;min-width:100px;margin-right: 10px" href="{{ route('reviews.index', ['trash', true]) }}">Check Trash</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')
<table style="text-align: center;" class="table table-bordered data-table" id="myTable" name="myTable">
    <thead >
    <th>ID</th>
    <th>Customer Name</th>
    <th>Product Name</th>
    <th>stars</th>
    <th>Comment</th>
    <th>Tools</th>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center;">
                <p style="font-size:20px;">Are You Sure You Want To Delete The Review.</p>
            </div>
            <div class="modal-footer">
                <form id="DeletionForm" method="post" action="">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function deleteRecord(id){
            document.getElementById("DeletionForm").action = "{{route('reviews.destroy', '')}}"+"/"+id;
        }

        $(function () {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('reviews.index') }}",
                    "data":{
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'stars',
                        name: 'stars'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });

    </script>
@endpush
