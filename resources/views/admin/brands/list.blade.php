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
                          
                            <a href="{{route('brands.create')}}" class="btn btn-success"><i class="bx bx-plus me-1"></i>Creat Brand</a>
                        </div>
                    </div> <!-- end row -->
                </div>
                <div>
                    <div class="table-responsive table-centered">
                        <table class="table text-nowrap mb-0">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 py-2">STT</th>
                                    <th class="border-0 py-2">Name Brand</th>
                                    <th class="border-0 py-2">Action</th>
                                </tr>
                            </thead> <!-- end thead-->
                            <tbody>
                                
                                    <tr>
                                        <td>
                                            <a href="apps-invoice-details.html" class="fw-medium">1</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h5 class="fs-14 m-0 fw-normal">VL</h5>
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
                               
                            </tbody> <!-- end tbody -->
                        </table> <!-- end table -->
                    </div> <!-- table responsive -->
                    
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
