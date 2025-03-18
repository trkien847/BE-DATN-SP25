@extends('admin.layouts.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  .form-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .price-input {
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    background-color: #fff;
    transition: opacity 0.3s ease, transform 0.2s ease;
  }

  .price-input input {
    flex: 1;
  }

  .product-list,
  .variant-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ced4da;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
  }
  .price-input {
  margin-top: 10px;
  display: flex;
  flex-wrap: wrap; /* Cho phép các input xuống dòng nếu không đủ chỗ */
  align-items: center;
  gap: 10px;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 5px;
  background-color: #fff;
  transition: opacity 0.3s ease, transform 0.2s ease;
}

.price-input input {
  flex: 1;
  min-width: 150px; /* Đảm bảo input không quá nhỏ */
}
  .product-item,
  .variant-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    padding: 5px;
    transition: background-color 0.3s ease;
  }

  .product-item:hover,
  .variant-item:hover {
    background-color: #f5f5f5;
  }

  .variant-list {
    margin-left: 20px;
    display: none;
  }

  .imported-list {
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #ced4da;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    background-color: #f8f9fa;
  }

  .input-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.left-section, .right-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.input-wrapper {
    display: flex;
    align-items: center;
    gap: 5px;
}

.input-unit {
    width: 100px;
    text-align: right;
}

.form-control {
    flex: 1;
}


.details-column {
    padding: 10px;
    max-width: 400px; /* Giới hạn chiều rộng để tránh tràn */
    overflow-x: auto; /* Cho phép cuộn ngang nếu nội dung dài */
}

.import-details-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.import-detail-item {
    display: flex;
    justify-content: space-between; /* Phân bố đều các phần */
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #eee; /* Đường phân cách */
    background-color: #fff;
    transition: background-color 0.3s;
}

.import-detail-item:hover {
    background-color: #f9f9f9; /* Hiệu ứng hover */
}

.detail-product {
    font-weight: bold;
    color: #2c3e50;
    flex: 2;
    white-space: nowrap; /* Ngăn ngắt dòng */
    overflow: hidden;
    text-overflow: ellipsis; /* Thêm dấu ... nếu tràn */
}

.detail-variant {
    color: #34495e;
    flex: 2;
    margin-left: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.detail-quantity {
    color: #27ae60;
    font-weight: 500;
    flex: 1;
    text-align: right;
    white-space: nowrap;
}

/* Đảm bảo bảng không bị tràn */
table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    padding: 10px;
    border: 1px solid #ddd;
}

.summary-column {
    padding: 10px;
    text-align: right;
    vertical-align: middle;
}

.summary-column p {
    margin: 5px 0;
    color: #2c3e50;
}

.summary-column strong {
    color: #e74c3c; /* Màu nổi bật cho tiêu đề */
}

.header-row {
    display: flex;
    align-items: center; /* Căn giữa theo chiều dọc */
    gap: 500px; /* Khoảng cách giữa tiêu đề và hình ảnh */
}

.header-image {
    width: 300px; /* Điều chỉnh kích thước hình ảnh */
    height: 150px; /* Điều chỉnh kích thước hình ảnh */
    object-fit: contain; /* Đảm bảo hình ảnh không bị méo */
}
</style>

<div class="container">
  <div class="form-container">
  <div class="header-row">
      <h4 class="text-secondary mb-4">NHẬP SẢN PHẨM</h4>
      <img src="https://media4.giphy.com/media/Um3ljJl8jrnHy/giphy.webp?cid=ecf05e47a67hlasek37i2ut7sg8u5psxkkovu16o250uamnn&ep=v1_gifs_search&rid=giphy.webp&ct=g" alt="Import Icon" class="header-image">
  </div>

    @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'success',
          title: 'Thành công!',
          text: '{{ session('success') }}',
          confirmButtonText: 'OK'
        });
      });
    </script>
    @endif

    <form id="importForm" action="{{ route('products.import.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="import_at" class="form-label">Thời gian nhập</label>
        <input type="datetime-local" name="import_at" id="import_at" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Chọn sản phẩm heheboy</label>
        <div class="product-list">
          @foreach($products as $product)
          <div class="product-item">
            <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox" data-product-id="{{ $product->id }}">
            <label>{{ $product->name }} (Mã sản phẩm: {{ $product->sku }})</label>
            @if($product->variants->isNotEmpty())
            <div class="variant-list" id="variants-{{ $product->id }}">
              @foreach($product->variants as $variant)

              <div class="variant-item">
              <input type="checkbox" name="variants[{{ $variant->id }}]" value="{{ $variant->id }}" class="variant-checkbox" 
                data-price="{{ $variant->price }}" 
                data-sale-price="{{ $variant->sale_price ?? '' }}" 
                data-stock="{{ $variant->stock }}" 
                data-sale-start="{{ $variant->sale_price_start_at ? \Carbon\Carbon::parse($variant->sale_price_start_at)->format('Y-m-d\TH:i') : '' }}" 
                data-sale-end="{{ $variant->sale_price_end_at ? \Carbon\Carbon::parse($variant->sale_price_end_at)->format('Y-m-d\TH:i') : '' }}"
                data-product-name="{{ $variant->product->name ?? 'Không có tên' }}">
                <label>
                  @foreach($variant->attributeValues as $attrValue)
                  {{ $attrValue->attribute->name }}: {{ $attrValue->attribute->slug }}{{ $attrValue->value }} 
                  @endforeach
                </label>
              </div>

              @endforeach
            </div>
            @endif
          </div>
          @endforeach
        </div>
      </div>

      <div id="price-inputs" class="mb-3" style="display: none;"></div>

      <button type="submit" class="btn btn-primary">Xác nhân</button>
      <a href="{{ route('products.list') }}" class="btn btn-secondary">Quay lại</a>
    </form>
  </div>

  <h2>Lịch sử nhập hàng</h2>
