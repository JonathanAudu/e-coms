@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Product</h5>
    <div class="card-body">
      <form method="post" action="{{ route('product.update', $product->id) }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="inputName" class="col-form-label">Name <span class="text-danger">*</span></label>
            <input id="inputName" type="text" name="name" placeholder="Enter name" value="{{$product->name}}" class="form-control">
          @error('title')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
          @error('description')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Category <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                  {{ $cat->title }}
                </option>
              @endforeach
            </select>
          </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Price <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter price" value="{{ $product->price }}" class="form-control">
          @error('price')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Discount (%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount" value="{{ $product->discount }}" class="form-control">
          @error('discount')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="stock">Quantity <span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity" value="{{ $product->stock }}" class="form-control">
          @error('stock')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                  <i class="fas fa-image"></i> Choose
                  </a>
              </span>
              <input id="thumbnail" class="form-control" type="text" name="photo" value="{{ $product->photo }}">
          </div>
          <div id="holder" style="margin-top:15px; max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group mb-3">
          <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
        $('#description').summernote({
            placeholder: "Write detail description...",
            tabsize: 2,
            height: 150
        });
    });
</script>
@endpush
