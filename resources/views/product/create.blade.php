@extends('layouts.app')

@section('title','Create Product')

@section('subject','Create New Product')

@section('content')
    <div class="container" style="max-width:500px;">
        <form method="post" action="{{route('products.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                <input type="text" class="form-control @error('name') error @enderror" id="name" name="name" placeholder="Enter The Name Of The Product" value="{{ old('name') }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
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
                <label class="form-label @error('price') error @enderror" for="name">The Price </label>
                <input type="number" step="0.01" class="form-control @error('price') error @enderror" id="price" name="price" placeholder="0.00" value="{{ old('price') }}">
            </div>
            @error('price')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <h4 style='font-weight: bold;'>To Which Categories It Belongs</h4>
            @foreach($categories as $category)
                <div class="form-check">
                    <input type="checkbox" name="categories[]" id="{{ $category->name }}" value="{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? ' checked' : '' }}>
                    <label class="form-check-label" for="{{ $category->name }}" style="margin-left: 5px">{{ $category->name }}</label>
                </div>
            @endforeach
            <hr>
            <div class="form-group">
                <label class="form-label @error('primary') error @enderror" for="primary">Porduct Primary Image</label>
                <input type="file" class="form-control-file @error('primary') error @enderror" id="primary" name="primary">
            </div>
            @error('primary')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            <div class="form-group">
                <label class="form-label @error('images') error @enderror" for="primary">Porduct Images (Multiple)</label>
                <input type="file" class="form-control-file @error('images') error @enderror" id="images[]" name="images[]" multiple>
            </div>
            @error('images')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <hr>
            @foreach ($variants as $variant)
                <h4 style="font-weight: bold;">{{ $variant->name }}</h4>
                @foreach ($variant->options as $option)
                    <div class="form-check">
                        <input type="checkbox" name="{{ $variant->name . '[]' }}" id="{{ $option->name }}" value="{{ $option->id }}" {{ (is_array(old($variant->name)) && in_array($option->id, old($variant->name))) ? ' checked' : '' }}>
                        <label class="form-check-label" for="{{ $option->name }}" style="margin-left: 5px">{{ $option->name }}</label>
                    </div>
                @endforeach
            <hr>
            @endforeach

            <div class="form-group" style="text-align:center;margin-top: 10px;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('products.index') }}' style="text-align:center;">
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

