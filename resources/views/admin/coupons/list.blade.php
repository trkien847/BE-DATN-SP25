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
                      
                        <a href="{{route('coupons.create')}}" class="btn btn-success"><i class="bx bx-plus me-1"></i>Creat Coupon</a>
                    </div>
                </div> <!-- end row -->
            </div>
            <div>
                <div class="table-responsive table-centered">
                    <table class="table text-nowrap mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="border-0 py-2">STT</th>
                                <th class="border-0 py-2">Name của thương hiệu</th>
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
@endsection
