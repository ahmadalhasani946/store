@extends('layouts.app')

@section('title','Edit ' . $variant->name)

@section('subject','Edit ' . $variant->name)

@section('content')
    <div class="container" style="max-width:500px;">
        <form method="post" action="{{ route('variants.update', ['variant' => $variant->id ]) }}">
            @csrf
            @method('patch')
            <input hidden name="sent" type="text" value="sent">
            <div class="form-group">
                <label class="form-label @error('name') error @enderror" for="name">The Name </label>
                <input type="text" class="form-control @error('name') error @enderror" id="name" name="name"  placeholder="Enter The Name Of The Variant" value="{{ old('name') ? old('name') : $variant->name }}">
            </div>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
                <label class="form-label @error('options') error @enderror">The Options</label>
                @foreach($options as $option)
                    <div class="form-check">
                        <input type="checkbox"  @if(in_array($option->id,$variantOptions) == 1 && !old('sent')) checked @endif name="options[]" id='{{ $option->name }}' value="{{ $option->id }}" {{ (old('options') && in_array($option->id, old('options'))) ? ' checked' : '' }}>
                        <label class="form-check-label" for="{{ $option->name }}">
                            {{ $option->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('options')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group" style="text-align:center;">
                <button class="btn btn-success" type="submit">Save Record</button>
                <a class="btn btn-danger " href='{{ route('variants.index') }}' style="text-align:center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection



