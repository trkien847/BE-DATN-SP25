@extends('admin.layouts.layout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3">
                        <div class="search-bar">
                            <span><i class="bx bx-search-alt"></i></span>
                            <input type="search" class="form-control" id="search" placeholder="Search invoice...">
                        </div>
                        <div>
                            <button id="openModalBtn" class="btn btn-success"><i class="bx bx-plus me-1"></i>Create
                                Category</button>
                        </div>
                    </div> <!-- end row -->
                </div>
                <div>
                    <div class="table-responsive table-centered">
                        <table class="table text-nowrap mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 py-2">Category ID</th>
                                    <th class="border-0 py-2">Name</th>
                                    <th class="border-0 py-2">Action</th>
                                </tr>
                            </thead> <!-- end thead-->
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <a href="apps-invoice-details.html" class="fw-medium">{{ $category->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">{{ $category->name }}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i
                                                    class="bx bx-edit fs-16"></i></button>
                                            <button type="button" class="btn btn-sm btn-soft-danger"><i
                                                    class="bx bx-trash fs-16"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody> <!-- end tbody -->
                        </table> <!-- end table -->
                    </div> <!-- table responsive -->
                    {{-- <div
                        class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                        <div class="col-sm">
                            <div class="text-muted">
                                Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">52</span>
                                invoices
                            </div>
                        </div>
                        <div class="col-sm-auto mt-3 mt-sm-0">
                            <ul class="pagination pagination-rounded m-0">
                                <li class="page-item">
                                    <a href="#" class="page-link"><i class='bx bx-left-arrow-alt'></i></a>
                                </li>
                                <li class="page-item active">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">2</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">3</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link"><i class='bx bx-right-arrow-alt'></i></a>
                                </li>
                            </ul>
                        </div> --}}
                </div>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
    @include('admin.categories.modal.add')
    @include('sweetalert::alert')
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const modal = new bootstrap.Modal(document.getElementById('nameModal'));
            const openModalBtn = document.getElementById('openModalBtn');
            const saveNameBtn = document.getElementById('saveNameBtn');
            const nameForm = $('#nameForm');
            const errorMessage = $('#errorMessage');

            openModalBtn.addEventListener('click', function() {
                modal.show();
            });

            saveNameBtn.addEventListener('click', function() {
                const formData = nameForm.serialize();

                errorMessage.empty();

                $.ajax({
                    url: nameForm.attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            modal.hide();
                            nameForm[0].reset();
                            window.location.href = window.location.href;
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage.empty();
                        for (let field in errors) {
                            errors[field].forEach(function(error) {
                                errorMessage.append('<p class="text-danger">' + error +
                                    '</p>');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .modal-header {
            background-color: #73e882;
            color: white;
        }
    </style>
@endpush
