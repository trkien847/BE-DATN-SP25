@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        Bình luận sản phẩm:
                        <a href="{{ route('products.productct', $product->id) }}" target="_blank" class="text-primary">
                            {{ $product->name }}
                        </a>
                    </h4>
                    <a href="{{ route('admin.products.comments.list') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 150px;">Người dùng</th>
                                    <th>Nội dung</th>
                                    <th style="width: 150px;">Thời gian</th>
                                    <th style="width: 100px;">Trạng thái</th>
                                    <th style="width: 120px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comments->whereNull('parent_id') as $parentComment)
                                    {{-- Parent comment row --}}
                                    <tr data-comment-id="{{ $parentComment->id }}" class="parent-comment">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($parentComment->user && $parentComment->user->avatar)
                                                    <img src="{{ asset($parentComment->user->avatar) }}"
                                                        class="rounded-circle me-2" alt="Avatar"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('admin/images/users/dummy-avatar.jpg') }}"
                                                        class="rounded-circle me-2" alt="Default Avatar"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $parentComment->user->fullname ?? 'Ẩn danh' }}
                                                    </h6>
                                                    <small
                                                        class="text-muted">{{ $parentComment->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="comment-content">
                                                {{ $parentComment->content }}
                                            </div>
                                        </td>
                                        <td>{{ $parentComment->created_at->format('H:i, d/m/Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $parentComment->is_approved ? 'success' : 'warning' }}">
                                                {{ $parentComment->is_approved ? 'Đã duyệt' : 'Chờ duyệt' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-soft-primary btn-sm reply-btn"
                                                    data-bs-toggle="modal" data-bs-target="#replyModal"
                                                    data-comment-id="{{ $parentComment->id }}"
                                                    data-comment-content="{{ $parentComment->content }}">
                                                    <i class="bx bx-reply"></i>
                                                </button>
                                                <button type="button" class="btn btn-soft-danger btn-sm delete-btn"
                                                    data-comment-id="{{ $parentComment->id }}">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Child comments --}}
                                    @foreach ($comments->where('parent_id', $parentComment->id) as $childComment)
                                        <tr data-comment-id="{{ $childComment->id }}" class="child-comment">
                                            <td class="border-0">
                                                <div class="d-flex align-items-center ms-4">
                                                    @if ($childComment->user && $childComment->user->avatar)
                                                        <img src="{{ asset($childComment->user->avatar) }}"
                                                            class="rounded-circle me-2" alt="Avatar"
                                                            style="width: 28px; height: 28px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('admin/images/users/dummy-avatar.jpg') }}"
                                                            class="rounded-circle me-2" alt="Default Avatar"
                                                            style="width: 28px; height: 28px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $childComment->user->fullname ?? 'Ẩn danh' }}
                                                        </h6>
                                                        <small
                                                            class="text-muted">{{ $childComment->user->email ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0">
                                                <div class="comment-content ms-4">
                                                    <div class="reply-indicator text-muted small mb-1">
                                                        <i class="bx bx-reply"></i> Trả lời bình luận của
                                                        {{ $parentComment->user->fullname ?? 'Ẩn danh' }}
                                                    </div>
                                                    {{ $childComment->content }}
                                                </div>
                                            </td>
                                            <td class="border-0">{{ $childComment->created_at->format('H:i, d/m/Y') }}</td>
                                            <td class="border-0">
                                                <span
                                                    class="badge bg-{{ $childComment->is_approved ? 'success' : 'warning' }}">
                                                    {{ $childComment->is_approved ? 'Đã duyệt' : 'Chờ duyệt' }}
                                                </span>
                                            </td>
                                            <td class="border-0">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-soft-primary btn-sm reply-btn"
                                                        data-bs-toggle="modal" data-bs-target="#replyModal"
                                                        data-comment-id="{{ $childComment->id }}"
                                                        data-comment-content="{{ $childComment->content }}">
                                                        <i class="bx bx-reply"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-soft-danger btn-sm delete-btn"
                                                        data-comment-id="{{ $childComment->id }}">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có bình luận nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị {{ $comments->firstItem() ?? 0 }} đến {{ $comments->lastItem() ?? 0 }}
                            của {{ $comments->total() ?? 0 }} bình luận
                        </div>
                        {{ $comments->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Trả lời bình luận</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="original-comment mb-3">
                        <label class="form-label">Bình luận gốc:</label>
                        <p id="originalComment" class="form-control-plaintext"></p>
                    </div>
                    <div class="reply-form">
                        <form id="replyForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nội dung trả lời:</label>
                                <textarea class="form-control" name="reply" rows="3" required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Gửi trả lời</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete comment handler
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const commentId = this.dataset.commentId;

                    Swal.fire({
                        title: 'Xác nhận xóa?',
                        text: "Bạn không thể hoàn tác sau khi xóa!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/comments/${commentId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const row = document.querySelector(
                                            `tr[data-comment-id="${commentId}"]`);
                                        row.remove();
                                        updateCommentCount(-1);
                                        Swal.fire('Đã xóa!', 'Bình luận đã được xóa.',
                                            'success');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire('Lỗi!', 'Không thể xóa bình luận.',
                                        'error');
                                });
                        }
                    });
                });
            });

            // Helper function to update comment count
            function updateCommentCount(change) {
                const totalElement = document.querySelector('.text-muted');
                if (totalElement) {
                    const numbers = totalElement.textContent.match(/(\d+)/g);
                    if (numbers && numbers.length >= 3) {
                        const firstItem = parseInt(numbers[0]);
                        const lastItem = parseInt(numbers[1]);
                        const currentCount = parseInt(numbers[2]) + change;
                        totalElement.textContent =
                            `Hiển thị ${firstItem} đến ${lastItem} của ${currentCount} bình luận`;
                    }
                }
            }
            // Reply modal
            const replyModal = document.getElementById('replyModal');
            replyModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const commentId = button.dataset.commentId;
                const commentContent = button.dataset.commentContent;

                document.getElementById('originalComment').textContent = commentContent;
                document.getElementById('replyForm').action = `/admin/comments/${commentId}/reply`;
            });

            document.getElementById('replyForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const commentId = this.action.split('/').slice(-2)[0];

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the comment row from table
                            const commentRow = document.querySelector(
                                `tr[data-comment-id="${commentId}"]`);
                            if (commentRow) {
                                commentRow.remove();
                            }

                            // Close modal and remove backdrop
                            $('#replyModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');

                            // Reset form
                            this.reset();

                            // Show success message
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Đã trả lời bình luận',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location
                                    .reload();
                            });

                            // Update comment count
                            updateCommentCount();
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Không thể gửi trả lời.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Lỗi!', 'Không thể gửi trả lời.', 'error');
                    });
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        .child-comment td {
            background-color: #f8f9fa;
        }

        .reply-indicator {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .child-comment .comment-content {
            position: relative;
        }
    </style>
@endpush