<table class="table">
    <thead>
        <tr>
            <th>Ngày Báo</th>
            <th>Người báo</th>
            <th>Những sản phẩm trên thớt</th>
            <th>Giá Thất thoát tổng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($importedProducts as $import)
            <tr>
                <td>{{ $import->imported_at }}</td>
                <td>{{ $import->imported_by }}</td>
                <td class="details-column">
                    <ul class="import-details-list">
                        @foreach ($import->details as $detail)
                            <li class="import-detail-item">
                                <span class="detail-product">{{ $detail->product->name }}</span>
                                <span class="detail-variant">Biến thể: {{ $detail->name_vari }}</span>
                                <span class="detail-variant">Giá nhập: {{ number_format($detail->price, 0, ',', '.') }} VND</span>
                                <span class="detail-quantity">Số lượng: {{ $detail->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td class="summary-column">
                    <p><strong>Số tiền thất thoát:</strong> {{ number_format($import->details->sum(function ($detail) {
                        return $detail->price * $detail->quantity;
                    }), 0, ',', '.') }} VND</p>
                    <p><strong>Số lượng nhận lại:</strong> {{ $import->details->sum('quantity') }}</p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
  $('#import_at').on('change', function() {
    $(this).animate({
      borderColor: '#007bff'
    }, 300).animate({
      borderColor: '#ced4da'
    }, 300);
  });

  $('.product-checkbox').on('change', function() {
    const productId = $(this).data('product-id');
    const variantList = $(`#variants-${productId}`);
    
    if ($(this).is(':checked')) {
      variantList.slideDown(300);
      $(this).parent('.product-item').css('background-color', '#e9f7ff');
    } else {
      variantList.slideUp(300);
      variantList.find('.variant-checkbox').prop('checked', false);
      $(this).parent('.product-item').css('background-color', '');
      updatePriceInputs();
    }
  });

  $('.variant-checkbox').on('change', function() {
    updatePriceInputs();
  });

  
  function updatePriceInputs() {
    const priceInputs = $('#price-inputs');
    const selectedVariants = $('.variant-checkbox:checked');
    
    if (selectedVariants.length > 0) {
        priceInputs.empty().slideDown(300);
        
        selectedVariants.each(function() {
            const variantId = $(this).val();
            const variantLabel = $(this).next('label').text();
            const variantPrice = $(this).data('price');
            const variantSalePrice = $(this).data('sale-price') || ''; 
            const variantStock = $(this).data('stock');
            const variantSaleStart = $(this).data('sale-start') || ''; // Giá trị thời gian bắt đầu hiện tại
            const variantSaleEnd = $(this).data('sale-end') || '';     // Giá trị thời gian kết thúc hiện tại
            const productName = $(this).data('product-name') || 'Không xác định'; // Tên sản phẩm
            
            // Đặt giá trị mặc định cho stock là 0 nếu đã có stock > 0
            const defaultStock = variantStock > 0 ? 0 : variantStock;

            const inputHtml = `
                <div class="price-input">
                    <div class="variant-info">
                        <label class="variant-label">${productName} - ${variantLabel}</label>
                        <span class="variant-hint">Thông tin hiện tại: Giá bán: ${Number(variantPrice).toLocaleString('vi-VN')} ₫ 
                            ${variantSalePrice ? '| Giá sale: ' + Number(variantSalePrice).toLocaleString('vi-VN') + ' ₫' : ''} 
                            | Tồn kho: ${variantStock}</span>
                    </div>
                    <div class="input-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <!-- Bên trái: Số lượng và Thời gian -->
                        <div class="left-section">
                            <div class="input-wrapper">
                                <span class="input-unit">Số lượng</span>
                                <input type="number" name="quantities[${variantId}]" 
                                       class="form-control stock-input" 
                                       placeholder="Số lượng mới" 
                                       step="1" 
                                       min="0" 
                                       value="${defaultStock}" 
                                       required>
                            </div>
                            <div class="sale-date-container">
                                <div class="input-wrapper">
                                    <span class="input-unit">Thời gian bắt đầu</span>
                                    <input type="datetime-local" name="sale_price_start_at[${variantId}]" 
                                           class="form-control sale-start-input" 
                                           value="${variantSaleStart || ''}">
                                </div>
                                <div class="input-wrapper">
                                    <span class="input-unit">Thời gian kết thúc</span>
                                    <input type="datetime-local" name="sale_price_end_at[${variantId}]" 
                                           class="form-control sale-end-input" 
                                           value="${variantSaleEnd || ''}">
                                </div>

                                 <div class="input-wrapper" style="display: none;">
                                    <input type="text" name="name_vars[${variantId}]" 
                                           class="form-control sale-end-input" 
                                           value="${variantLabel}">
                                </div>
                            </div>
                        </div>
                        <!-- Bên phải: Giá nhập, Giá bán, Giá sale -->
                        <div class="right-section">
                            <div class="input-wrapper">
                                <span class="input-unit">Giá nhập</span>
                                <input type="number" name="import_prices[${variantId}]" 
                                       class="form-control import-price-input" 
                                       placeholder="Giá nhập mới" 
                                       step="0.01" 
                                       min="0" 
                                       required 
                                       data-price="${variantPrice}">
                            </div>
                            <div class="input-wrapper">
                                <span class="input-unit">Giá bán</span>
                                <input type="number" name="prices[${variantId}]" 
                                       class="form-control price-input" 
                                       placeholder="Giá bán mới" 
                                       step="0.01" 
                                       min="0" 
                                       value="${variantPrice}" 
                                       required>
                            </div>
                            <div class="input-wrapper">
                                <span class="input-unit">Giá sale</span>
                                <input type="number" name="sale_prices[${variantId}]" 
                                       class="form-control sale-price-input" 
                                       placeholder="Giá sale mới" 
                                       step="0.01" 
                                       min="0" 
                                       value="${variantSalePrice || ''}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            priceInputs.append(inputHtml);
        });
        
        $('#price-inputs .price-input').hide().fadeIn(300);
    } else {
        priceInputs.slideUp(300);
    }
}



  $('#importForm').on('submit', function(e) {
    let hasError = false;
    let errorMessage = '';

    // Check each variant's pricing and stock
    $('.price-input').each(function() {
      const $container = $(this);
      const variantLabel = $container.find('.variant-label').text();
      const importPrice = parseFloat($container.find('.import-price-input').val()) || 0;
      const sellPrice = parseFloat($container.find('.price-input').val()) || 0;
      const salePrice = parseFloat($container.find('.sale-price-input').val()) || 0;
      const stock = parseFloat($container.find('.stock-input').val()) || 0;

      // Check if selling price is less than import price
      if (sellPrice < importPrice) {
        hasError = true;
        errorMessage += `Giá bán (${sellPrice.toLocaleString('vi-VN')} ₫) nhỏ hơn giá nhập (${importPrice.toLocaleString('vi-VN')} ₫) cho biến thể: ${variantLabel}\n`;
      }

      // Check for negative values
      if (importPrice < 0) {
        hasError = true;
        errorMessage += `Giá nhập không được âm (${importPrice.toLocaleString('vi-VN')} ₫) cho biến thể: ${variantLabel}\n`;
      }
      if (sellPrice < 0) {
        hasError = true;
        errorMessage += `Giá bán không được âm (${sellPrice.toLocaleString('vi-VN')} ₫) cho biến thể: ${variantLabel}\n`;
      }
      if (salePrice < 0) {
        hasError = true;
        errorMessage += `Giá sale không được âm (${salePrice.toLocaleString('vi-VN')} ₫) cho biến thể: ${variantLabel}\n`;
      }
      if (stock < 0) {
        hasError = true;
        errorMessage += `Số lượng không được âm (${stock}) cho biến thể: ${variantLabel}\n`;
      }
    });

    if (hasError) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Lỗi nhập liệu!',
        text: errorMessage,
        confirmButtonText: 'OK',
        whiteSpace: 'pre-wrap' // Allows line breaks in the message
      });
      return false;
    }
  });
});
</script>
@endsection