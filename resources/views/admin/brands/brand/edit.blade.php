@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Edit Brand</h5>
                    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $brand->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" id="description" name="description" class="form-control" value="{{ $brand->description }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" id="logo" name="logo" class="form-control">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="100" class="mt-2">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Active</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1" {{ $brand->is_active ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$brand->is_active ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Brand</button>
                        {{-- <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
