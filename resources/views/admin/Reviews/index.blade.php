@extends('admin.layouts.layout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fa-star {
            font-size: 20px;
            transition: transform 0.2s ease;
        }
        .fa-star:hover {
            transform: scale(1.2); 
        }
    </style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý đánh giá</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                    <th>Số sao</th>
                                    <th>Thời gian</th>
                                    <th>Loại</th>
                                    <th>Trả lời</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->user->fullname }}</td>
                                    <td>{{ $review->product->name }}</td>
                                    <td>{{ $review->review_text }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge {{ $review->is_auto ? 'badge-info' : 'badge-primary' }}">
                                            {{ $review->is_auto ? 'Tự động' : 'Thủ công' }}
                                        </span>
                                    </td>
                                    <td>
                                    @if($review->admin_reply)
                                        <p class="mb-1">{{ $review->admin_reply }}</p>
                                        <small class="text-muted">
                                            {{ $review->replied_at->format('d/m/Y H:i:s') }}
                                            @if($review->is_auto)
                                                <span class="badge badge-info">Tự động</span>
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">Chưa trả lời</span>
                                    @endif
                                    </td>
                                    <td>@if($review->admin_reply)
                                            
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary" 
                                                    onclick="showReplyForm('{{ $review->id }}')">
                                                <i class="fas fa-reply"></i> Trả lời
                                            </button>
                                        @endif
                                       
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showReplyForm(reviewId) {
    Swal.fire({
        title: 'Trả lời đánh giá',
        input: 'textarea',
        inputLabel: 'Nội dung trả lời',
        inputPlaceholder: 'Nhập nội dung trả lời...',
        showCancelButton: true,
        confirmButtonText: 'Gửi trả lời',
        cancelButtonText: 'Hủy',
        inputValidator: (value) => {
            if (!value) {
                return 'Vui lòng nhập nội dung trả lời!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/reviews/${reviewId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    reply: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Thành công!', 'Đã trả lời đánh giá', 'success')
                    .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi!', data.message, 'error');
                }
            });
        }
    });
}
</script>
@endpush
@endsection