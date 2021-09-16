@extends('layouts.app')

@section('title','Create Category')

@section('subject','Create New Category')

@section('content')

    <div class="container" style="max-width:500px;">
        <form method="post" action="{{route('categories.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                <input type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The Category" value="{{ old('name') }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <label class="form-label" for="parent">The Parent </label>
            <div id="ParentDiv" name="ParentDiv" style="margin: 10px 0;">
                <select name='parent' id='parent' class='custom-select form-control'>
                <option value="0">Choose The Parent Category</option>
                @foreach ($categories as $category) {
                    <option @if(old('parent') == $category->id) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                </select>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label @error('description') error @enderror" for="description">The Description </label>
                <textarea class="form-control @error('description') error @enderror" id="description" name="description" placeholder="Enter The Description Of The Category">{!! old('description') !!}</textarea>
             </div>
            @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div class="form-group">
                <label class="form-label @error('image') error @enderror" for="image">Category Image</label>
                <input type="file" class="form-control-file @error('image') error @enderror" id="image" name="image">
            </div>
            @error('image')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group" style="text-align:center;margin-top: 10px;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('categories.index') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>

        </form>

    </div>

    <script>
        tinymce.init({
            selector: 'textarea',
        });
    </script>
@endsection

