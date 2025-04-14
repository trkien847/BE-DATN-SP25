@extends('admin.layouts.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
        <!-- Header Information -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Chi tiết lô hàng #{{ $import->import_code }}
                </h1>
                <div class="flex gap-3">
                    @if(!$import->is_confirmed)
                        <button onclick="confirmImport({{ $import->id }})" 
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                            Xác nhận
                        </button>
                        <button onclick="cancelImport({{ $import->id }})" 
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                            Hủy
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Ngày nhập:</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($import->import_date)->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Người nhập:</p>
                    <p class="font-semibold text-gray-800">{{ $import->user->fullname }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Nhà cung cấp:</p>
                    <p class="font-semibold text-gray-800">{{ $import->supplier->name }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Tổng số lượng:</p>
                    <p class="font-semibold text-gray-800">{{ number_format($import->total_quantity) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Tổng giá trị:</p>
                    <p class="font-semibold text-gray-800">{{ number_format($import->total_price, 0, ',', '.') }} VNĐ</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">Trạng thái:</p>
                    <p class="font-semibold">
                        @if($import->is_confirmed)
                            <span class="text-green-600">Đã xác nhận</span>
                        @else
                            <span class="text-yellow-600">Chờ xác nhận</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Danh sách sản phẩm</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Biến thể
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Số lượng
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Đơn giá
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Thành tiền
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($import->importProducts as $importProduct)
                            @foreach($importProduct->variants as $variant)
                                <tr class="hover:bg-gray-50 transition-all duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <img class="h-12 w-12 rounded-lg object-cover" 
                                                     src="{{ asset('upload/' . $importProduct->product->thumbnail) }}" 
                                                     alt="{{ $importProduct->product_name }}"
                                                     loading="lazy">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $importProduct->product_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $variant->variant_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ number_format($variant->quantity) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ number_format($variant->import_price, 0, ',', '.') }} VNĐ
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ number_format($variant->total_price, 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Proof Images -->
        @if($import->proof_image)
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Hình ảnh chứng từ</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach(json_decode($import->proof_image) as $image)
                        <div class="relative group overflow-hidden rounded-xl shadow-md">
                            <img src="{{ asset('upload/imports/' . $image) }}" 
                                 alt="Proof image" 
                                 class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-110"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="{{ asset('upload/imports/' . $image) }}" 
                                   target="_blank" 
                                   class="text-white hover:text-gray-200 transform transition-transform duration-200 hover:scale-125">
                                    <i class="fas fa-expand-arrows-alt text-3xl"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
async function confirmImport(importId) {
    try {
        const result = await Swal.fire({
            title: 'Xác nhận?',
            text: "Bạn có chắc chắn muốn xác nhận lô hàng này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/admin/imports/confirm/${importId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                await Swal.fire(
                    'Thành công!',
                    data.message,
                    'success'
                );
                window.location.reload();
            } else {
                throw new Error(data.message);
            }
        }
    } catch (error) {
        Swal.fire(
            'Lỗi!',
            error.message,
            'error'
        );
    }
}

async function cancelImport(importId) {
    try {
        const result = await Swal.fire({
            title: 'Hủy lô hàng?',
            text: "Bạn có chắc chắn muốn hủy lô hàng này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hủy lô hàng',
            cancelButtonText: 'Không'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/admin/imports/cancel/${importId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                await Swal.fire(
                    'Thành công!',
                    data.message,
                    'success'
                );
                window.location.reload();
            } else {
                throw new Error(data.message);
            }
        }
    } catch (error) {
        Swal.fire(
            'Lỗi!',
            error.message,
            'error'
        );
    }
}
</script>
@endpush
@endsection