@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <form action="{{ route('brands.list') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Search brand..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>
                            </form>
                            
                        </div>
                        <div>
                            <a href="{{ route('brands.create') }}" class="btn btn-success">
                                <i class="bx bx-plus me-1"></i>Thêm Thương Hiệu
                            </a>
                        </div>
                    </div> <!-- end row -->
                </div>
                <div>
                    <div class="table-responsive table-centered">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->description }}</td>
                                        <td>{{ $brand->slug }}</td>
                                        <!-- <td>{{ $brand->slug }}</td> -->

                                        <td>
                                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo" width="50">
                                        </td>
                                        <td>
                                            @if($brand->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            
                                            <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            
                                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')">
                                                    Delete
                                                </button>
                                            </form>
                                            
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- table responsive -->
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
@endsection

