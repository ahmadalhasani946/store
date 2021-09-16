@extends('layouts.cp')

@section('title','Categories Trash')

@section('SearchBar')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="row SearchBar">
                <div class="col-md-4" ></div>
                <div class="col-md-8" >
                    <a class="btn btn-danger" style="float:right;min-width:100px;margin-right: 10px" href="{{ route('categories.index') }}">Go Back To Categories</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('content')
<table class="table table-bordered data-table" id="myTable" name="myTable">
    <thead>
    <th>ID</th>
    <th>Category Name</th>
    <th>Parent Name</th>
    <th>Depth</th>
    <th>Number Of Products Has</th>
    <th>Image</th>
    <th>Tools</th>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center;">
                <p style="font-size:20px;">Are You Sure You Want To Delete The Category.</p>
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
        function forceDelete(id){
            document.getElementById("DeletionForm").action = "{{route('categories.forceDelete', '')}}"+"/"+id;
        }

        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ route('categories.index') }}",
                    "data":{
                        "trash": true
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'parent',
                        name: 'parent'
                    },
                    {
                        data: 'depth',
                        name: 'depth'
                    },
                    {
                        data: 'products',
                        name: 'products'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
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
