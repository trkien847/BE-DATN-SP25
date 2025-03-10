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
</style>

<div class="container">
  <div class="form-container">
    <h4 class="text-secondary mb-4">THÊM GIÁ NHẬP BIẾN THỂ VÀ THỜI GIAN NHẬP SẢN PHẨM</h4>

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
        <label class="form-label">Chọn sản phẩm và biến thể</label>
        <div class="product-list">
          @foreach($products as $product)
          <div class="product-item">
            <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox" data-product-id="{{ $product->id }}">
            <label>{{ $product->name }} (SKU: {{ $product->sku }})
              @if($product->import_at)
              <span class="text-muted">(Đã nhập: {{ \Carbon\Carbon::parse($product->import_at)->format('d/m/Y H:i') }})</span>
              @else
              <span class="text-muted">(Chưa nhập)</span>
              @endif
            </label>
            @if($product->variants->isNotEmpty())
            <div class="variant-list" id="variants-{{ $product->id }}">
              @foreach($product->variants as $variant)
              <div class="variant-item">
                <input type="checkbox" name="variants[]" value="{{ $variant->id }}" class="variant-checkbox" data-product-id="{{ $product->id }}" data-price="{{ $variant->price }}">
                <label>{{ $variant->sku }} -
                  @foreach($variant->attributeValues as $attrValue)
                  {{ $attrValue->attribute->name }}: {{ $attrValue->attribute->slug }}{{ $attrValue->value }} giá({{ number_format($variant->price, 0, ',', '.') }})
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

      <button type="submit" class="btn btn-primary">Lưu</button>
      <a href="{{ route('products.list') }}" class="btn btn-secondary">Quay lại</a>
    </form>
  </div>

  <h4 class="text-secondary mb-4">Những sản phẩm đã có ngày nhập</h4>
  <div class="imported-list">
    @if($importedProducts->isNotEmpty())
    @foreach($importedProducts as $imported)
    <div>
      <strong>{{ \Carbon\Carbon::parse($imported->import_at)->format('d/m/Y H:i') }}</strong>:
      Những sản phẩm đã nhập: ({{ $imported->product_names }})
    </div>
    @endforeach
    @else
    <div>Chưa có sản phẩm nào được nhập.</div>
    @endif
  </div>
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
        const inputHtml = `
          <div class="price-input">
            <label>${variantLabel}</label>
            <input type="number" name="import_prices[${variantId}]" class="form-control import-price-input" placeholder="Nhập giá cho ${variantLabel}" step="0.01" required data-price="${variantPrice}">
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
    $('.import-price-input').each(function() {
      const importPrice = parseFloat($(this).val());
      const sellPrice = parseFloat($(this).data('price'));
      
      if (importPrice > sellPrice) {
        hasError = true;
        Swal.fire({
          icon: 'error',
          title: 'Lỗi!',
          text: `Giá nhập (${importPrice}) không được lớn hơn giá bán (${sellPrice}) cho biến thể: ${$(this).prev('label').text()}`,
          confirmButtonText: 'OK'
        });
        return false; 
      }
    });

    if (hasError) {
      e.preventDefault(); 
    }
  });
});
</script>
@endsection