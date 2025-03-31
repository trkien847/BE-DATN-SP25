@if(auth()->check() && auth()->user()->role_id == 3)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationList = document.getElementById('notification-list');
        const notificationCount = document.getElementById('notification-count');
        const countSpan = notificationCount.querySelector('.count');
        let lastChecked = new Date();

        function fetchNotifications() {
            fetch("{{ route('notifications.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ last_checked: lastChecked.toISOString() })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.notifications) {
                    countSpan.textContent = data.notifications.length;
                    notificationCount.style.display = data.notifications.length > 0 ? 'block' : 'none';
                    notificationList.innerHTML = '';

                    data.notifications.forEach(notification => {
                        const item = document.createElement('a');
                        item.href = 'javascript:void(0);';
                        item.className = 'dropdown-item py-3 border-bottom text-wrap';
                        item.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="${notification.avatar}"
                                        class="img-fluid me-2 avatar-sm rounded-circle"/>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0">
                                        <span class="fw-medium">${notification.user_name}</span>
                                        đang yêu cầu nhập hàng
                                        <small class="text-muted d-block">${notification.created_at}</small>
                                    </p>
                                </div>
                            </div>
                            <form action="{{ url('products/import/confirm') }}/${notification.import_id}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success mt-2">Xác nhận</button>
                                </form>
                                <form action="{{ url('products/import/reject') }}/${notification.import_id}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger mt-2">Không xác nhận</button>
                                </form>
                        `;
                        notificationList.appendChild(item);
                    });
                }

                if (data.imports && data.imports.length > 0) {
                    data.imports.forEach(importItem => {
                        Swal.fire({
                            icon: 'info',
                            title: 'Thông báo',
                            html: `${importItem.message} (Ngày nhập: ${importItem.imported_at} bởi ${importItem.imported_by})<br>
                                <form action="{{ url('products/import/confirm') }}/${importItem.import_id}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success mt-2">Xác nhận</button>
                                </form>
                                <form action="{{ url('products/import/reject') }}/${importItem.import_id}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger mt-2">Không xác nhận</button>
                                </form>`,
                            showConfirmButton: false,
                        });
                    });
                    lastChecked = new Date(); 
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            })
            .finally(() => {
                setTimeout(fetchNotifications, 3000); 
            });
        }

        fetchNotifications();
    });
    </script>
@endif 