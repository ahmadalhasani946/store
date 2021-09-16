@extends('layouts.app')

@section('title','Edit ' . $category->name)

@section('subject','Edit ' . $category->name)

@section('content')
    <div class="container" style="max-width:500px;">
        <form method="post" action="{{route('categories.update',['category' => $category->id ])}}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="form-group">
                <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                <input type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The Category" value="{{ old('name') ? old('name') : $category->name }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div id="ParentDiv" name="ParentDiv" style="margin: 10px 0;">
                <label class="form-label" for="parent">The Parent </label>
                <select name='parent' id='parent' class='custom-select form-control'>
                    <option @if(old('parent') == 0) selected @endif value="0">Choose The Parent Category</option>
                    @foreach ($categories as $category) {
                    <option @if($parent != null && $parent->id == $category->id && old('parent') == null || old('parent') == $category->id) selected @endif  value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="form-group">
                <label class="form-label @error('description') error @enderror" for="description">The Description </label>
                <textarea class="form-control @error('description') error @enderror" id="description" name="description" placeholder="Enter The Description Of The Category">{!! old('description') ? old('description') :  $category->description  !!}</textarea>
            </div>
            @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div class="form-group">
                <label class="form-label @error('image') error @enderror" for="image">Category Image</label>
                <input type="file" class="form-control-file @error('image') error @enderror" id="image" name="image">
                <label  for="oldImage">Old Image</label>
                <img id="oldImage" name="oldImage" style="margin: 10px;" src="{{ $image }}" border="0" width="150" class="img-rounded" align="center" />
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

