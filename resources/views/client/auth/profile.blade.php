@extends('client.layouts.layout')
@section('content')
    <!-- Utilize Cart Menu Start -->
    @include('client.components.CartMenuStart')
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    @include('client.components.MobileMenuStart')
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image " data-bs-bg="img/bg/14.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Tài khoản</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i
                                                class="fas fa-home"></i></span> Trang chủ</a></li>
                                <li>Tài khoản</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- WISHLIST AREA START -->
    <div class="liton__wishlist-area pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- PRODUCT TAB AREA START -->
                    <div class="ltn__product-tab-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="ltn__tab-menu-list mb-50">
                                        <div class="nav">
                                            <a class="active show" data-bs-toggle="tab" href="#liton_tab_1_1">Tài khoản <i
                                                    class="fas fa-home"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_2">Đơn hàng <i
                                                    class="fas fa-file-alt"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_4">Địa chỉ <i
                                                    class="fas fa-map-marker-alt"></i></a>
                                            <a data-bs-toggle="tab" href="#liton_tab_1_5">Thông tin tài khoản <i
                                                    class="fas fa-user"></i></a>
                                            <a href="{{ route('logout') }}">Đăng xuất <i
                                                    class="fas fa-sign-out-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="liton_tab_1_1">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>Xin chào <strong>{{ Auth::user()->fullname }}</strong> </p>
                                                <p>Từ bảng điều khiển tài khoản của bạn, bạn có thể xem<span>lịch sử mua
                                                        hàng</span>
                                                    của bạn <span>, địa chỉ vận chuyển và thanh toán</span>, và <span>chỉnh
                                                        sửa mật khẩu và thông tin tài khoản</span>.</p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_2">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Đơn hàng</th>
                                                                <th>Ngày mua</th>
                                                                <th>Trạng thái</th>
                                                                <th>Tông tiền</th>
                                                                <th>Hành động</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Jun 22, 2019</td>
                                                                <td>Pending</td>
                                                                <td>$3000</td>
                                                                <td><a href="cart.html">Xem</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Nov 22, 2019</td>
                                                                <td>Approved</td>
                                                                <td>$200</td>
                                                                <td><a href="cart.html">Xem</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Jan 12, 2020</td>
                                                                <td>On Hold</td>
                                                                <td>$990</td>
                                                                <td><a href="cart.html">Xem</a></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_4">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>Các địa chỉ sau sẽ được sử dụng trên trang thanh toán theo mặc định.</p>
                                                <div class="row" id="addressList">
                                                    @php
                                                        $address = auth()->user()->address;
                                                    @endphp

                                                    @if ($address)
                                                        <div class="col-md-6 col-12 learts-mb-30"
                                                            id="address-{{ $address->id }}">
                                                            <h4> Địa chỉ
                                                                <small><a href="#" class="edit-address"
                                                                        data-id="{{ $address->id }}"
                                                                        data-address="{{ $address->address }}">edit</a></small>
                                                                <small><a href="#" class="delete-address text-danger"
                                                                        data-id="{{ $address->id }}">delete</a></small>
                                                            </h4>
                                                            <address>
                                                                <p><strong>{{ auth()->user()->fullname }}</strong></p>
                                                                <p class="address-text">{{ $address->address }}</p>
                                                                <p>Mobile: {{ auth()->user()->phone_number ?? 'Chưa có' }}
                                                                </p>
                                                            </address>
                                                        </div>
                                                    @endif
                                                </div>
                                                <!-- Nút Thêm Địa Chỉ -->
                                                <button class="btn btn-primary" id="addAddressBtn">Thêm địa chỉ</button>

                                                <!-- Form thêm địa chỉ (Ẩn mặc định) -->
                                                <div id="addAddressForm" style="display: none; margin-top: 10px;">
                                                    <h4>Thêm địa chỉ mới</h4>
                                                    <form id="newAddressForm" method="POST"
                                                        action="{{ route('profile.address.store') }}">
                                                        @csrf
                                                        <input type="hidden" id="address_id" value="">
                                                        <div class="form-group">
                                                            <label for="address">Địa chỉ:</label>
                                                            <input type="text" class="form-control" id="address">
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Lưu</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            id="cancelAddAddress">Hủy</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_5">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="ltn__form-box">
                                                    <form id="profileForm" action="{{ route('profile.update') }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Avatar -->
                                                        <div class="avatar-upload-container">
                                                            <label for="avatar">Ảnh đại diện</label>
                                                            <div class="avatar-wrapper">
                                                                <img id="avatarPreview"
                                                                    src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('admin/images/users/dummy-avatar.png') }}"
                                                                    alt="Avatar" class="avatar-img">
                                                                <input type="file" id="avatar" name="avatar"
                                                                    accept="image/*" class="file-input">
                                                                <button type="button" class="change-avatar-btn"
                                                                    onclick="document.getElementById('avatar').click()">Đổi</button>
                                                            </div>
                                                        </div>
                                                        <!-- Thông tin cá nhân -->
                                                        <div class="row mb-50 profile-info">
                                                            <div class="form-group col-md-6">
                                                                <label for="fullname">Tên hiển thị:</label>
                                                                <input type="text" name="fullname" id="fullname"
                                                                    class="form-control"
                                                                    value="{{ old('display_name', auth()->user()->fullname) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="email">Email:</label>
                                                                <input type="email" name="email" id="email"
                                                                    class="form-control"
                                                                    value="{{ old('email', auth()->user()->email) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="phone_number">Số điện thoại:</label>
                                                                <input type="text" name="phone_number"
                                                                    id="phone_number" class="form-control"
                                                                    value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="birthday">Ngày sinh:</label>
                                                                <input type="date" name="birthday" id="birthday"
                                                                    class="form-control"
                                                                    value="{{ old('birthday', auth()->user()->birthday) }}">
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label for="gender">Giới tính:</label>
                                                                <select name="gender" id="gender"
                                                                    class="form-control">
                                                                    <option value="">-- Chọn giới tính --</option>
                                                                    <option value="Nam"
                                                                        {{ auth()->user()->gender == 'Nam' ? 'selected' : '' }}>
                                                                        Nam</option>
                                                                    <option value="Nữ"
                                                                        {{ auth()->user()->gender == 'Nữ' ? 'selected' : '' }}>
                                                                        Nữ</option>
                                                                    <option value="Khác"
                                                                        {{ auth()->user()->gender == 'Khác' ? 'selected' : '' }}>
                                                                        Khác</option>
                                                                </select>
                                                            </div>
                                                        </div>



                                                        <!-- Mật khẩu -->
                                                        <fieldset
                                                            style="border: 1px solid #ddd; padding: 20px; border-radius: 10px; margin-top: 30px;">
                                                            <legend style="padding: 0 10px; font-weight: bold;margin-bottom: 10px;">Thay đổi
                                                                mật khẩu</legend>

                                                            <div class="password-group">
                                                                <div class="form-row">
                                                                    <label for="current_password">Mật khẩu hiện
                                                                        tại:</label>
                                                                    <input type="password" name="current_password"
                                                                        id="current_password">
                                                                </div>
                                                                <div class="form-row">
                                                                    <label for="new_password">Mật khẩu mới:</label>
                                                                    <input type="password" name="new_password"
                                                                        id="new_password">
                                                                </div>
                                                                <div class="form-row">
                                                                    <label for="new_password_confirmation">Nhập lại mật
                                                                        khẩu:</label>
                                                                    <input type="password"
                                                                        name="new_password_confirmation"
                                                                        id="new_password_confirmation">
                                                                </div>
                                                                <span id="password_error" class="error-text">Mật khẩu xác
                                                                    nhận không khớp.</span>
                                                            </div>
                                                        </fieldset>


                                                        <!-- Nút lưu -->
                                                        <div class="btn-wrapper">
                                                            <button type="submit"
                                                                class="btn theme-btn-1 btn-effect-1 text-uppercase">Lưu
                                                                thay đổi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PRODUCT TAB AREA END -->
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->

    <!-- CALL TO ACTION START (call-to-action-6) -->
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom" data-bs-bg="img/1.jpg--">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div
                        class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Buy medical disposable face mask <br> to protect your loved ones</h1>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="shop.html">Explore Products <i
                                    class="icon-next"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->
