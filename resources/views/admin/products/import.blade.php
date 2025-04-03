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
  flex-wrap: wrap;
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
  min-width: 150px; 
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
    max-width: 400px; 
    overflow-x: auto; 
}

.import-details-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.import-detail-item {
    display: flex;
    justify-content: space-between; 
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #eee; 
    background-color: #fff;
    transition: background-color 0.3s;
}

.import-detail-item:hover {
    background-color: #f9f9f9; 
}

.detail-product {
    font-weight: bold;
    color: #2c3e50;
    flex: 2;
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
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
    color: #e74c3c;
}

.header-row {
    display: flex;
    align-items: center; 
    gap: 400px; 
}

.header-image {
    width: 300px; 
    height: 150px;
    object-fit: contain;
}

.welcome-title {
    position: relative;
    display: inline-block;
    overflow: hidden;
}

.text-container {
    display: inline-block;
}

.char {
    display: inline-block;
    font-size: 2rem;
    color: #000;
}

.history-text {
    opacity: 0;
}

.welcome-title.animate .text-container {
    animation: fadeIn 10s forwards;
}

.car {
    width: 50px;
    height: auto;
    transition: transform 10s linear;
}

.welcome-title.animate .car {
    transform: translateX(100vw);
}

@keyframes fadeIn {
    0% { opacity: 0; }
    10% { opacity: 1; }
    100% { opacity: 1; }
}
</style>
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
<div class="container">



  <div id="importFormzz" class="form-container" style="display: none;">
    <div class="header-row">
        <h4 class="text-secondary mb-4">NHẬP SẢN PHẨM</h4>
        <img src="https://media4.giphy.com/media/Um3ljJl8jrnHy/giphy.webp?cid=ecf05e47a67hlasek37i2ut7sg8u5psxkkovu16o250uamnn&ep=v1_gifs_search&rid=giphy.webp&ct=g" alt="Import Icon" class="header-image">
    </div>

      <form id="importForm" action="{{ route('products.import.store') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="import_at" class="form-label">Thời gian nhập</label>
          <input type="datetime-local" name="import_at" id="import_at" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Chọn sản phẩm heheboy</label>

          <div class="product-list" style="margin-top: 5px;">
              <!-- Sản phẩm đã nhập -->
                    <h5 class="text-success mt-4">Sản phẩm đã nhập</h5>
                    @if($importedProductsList->isNotEmpty())
                        @foreach($importedProductsList as $product)
                            <div class="product-item">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox" data-product-id="{{ $product->id }}">
                                <label>{{ $product->name }} (Mã sản phẩm: {{ $product->sku }})</label>
                                @if($product->variants->isNotEmpty())
                                    <div class="variant-list" id="variants-{{ $product->id }}">
                                        @foreach($product->variants as $variant)
                                            <div class="variant-item">
                                                <input type="checkbox" name="variants[{{ $variant->id }}]" value="{{ $variant->id }}" class="variant-checkbox" 
                                                    data-import-price="{{ $variant->import_price }}" 
                                                    data-price="{{ $variant->price }}" 
                                                    data-sale-price="{{ $variant->sale_price ?? '' }}" 
                                                    data-stock="{{ $variant->stock }}" 
                                                    data-sale-start="{{ $variant->sale_price_start_at ? \Carbon\Carbon::parse($variant->sale_price_start_at)->format('Y-m-d\TH:i') : '' }}" 
                                                    data-sale-end="{{ $variant->sale_price_end_at ? \Carbon\Carbon::parse($variant->sale_price_end_at)->format('Y-m-d\TH:i') : '' }}"
                                                    data-product-name="{{ $variant->product->name ?? 'Không có tên' }}"
                                                    {{ in_array($variant->id, $importedVariantIds) ? 'checked' : '' }}>
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
                    @else
                        <p class="text-muted">Không có sản phẩm đã nhập.</p>
                    @endif

                    <!-- Sản phẩm chưa nhập -->
                    <h5 class="text-danger mt-4">Sản phẩm chưa nhập</h5>
                    @if($notImportedProductsList->isNotEmpty())
                        @foreach($notImportedProductsList as $product)
                            <div class="product-item">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}" class="product-checkbox" data-product-id="{{ $product->id }}">
                                <label>{{ $product->name }} (Mã sản phẩm: {{ $product->sku }})</label>
                                @if($product->variants->isNotEmpty())
                                    <div class="variant-list" id="variants-{{ $product->id }}">
                                        @foreach($product->variants as $variant)
                                            <div class="variant-item">
                                                <input type="checkbox" name="variants[{{ $variant->id }}]" value="{{ $variant->id }}" class="variant-checkbox" 
                                                    data-import-price="{{ $variant->import_price }}" 
                                                    data-price="{{ $variant->price }}" 
                                                    data-sale-price="{{ $variant->sale_price ?? '' }}" 
                                                    data-stock="{{ $variant->stock }}" 
                                                    data-sale-start="{{ $variant->sale_price_start_at ? \Carbon\Carbon::parse($variant->sale_price_start_at)->format('Y-m-d\TH:i') : '' }}" 
                                                    data-sale-end="{{ $variant->sale_price_end_at ? \Carbon\Carbon::parse($variant->sale_price_end_at)->format('Y-m-d\TH:i') : '' }}"
                                                    data-product-name="{{ $variant->product->name ?? 'Không có tên' }}"
                                                    {{ !in_array($variant->id, $importedVariantIds) ? '' : 'disabled' }}>
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
                    @else
                        <p class="text-muted">Không có sản phẩm chưa nhập.</p>
                    @endif
                </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
           
            const searchBar = `
                <div class="d-flex">
                    <input type="text" id="product-search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm theo tên hoặc mã SKU...">
                    <button id="clear-search" class="btn btn-outline-secondary" type="button">Xóa</button>
                </div>
            `;
        
            document.querySelector('.product-list').insertAdjacentHTML('beforebegin', searchBar);
            document.getElementById('product-search').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const productItems = document.querySelectorAll('.product-item');
                
                productItems.forEach(function(item) {
                    const productText = item.querySelector('label').textContent.toLowerCase();
                    if (productText.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            document.getElementById('clear-search').addEventListener('click', function() {
                document.getElementById('product-search').value = '';
                document.querySelectorAll('.product-item').forEach(function(item) {
                    item.style.display = '';
                });
            });
        });
        </script>
        <div id="price-inputs" class="mb-3" style="display: none;"></div>

        <button type="submit" class="btn btn-primary">Xác nhân</button>
        <button type="button" id="hideImportForm" class="btn btn-secondary">Hủy</button>
      </form>

      
  </div>
  
  
  <h2 class="welcome-title" id="end">
    <span class="text-container">
        <span class="char">C</span><span class="char">h</span><span class="char">à</span><span class="char">o</span>
        <span class="char"> </span>
        <span class="char">m</span><span class="char">ừ</span><span class="char">n</span><span class="char">g</span>
        <span class="char"> </span>
        <span class="char">b</span><span class="char">ạ</span><span class="char">n</span>
        <span class="char"> </span>
        <span class="char">đ</span><span class="char">ế</span><span class="char">n</span>
        <span class="char"> </span>
        <span class="char">v</span><span class="char">ớ</span><span class="char">i</span><span class="char">:</span>
    </span>
    <span class="text-container history-text" style="opacity: 0;">
        <span class="char"> </span>
        <span class="char">L</span><span class="char">ị</span><span class="char">c</span><span class="char">h</span>
        <span class="char"> </span>
        <span class="char">s</span><span class="char">ử</span>
        <span class="char"> </span>
        <span class="char">n</span><span class="char">h</span><span class="char">ậ</span><span class="char">p</span>
        <span class="char"> </span>
        <span class="char">h</span><span class="char">à</span><span class="char">n</span><span class="char">g</span>
    </span>
    <img src="https://png.pngtree.com/element_our/20190528/ourlarge/pngtree-black-truck-icon-free-illustration-image_1145193.jpg" class="car" alt="Car" style="position: absolute; left: -100px;">
</h2>
   <script>
    document.addEventListener('DOMContentLoaded', () => {
    const title = document.querySelector('.welcome-title');
    const car = document.querySelector('.car');
    const chars = document.querySelectorAll('.char');
    const historyText = document.querySelector('.history-text');
    const welcomeText = document.querySelector('.text-container:not(.history-text)');
    const colors = [
        'red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet',
        'pink', 'purple', 'cyan', 'lime', 'brown', 'magenta', 'gold'
    ];

    title.classList.add('animate');

    function updateColorsAndText() {
        const carRect = car.getBoundingClientRect();
        const carLeft = carRect.left;
        const welcomeRect = welcomeText.getBoundingClientRect();
        const welcomeRight = welcomeRect.right;

        if (carLeft > welcomeRight && historyText.style.opacity === '0') {
            historyText.style.opacity = '1';
            historyText.style.transition = 'opacity 0.5s ease';
        }

        chars.forEach((char, index) => {
            const charRect = char.getBoundingClientRect();
            const charLeft = charRect.left;

            if (carLeft > charLeft) {
                char.style.color = colors[index % colors.length];
            }
        });

        if (carLeft < window.innerWidth) {
            requestAnimationFrame(updateColorsAndText);
        }
    }

    requestAnimationFrame(updateColorsAndText);
});
   </script>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form id="searchForm" class="mb-3">
        <div class="row gx-2 align-items-end">
            <div class="col-md-auto">
                <label for="from_date" class="form-label">Từ ngày:</label>
                <input type="date" id="from_date" name="from_date" class="form-control">
            </div>
            <div class="col-md-auto">
                <label for="to_date" class="form-label">Đến ngày:</label>
                <input type="date" id="to_date" name="to_date" class="form-control">
            </div>
            <div class="col-md-auto">
                <label for="imported_by" class="form-label">Người nhập:</label>
                <input type="text" id="imported_by" name="imported_by" class="form-control" placeholder="Nhập tên">
            </div>
            <div class="col-md-auto">
                <button type="button" id="searchBtn" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <button id="showImportForm" class="btn btn-success">Nhập hàng</button>
</div>


<table class="table" id="tablesss">
    <thead>
        <tr>
            <th>Ngày nhập</th>
            <th>Người nhập</th>
            <th>Số tiền thất thoát</th>
            <th>Số lượng nhận lại</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody id="importTableBody">
    @foreach ($importedProducts as $import)
            <tr>
                <td>{{ $import->imported_at }}</td>
                <td>{{ $import->imported_by }}</td>
                <td>
                    {{ number_format($import->details->sum(function ($detail) {
                        return $detail->price * $detail->quantity;
                    }), 0, ',', '.') }} VND
                </td>
                <td>{{ $import->details->sum('quantity') }}</td>
                <td>
                    @if ($import->is_active == 0)
                        <span class="badge text-bg-warning">Đang chờ cấp trên bị lừa</span>
                    @elseif ($import->is_active == 1)
                        <span class="badge text-bg-success">Cấp trên đã bị lừa</span>
                    @elseif ($import->is_active == 2)
                        <span class="badge text-bg-danger">Cấp trên khôn quá</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary btn-sm view-details" 
                        data-id="{{ $import->id }}" 
                        data-imported="{{ $import->imported_at }}" 
                        data-user="{{ $import->imported_by }}" 
                        data-status="{{ $import->is_active }}">
                        Xem chi tiết
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
          document.getElementById("showImportForm").addEventListener("click", function () {
              document.getElementById("importFormzz").style.display = "block";
              this.style.display = "none"; 
              document.getElementById("tablesss").style.display = "none";
              document.getElementById("searchForm").style.display = "none";
              document.getElementById("end").style.display = "none";
          });

          document.getElementById("hideImportForm").addEventListener("click", function () {
              document.getElementById("importFormzz").style.display = "none";
              document.getElementById("showImportForm").style.display = "block";
              document.getElementById("tablesss").style.display = "";
              document.getElementById("searchForm").style.display = "";
              document.getElementById("end").style.display = "";
          });
  </script>
<div class="modal fade" id="importDetailsModal" tabindex="-1" aria-labelledby="importDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importDetailsLabel">Chi Tiết Nhập Hàng (Saygex69.com)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Ngày nhập:</strong> <span id="modal-imported-at"></span></p>
                <p><strong>Người Chịu trận:</strong> <span id="modal-imported-by"></span></p>
                <p><strong>Trạng thái:</strong> <span id="modal-status"></span></p>
                
                <h5>Danh sách sản phẩm nhập(Truy cập saygex69 để tham gia vào hội ae xã đoàn nhé)</h5>
                <ul id="modal-import-details" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    // nhìn vào note mà tập giải thích

    // Hàm hiển thị trạng thái dựa trên giá trị status 
    function getStatusText(status) {
        const statusValue = parseInt(status, 10) || 0;
        console.log(statusValue);
        switch (statusValue) {
            case 0:
                return '<span class="badge text-bg-warning">Đang chờ cấp trên bị lừa</span>';
            case 1:
                return '<span class="badge text-bg-success">Cấp trên đã bị lừa</span>';
            case 2:
                return '<span class="badge text-bg-danger">Cấp trên khôn quá</span>';
            default:
                return '<span class="badge text-bg-secondary">Không xác định</span>';
        }
    }

    // Hàm hiển thị trạng thái trong modal
    function getModalStatusText(status) {
        const statusValue = parseInt(status, 10) || 0;
        switch (statusValue) {
            case 0:
                return '<span class="badge bg-warning text-dark">Đang chờ duyệt</span>';
            case 1:
                return '<span class="badge bg-success">Đã duyệt</span>';
            case 2:
                return '<span class="badge bg-danger">Bị từ chối</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }

    // Hàm render bảng nhập hàng
    function renderImportTable(imports) {
        const tableBody = document.getElementById("importTableBody");
        tableBody.innerHTML = ""; 

        if (!imports || imports.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
            return;
        }

        imports.forEach(imported => {
            const row = `
                <tr>
                    <td>${imported.imported_at}</td>
                    <td>${imported.imported_by}</td>
                    <td>${imported.total_loss} VND</td>
                    <td>${imported.total_quantity}</td>
                    <td>${getStatusText(imported.status)}</td>
                    <td>
                        <button class="btn btn-primary btn-sm view-details" 
                            data-id="${imported.id}" 
                            data-imported="${imported.imported_at}" 
                            data-user="${imported.imported_by}" 
                            data-status="${imported.status}">
                            Xem chi tiết
                        </button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // Hàm hiển thị chi tiết trong modal
    function showImportDetails(importId, importedAt, importedBy, status) {
        document.getElementById("modal-imported-at").textContent = importedAt;
        document.getElementById("modal-imported-by").textContent = importedBy;
        document.getElementById("modal-status").innerHTML = getModalStatusText(status);
        fetch(`/admin/imports/${importId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Không thể lấy chi tiết nhập hàng');
                return response.json();
            })
            .then(data => {
                const detailsList = document.getElementById("modal-import-details");
                detailsList.innerHTML = "";

                if (!data || data.length === 0) {
                    detailsList.innerHTML = '<li class="list-group-item">Không có chi tiết</li>';
                } else {
                    data.forEach(detail => {
                        const listItem = document.createElement("li");
                        listItem.classList.add("list-group-item");
                        listItem.innerHTML = `
                            <strong>Sản phẩm:</strong> ${detail.product_name} <br>
                            <strong>Biến thể:</strong> ${detail.name_vari} <br>
                            <strong>Giá nhập:</strong> ${new Intl.NumberFormat('vi-VN').format(detail.price)} VND <br>
                            <strong>Số lượng:</strong> ${detail.quantity}
                        `;
                        detailsList.appendChild(listItem);
                    });
                }
                const modal = new bootstrap.Modal(document.getElementById('importDetailsModal'));
                modal.show();
            })
            .catch(error => {
                console.error("Lỗi khi lấy chi tiết:", error);
                alert("Đã có lỗi xảy ra khi lấy chi tiết nhập hàng. Vui lòng thử lại!");
            });
    }

    // Hàm tìm kiếm nhập hàng
    function searchImports() {
        const fromDate = document.getElementById("from_date").value;
        const toDate = document.getElementById("to_date").value;
        const importedBy = document.getElementById("imported_by").value;

        fetch("{{ route('products.import.search') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ from_date: fromDate, to_date: toDate, imported_by: importedBy })
        })
        .then(response => {
            if (!response.ok) throw new Error('Không thể tìm kiếm dữ liệu');
            return response.json();
        })
        .then(data => {
            renderImportTable(data);
        })
        .catch(error => {
            console.error("Lỗi khi tìm kiếm:", error);
            alert("Đã có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại!");
        });
    }

    // Khởi tạo sự kiện khi trang tải
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("importTableBody").addEventListener("click", function (event) {
            const button = event.target.closest(".view-details");
            if (button) {
                const importId = button.getAttribute("data-id");
                const importedAt = button.getAttribute("data-imported");
                const importedBy = button.getAttribute("data-user");
                const status = button.getAttribute("data-status");
                showImportDetails(importId, importedAt, importedBy, status);
            }
        });
        document.getElementById("searchBtn").addEventListener("click", searchImports);
    });
</script>

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
            const variantImportPrice = $(this).data('import-price');
            const variantPrice = $(this).data('price');
            const variantSalePrice = $(this).data('sale-price') || ''; 
            const variantStock = $(this).data('stock');
            const variantSaleStart = $(this).data('sale-start') || ''; 
            const variantSaleEnd = $(this).data('sale-end') || '';     
            const productName = $(this).data('product-name') || 'Không xác định'; 
            const defaultStock = variantStock > 0 ? 0 : variantStock;

            console.log(variantImportPrice);

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
                       
                        <div class="right-section">
                            <div class="input-wrapper">
                                <span class="input-unit">Giá nhập</span>
                                <input type="number" name="import_prices[${variantId}]" 
                                       class="form-control import-price-input" 
                                       placeholder="Giá nhập mới" 
                                       step="0.01" 
                                       min="0" 
                                       required 
                                       value="${variantImportPrice}">
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

    $('.price-input').each(function() {
      const $container = $(this);
      const variantLabel = $container.find('.variant-label').text();
      const importPrice = parseFloat($container.find('.import-price-input').val()) || 0;
      const sellPrice = parseFloat($container.find('.price-input').val()) || 0;
      const salePrice = parseFloat($container.find('.sale-price-input').val()) || 0;
      const stock = parseFloat($container.find('.stock-input').val()) || 0;

      if (sellPrice < importPrice) {
        hasError = true;
        errorMessage += `Giá bán (${sellPrice.toLocaleString('vi-VN')} ₫) nhỏ hơn giá nhập (${importPrice.toLocaleString('vi-VN')} ₫) cho biến thể: ${variantLabel}\n`;
      }
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
        whiteSpace: 'pre-wrap' 
      });
      return false;
    }
  });
});
</script>
@endsection