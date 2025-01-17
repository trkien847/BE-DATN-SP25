@extends('admin.layouts.layout')
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between gap-3">
                   
                </div> <!-- end row -->
            </div>
            <div>
                <div class="table-responsive table-centered">
                    @if (session('message'))
                        <div class="aler text-primary">
                            {{ session('message') }}
                        </div>
                    @endif
                    <table class="table text-nowrap mb-0">
                        <thead class="bg-light bg-opacity-50">
                             <tr>
                                  <th class="border-0 py-2">ID</th>
                                  <th class="border-0 py-2">Mã Sản Phẩm</th>
                                  <th class="border-0 py-2">Mã Đơn Hàng</th>
                                  <th class="border-0 py-2">Mã Người Dùng</th>
                                  <th class="border-0 py-2">Sao Đánh Giá</th>
                                  <th class="border-0 py-2">Nội Dung</th>
                                  <th class="border-0 py-2">Lý Do Khong Duyệt Đánh Giá</th>
                                  <th class="border-0 py-2">Thời Gian Tạo Đánh Giá</th>
                                  <th class="border-0 py-2">Thời Gian Cập Nhật Đánh Giá</th>
                             </tr>
                        </thead> <!-- end thead-->
                        <tbody>
                            @foreach ($data as $items )
                            <tr>
                               <td>
                                   {{$items->id}}
                               </td>
                               <td>{{$items->product_id}}</td>
                               <td>{{$items->order_id}}</td>
                               <td>
                                   {{$items->user_id}}
                               </td>
                               <td>
                                   {{$items->rating}}
                               </td>
                               <td>
                                   {{$items->review_text}}
                               </td>
                               <td>
                                   {{$items->reason}}
                               </td>
                               <td class="text-danger">{{$items->is_active}}</td>
                               <td class="text-danger">{{$items->created_at}}</td>
                               <td class="text-danger">{{$items->updated_at}}</td>
                               <td>
                                <a href="{{ route('list.edit', $items->id) }}" class="btn btn-sm btn-soft-secondary me-1">
                                    <i class="bx bx-edit fs-16"></i>
                                </a>
                                   <form action="{{route('reviews.destroy',$items->id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button  type="button" onclick="return confirm('bạn có muốn xóa không ?')" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                   </form>
                                  
                               </td>
                          </tr>
                            @endforeach
                            
                             
                        </tbody> <!-- end tbody -->
                   </table> <!-- end table -->
                   {{$data->links()}}
                </div> <!-- table responsive -->
               
            </div>
        </div> <!-- end card body -->
    </div> <!-- end card -->
</div> <!-- end col -->
    @include('admin.categories.modal.add')
    @include('sweetalert::alert')
@endsection
