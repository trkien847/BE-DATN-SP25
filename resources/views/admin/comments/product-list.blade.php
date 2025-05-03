@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Quản lý bình luận theo sản phẩm</h4>
                </div>
                <div class="card-body">
                    <!-- Search box -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="searchProduct" class="form-control"
                                    placeholder="Tìm kiếm sản phẩm...">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-primary">
                                <i class="bx bx-comment"></i> Xem tất cả bình luận đợi duyệt
                                <span class="pending-comment-badge badge bg-danger ms-2" id="pendingCommentCount"
                                    style="{{ $pendingCount > 0 ? '' : 'display: none;' }}">{{ $pendingCount }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th class="text-center">Số bình luận</th>
                                    <th class="text-center">Bình luận chờ duyệt</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr data-product-id="{{ $product->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $product->thumbnail ?? asset('images/default-product.png') }}"
                                                    class="rounded me-2" width="48" height="48"
                                                    style="object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($product->categories->isNotEmpty())
                                                @foreach ($product->categories as $category)
                                                    <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                                @endforeach
                                            @endif

                                            @if ($product->categoryTypes->isNotEmpty())
                                                @foreach ($product->categoryTypes as $type)
                                                    <span class="badge bg-info text-white me-1">{{ $type->name }}</span>
                                                @endforeach
                                            @endif

                                            @if ($product->categories->isEmpty() && $product->categoryTypes->isEmpty())
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $product->comments_count }}</td>
                                        <td class="text-center">
                                            @if ($product->pending_comments_count > 0)
                                                <span
                                                    class="badge bg-warning">{{ $product->pending_comments_count }}</span>
                                            @else
                                                <span class="badge bg-success">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.products.comments', $product->id) }}"
                                                class="btn btn-soft-info btn-sm">
                                                <i class="bx bx-message-square-detail"></i> Xem bình luận
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có sản phẩm nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị {{ $products->firstItem() ?? 0 }} đến {{ $products->lastItem() ?? 0 }}
                            của {{ $products->total() ?? 0 }} sản phẩm
                        </div>
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchProduct');
            let timeoutId;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    const searchText = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', searchText);

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            document.querySelector('.table-responsive').innerHTML =
                                doc.querySelector('.table-responsive').innerHTML;
                            document.querySelector('.d-flex.justify-content-between')
                                .innerHTML =
                                doc.querySelector('.d-flex.justify-content-between').innerHTML;

                            // Update URL without refreshing page
                            window.history.pushState({}, '', url);
                        })
                        .catch(error => console.error('Error:', error));
                }, 300);
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true
            });

            const channel = pusher.subscribe('comments');

            channel.bind('App\\Events\\CommentPosted', function(data) {
                // Update global pending count badge
                const badge = document.querySelector('.pending-comment-badge');
                let currentCount = badge ? parseInt(badge.textContent || '0') : 0;
                currentCount++;

                if (badge) {
                    badge.textContent = currentCount;
                    badge.style.display = '';
                    badge.classList.add('badge-pulse');
                    setTimeout(() => {
                        badge.classList.remove('badge-pulse');
                    }, 1000);
                }

                // Update product specific pending count
                if (data.product_id) {
                    const productRow = document.querySelector(`tr[data-product-id="${data.product_id}"]`);
                    if (productRow) {
                        const pendingBadge = productRow.querySelector('td:nth-child(4) .badge');
                        if (pendingBadge) {
                            let count = parseInt(pendingBadge.textContent || '0');
                            count++;
                            pendingBadge.textContent = count;
                            pendingBadge.classList.remove('bg-success');
                            pendingBadge.classList.add('bg-warning', 'badge-pulse');
                            setTimeout(() => {
                                pendingBadge.classList.remove('badge-pulse');
                            }, 1000);
                        }
                    }
                }

                // Show notification
                Toastify({
                    text: "Có bình luận mới chờ duyệt!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #4caf50, #81c784)",
                    },
                }).showToast();
            });
        });
    </script>
@endpush
@push('styles')
    <style>
        .pending-comment-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1;
            border-radius: 10px;
        }

        @keyframes badge-pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .badge-pulse {
            animation: badge-pulse 1s ease-in-out;
        }
    </style>
@endpush
