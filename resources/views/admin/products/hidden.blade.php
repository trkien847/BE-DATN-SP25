@extends('admin.layouts.layout')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container">
    <div class="d-flex flex-wrap justify-content-between gap-3">
        <h4 class="text-secondary">DANH SÁCH SẢN PHẨM ĐÃ ẨN</h4>
        <a href="{{ route('products.list') }}" class="btn btn-primary" id="back-to-list">
            <i class="bx bx-arrow-back me-1"></i> Quay Lại Danh Sách
        </a>
    </div>

   
    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            timer: 3000, 
            timerProgressBar: true
        });
    });
    </script>
    @endif

    <table class="table table-hover table-bordered align-middle">
        <thead>
            <tr>
                <th scope="col">Mã SP</th>
                <th scope="col">Tên Sản Phẩm</th>
                <th scope="col">Biến thể</th>
                <th scope="col">Ảnh</th>
                <th scope="col">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="product-row" data-id="{{ $product->id }}">
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @foreach($product->variants as $variant)
                    <div>
                        <strong>SKU:</strong>
                        @foreach($variant->attributeValues as $value)
                        {{ $value->attribute->name }}: {{ $value->attribute->slug }} {{ $value->value }}
                        @endforeach <br>
                        <strong>Giá:</strong> {{ number_format($variant->price, 0, ',', '.') }} VND <br>
                        <strong>Giá KM:</strong> {{ number_format($variant->sale_price, 0, ',', '.') }} VND <br>
                        <strong>Số lượng:</strong> {{ $variant->stock }} <br>
                    </div>
                    <hr>
                    @endforeach
                </td>
                <td>
                    <img src="{{ asset('upload/'.$product->thumbnail) }}" class="img-thumbnail" alt="Product Image" width="100px" height="100px">
                </td>
                <td>
                    <form action="{{ route('products.restore', $product->id) }}" method="POST" class="d-inline restore-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bx bx-show fs-16"></i> Khôi Phục
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        {{ $products->links('pagination::bootstrap-5') }}
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.restore-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 

            const productRow = this.closest('.product-row');
            const backButton = document.getElementById('back-to-list');
            const form = this;

            
            const rowRect = productRow.getBoundingClientRect();
            const backRect = backButton.getBoundingClientRect();
            const translateX = backRect.left - rowRect.left;
            const translateY = backRect.top - rowRect.top;

            
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Bạn có muốn khôi phục sản phẩm này không?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có, khôi phục!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    productRow.style.transition = 'all 0.6s ease';
                    productRow.style.transform = `translate(${translateX}px, ${translateY}px) scale(0.1)`;
                    productRow.style.opacity = '0';

                    
                    setTimeout(() => {
                        form.submit();
                    }, 600); 
                }
            });
        });
    });
});
</script>
@endsection