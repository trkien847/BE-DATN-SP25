@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quản lý bình luận</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchComment"
                                    placeholder="Tìm kiếm bình luận...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover" id="commentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th style="width: 150px;">Người dùng</th>
                                    <th style="width: 200px;">Sản phẩm</th>
                                    <th>Nội dung</th>
                                    <th style="width: 150px;">Thời gian</th>
                                    <th class="text-center" style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comments as $comment)
                                    <tr data-comment-id="{{ $comment->id }}">
                                        <td class="text-center">{{ $comment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                                                    class="rounded-circle me-2" width="32">
                                                <div>
                                                    <h6 class="mb-0">{{ $comment->user->fullname ?? 'Ẩn danh' }}</h6>
                                                    <small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('products.productct', $comment->product_id) }}"
                                                class="text-body" target="_blank">
                                                {{ Str::limit($comment->product->name, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $comment->content }}</td>
                                        <td>{{ $comment->created_at->format('H:i, d/m/Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-soft-primary btn-sm reply-btn"
                                                    data-bs-toggle="modal" data-bs-target="#replyModal"
                                                    data-comment-id="{{ $comment->id }}"
                                                    data-comment-content="{{ $comment->content }}">
                                                    <i class="bx bx-reply"></i>
                                                </button>
                                                <button type="button" class="btn btn-soft-danger btn-sm delete-btn"
                                                    data-comment-id="{{ $comment->id }}">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Không có bình luận nào</td>
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

    <!-- Reply Modal -->
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
            // Search functionality
            const searchInput = document.getElementById('searchComment');
            searchInput.addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('#commentsTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });

            // Delete comment
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
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        const row = document.querySelector(
                                            `tr[data-comment-id="${commentId}"]`);
                                        row.remove();
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
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('comments');

            channel.bind('App\\Events\\CommentPosted', function(data) {
                // Tạo HTML cho comment mới
                const newCommentHtml = `
                        <tr data-comment-id="${data.id}">
                            <td class="text-center">${data.id}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${data.user.avatar}" class="rounded-circle me-2" width="32">
                                    <div>
                                        <h6 class="mb-0">${data.user.name}</h6>
                                        <small class="text-muted">${data.user.email}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="/products/${data.product.id}" class="text-body" target="_blank">
                                    ${data.product.name}
                                </a>
                            </td>
                            <td>${data.content}</td>
                            <td>${data.created_at}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-soft-primary btn-sm reply-btn"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#replyModal"
                                            data-comment-id="${data.id}"
                                            data-comment-content="${data.content}">
                                        <i class="bx bx-reply"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-soft-danger btn-sm delete-btn"
                                            data-comment-id="${data.id}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;

                // Thêm comment mới vào đầu bảng
                const tbody = document.querySelector('#commentsTable tbody');
                const noComments = tbody.querySelector('tr td[colspan="6"]');

                if (noComments) {
                    tbody.innerHTML = newCommentHtml;
                } else {
                    tbody.insertAdjacentHTML('afterbegin', newCommentHtml);
                }

                // Cập nhật số lượng comment
                const totalElement = document.querySelector('.text-muted');
                if (totalElement) {
                    const numbers = totalElement.textContent.match(/(\d+)/g);
                    if (numbers && numbers.length >= 3) {
                        const firstItem = parseInt(numbers[0]);
                        const lastItem = parseInt(numbers[1]);
                        const currentCount = parseInt(numbers[2]) + 1;
                        totalElement.textContent =
                            `Hiển thị ${firstItem} đến ${lastItem} của ${currentCount} bình luận`;
                    }
                }

                // Thông báo có comment mới
                Toastify({
                    text: "Có bình luận mới!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4caf50",
                }).showToast();
            });

            // Add this helper function for updating comment count
            function updateCommentCount() {
                const totalElement = document.querySelector('.text-muted');
                if (totalElement) {
                    const numbers = totalElement.textContent.match(/(\d+)/g);
                    if (numbers && numbers.length >= 3) {
                        const firstItem = parseInt(numbers[0]);
                        const lastItem = parseInt(numbers[1]);
                        const currentCount = parseInt(numbers[2]) - 1;
                        totalElement.textContent =
                            `Hiển thị ${firstItem} đến ${lastItem} của ${currentCount} bình luận`;
                    }
                }
            }

        });
    </script>
@endpush
@push('styles')
    <!-- Add this in your layout header -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endpush
