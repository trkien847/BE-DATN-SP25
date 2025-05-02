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
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $product->image_url ?? asset('images/default-product.png') }}" class="rounded me-2"
                            width="48" height="48" style="object-fit: cover;">
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
                        <span class="badge bg-warning">{{ $product->pending_comments_count }}</span>
                    @else
                        <span class="badge bg-success">0</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.products.comments', $product->id) }}" class="btn btn-soft-info btn-sm">
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