@endsection
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const password = document.getElementById("new_password");
            const confirmPassword = document.getElementById("new_password_confirmation");
            const errorText = document.getElementById("password_error");
            const form = document.querySelector("form"); // Lấy form

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    errorText.style.display = "block"; // Hiển thị lỗi
                    return false;
                } else {
                    errorText.style.display = "none"; // Ẩn lỗi nếu khớp
                    return true;
                }
            }

            // Kiểm tra khi người dùng nhập
            password.addEventListener("input", validatePassword);
            confirmPassword.addEventListener("input", validatePassword);

            // Kiểm tra trước khi gửi form
            form.addEventListener("submit", function(event) {
                if (!validatePassword()) {
                    event.preventDefault(); // Ngăn không cho submit nếu có lỗi
                }
            });
        });
        $(document).ready(function() {
            $("#profileForm").on("submit", function(event) {
                event.preventDefault(); // Ngăn form gửi truyền thống

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Toastify({
                            text: "Cập nhật thành công!",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "green",
                        }).showToast();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Có lỗi xảy ra!";

                        if (errors) {
                            errorMessage = Object.values(errors).map(err => err[0]).join("\n");
                        }

                        Toastify({
                            text: errorMessage,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "red",
                        }).showToast();
                    }
                });
            });

            // Kiểm tra mật khẩu xác nhận
            $("#new_password, #new_password_confirmation").on("input", function() {
                let password = $("#new_password").val();
                let confirmPassword = $("#new_password_confirmation").val();
                let errorText = $("#password_error");

                if (password !== confirmPassword) {
                    errorText.show();
                } else {
                    errorText.hide();
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const addBtn = document.getElementById("addAddressBtn");
            const cancelBtn = document.getElementById("cancelAddAddress");

            if (addBtn) {
                addBtn.addEventListener("click", function() {
                    document.getElementById("addAddressForm").style.display = "block";
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener("click", function() {
                    document.getElementById("addAddressForm").style.display = "none";
                    document.getElementById("newAddressForm").reset();
                    document.getElementById("address_id").value = "";
                    document.querySelector("#addAddressForm h4").innerText = "Thêm địa chỉ mới";
                });
            }

            // 🟢 Thêm địa chỉ mới
            document.getElementById("newAddressForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let address = document.getElementById("address").value;
                let addressId = document.getElementById("address_id").value;
                let url = addressId ? `/profile/address/${addressId}` : "/profile/address";
                let method = addressId ? "PUT" : "POST";

                fetch(url, {
                        method: method,
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({
                            address: address
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (addressId) {
                                // 🟡 Cập nhật địa chỉ
                                document.querySelector(`#address-${addressId} .address-text`)
                                    .innerText = address;
                            } else {
                                // 🟢 Thêm địa chỉ mới
                                let newAddressHTML = `
                        <div class="col-md-6 col-12 learts-mb-30" id="address-${data.id}">
                            <h4> Address 
                                <small><a href="#" class="edit-address" data-id="${data.id}" data-address="${data.address}">edit</a></small>
                                <small><a href="#" class="delete-address text-danger" data-id="${data.id}">delete</a></small>
                            </h4>
                            <address>
                                <p><strong>${data.fullname}</strong></p>
                                <p class="address-text">${data.address}</p>
                                <p>Mobile: ${data.phone_number ?? 'Chưa có'}</p>
                            </address>
                        </div>
                    `;
                                document.getElementById("addressList").insertAdjacentHTML("beforeend",
                                    newAddressHTML);
                                attachEditEvent(); // Cập nhật sự kiện edit
                                attachDeleteEvent(); // Cập nhật sự kiện delete
                            }
                            document.getElementById("addAddressForm").style.display = "none";
                            document.getElementById("newAddressForm").reset();
                            document.getElementById("address_id").value = "";
                        }
                    })
                    .catch(error => console.error("Lỗi Fetch:", error));
            });

            // 🟡 Sự kiện chỉnh sửa địa chỉ
            function attachEditEvent() {
                document.querySelectorAll(".edit-address").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        let id = this.getAttribute("data-id");
                        let address = this.getAttribute("data-address");

                        document.getElementById("address_id").value = id;
                        document.getElementById("address").value = address;
                        document.getElementById("addAddressForm").style.display = "block";
                    });
                });
            }

            // 🔴 Sự kiện xóa địa chỉ
            function attachDeleteEvent() {
                document.querySelectorAll(".delete-address").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        let id = this.getAttribute("data-id");

                        if (confirm("Bạn có chắc chắn muốn xóa địa chỉ này không?")) {
                            fetch(`/profile/address/${id}`, {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": csrfToken,
                                        "X-Requested-With": "XMLHttpRequest"
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        document.getElementById(`address-${id}`).remove();
                                    }
                                })
                                .catch(error => console.error("Lỗi Fetch:", error));
                        }
                    });
                });
            }

            attachEditEvent();
            attachDeleteEvent();
        });

        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
@push('css')
    <style>
        .avatar-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .avatar-upload-container label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.02em;
        }

        .avatar-wrapper {
            position: relative;
            width: 128px;
            height: 128px;
            border-radius: 50%;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .avatar-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 3px solid #ffffff;
            border-radius: 50%;
        }

        .file-input {
            display: none;
        }

        .change-avatar-btn {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .change-avatar-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .change-avatar-btn:active {
            transform: translateY(0);
        }

        .profile-info .form-group {
            margin-bottom: 20px;
        }

        .profile-info label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        .profile-info .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
            height: 45px;
            /* 👈 Thêm để các ô bằng chiều cao */
        }

        .profile-info .form-control:focus {
            border-color: #007bff;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .form-control {
            height: 45px;
            /* Đảm bảo chiều cao bằng input */
            padding: 10px 14px;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        select.form-control {
            appearance: none;
            /* Xóa kiểu native của trình duyệt */
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
        }

        .password-group .form-row {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .password-group label {
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .password-group input {
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fdfdfd;
        }

        .password-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.15);
        }

        .error-text {
            color: red;
            font-size: 13px;
            display: none;
        }
    </style>
@endpush
