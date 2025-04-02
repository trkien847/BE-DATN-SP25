document.addEventListener('DOMContentLoaded', function () {
    const notificationList = document.getElementById('notification-list');
    const notificationCount = document.getElementById('notification-count');
    const countSpan = notificationCount.querySelector('.count');

    function fetchNotifications() {
        fetch('/api/notifications', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Cập nhật số lượng thông báo
            countSpan.textContent = data.count;
            notificationCount.style.display = data.count > 0 ? 'block' : 'none';

            // Xóa nội dung cũ
            notificationList.innerHTML = '';

            if (data.count === 0) {
                notificationList.innerHTML = `
                    <div class="text-center p-3">Không có thông báo nào</div>
                `;
            } else {
                data.notifications.forEach(notification => {
                    const itemHtml = `
                        <div class="notification-item p-3 border-bottom ${notification.is_read ? 'bg-light' : ''}">
                            <h6 class="mb-1">${notification.title}</h6>
                            <p class="mb-2 fs-13">${notification.content}</p>
                            <div class="d-flex gap-2">
                                ${getActionButtons(notification)}
                            </div>
                        </div>
                    `;
                    notificationList.innerHTML += itemHtml;
                });
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
    }

    function getActionButtons(notification) {
        switch (notification.type) {
            case 'order_cancel':
                return `
                    <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                    <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                `;
            case 'refund_request':
                return `
                    <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem trực tiếp</a>
                `;
            case 'order_status_request':
                return `
                    <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                    <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                `;
            case 'return_request':
                return `
                    <form action="${notification.data.actions.accept_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <form action="${notification.data.actions.cancel_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                    <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                `;
            case 'product_pending_create':
                return `
                    <form action="${notification.data.actions.approve_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                    <a href="${notification.data.actions.view_details}" class="btn btn-sm btn-info">Xem chi tiết</a>
                `;
            case 'product_pending_update':
                return `
                    <form action="${notification.data.actions.approve_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                    <a href="${notification.data.actions.view_details}?notification_id=${notification.id}" class="btn btn-sm btn-info">Xem chi tiết</a>
                `;
            case 'import_pending':
                return `
                    <form action="${notification.data.actions.confirm_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-success">Chấp nhận</button>
                    </form>
                    <form action="${notification.data.actions.reject_request}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="notification_id" value="${notification.id}">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy yêu cầu</button>
                    </form>
                `;
            default:
                return '';
        }
    }

    // Gọi lần đầu khi tải trang
    fetchNotifications();

    // Kiểm tra mỗi 3 giây
    setInterval(fetchNotifications, 3000);
});