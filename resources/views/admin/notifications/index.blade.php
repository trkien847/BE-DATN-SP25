@extends('admin.layouts.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification-table th, .notification-table td {
            vertical-align: middle;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .notification-section {
            margin-bottom: 30px;
        }
    </style>

    <div class="container mt-5">
        <h1 class="mb-4">Danh sách thông báo nhớ ngày 30/4 nhé ae</h1>

        @php
            $notificationTypes = [
                'order_cancel' => 'Yêu cầu hủy đơn hàng',
                'refund_request' => 'Yêu cầu hoàn tiền',
                'order_status_request' => 'Yêu cầu thay đổi trạng thái đơn hàng',
                'return_request' => 'Yêu cầu hoàn hàng',
                'product_pending_create' => 'Yêu cầu thêm sản phẩm mới',
                'product_pending_update' => 'Yêu cầu sửa sản phẩm',
                'import_pending' => 'Yêu cầu nhập hàng',
            ];
        @endphp

        @foreach($notificationTypes as $type => $title)
            @php
                $filteredNotifications = $notifications->where('type', $type);
            @endphp
            @if($filteredNotifications->isNotEmpty())
                <div class="notification-section">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="mb-0">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover notification-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Nội dung</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($filteredNotifications as $notification)
                                            <tr>
                                                <td>{{ $notification->title }}</td>
                                                <td>{{ $notification->content }}</td>
                                                <td class="action-buttons">
                                                    @if($notification->type === 'order_cancel')
                                                        <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'refund_request')
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem trực tiếp</a>
                                                    @elseif($notification->type === 'order_status_request')
                                                        <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'return_request')
                                                        <form action="{{ $notification->data['actions']['accept_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['cancel_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'product_pending_create')
                                                        <form action="{{ $notification->data['actions']['approve_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['reject_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'product_pending_update')
                                                        <form action="{{ $notification->data['actions']['approve_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['reject_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                        <a href="{{ $notification->data['actions']['view_details'] }}?notification_id={{ $notification->id }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                                                    @elseif($notification->type === 'import_pending')
                                                        <form action="{{ $notification->data['actions']['confirm_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                                                        </form>
                                                        <form action="{{ $notification->data['actions']['reject_request'] }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                            <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($notifications->isEmpty())
            <div class="alert alert-info text-center">
                Không có thông báo nào
            </div>
        @endif
    </div>
@endsection