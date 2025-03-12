Table "orders" {
    "id" BIGINT [pk, increment, note: "ID đơn hàng"]
    "code" VARCHAR(50) [unique, not null, note: "Mã đơn hàng (duy nhất)"]
    "user_id" INT [note: "ID người dùng đặt hàng"]
    "payment_id" INT [note: "ID phương thức thanh toán"]
    "phone_number" VARCHAR(20) [note: "Số điện thoại liên lạc của người mua"]
    "email" VARCHAR(255) [note: "Email liên lạc của người mua"]
    "fullname" VARCHAR(255) [note: "Họ và tên của người nhận"]
    "address" VARCHAR(255) [note: "Địa chỉ giao hàng"]
    "total_amount" DECIMAL(12, 2) [not null, note: "Tổng tiền thanh toán cho đơn hàng"]
    "is_paid" TINYINT(1) [note: "1 nếu đã thanh toán, 0 nếu chưa thanh toán"]
    "coupon_id" VARCHAR(50) [null, note: "ID mã giảm giá"]
    "coupon_code" VARCHAR(50) [null, note: "Code mã giảm giá"]
    "coupon_description" VARCHAR(50) [null, note: "Mô tả giảm giá"]
    "coupon_discount_type" VARCHAR(50) [null, note: "Loại giảm giá"]
    "coupon_discount_value" VARCHAR(50) [null, note: "Giá trị giảm của mã giảm giá"]
    "created_at" TIMESTAMP [note: "Thời gian tạo đơn hàng"]
    "updated_at" TIMESTAMP [note: "Thời gian cập nhật đơn hàng"]
  }
  
  
  Table "order_statuses" {
    "id" INT [pk, increment, note: "ID trạng thái sản phẩm"]
    "name" VARCHAR(255) [note: "Tên trạng thái"]
    "ordinal" INT [default: 0, note: "Sắp xếp thứ tự"]
  }

  Table "order_items" {
    "id" INT [pk, increment, note: "ID chi tiết đơn hàng"]
    "order_id" BIGINT [not null, note: "ID đơn hàng liên kết"]
    "product_id" INT [note: "ID sản phẩm"]
    "product_variant_id" INT [note: "ID biến thể sản phẩm"]
    "name" VARCHAR(255) [note: "Tên sản phẩm"]
    "price" DECIMAL(11,2) [note: "Giá sản phẩm"]
    "quantity" INT [note: "Số lượng sản phẩm trong đơn hàng"]
    "name_variant" VARCHAR(255) [note: "Tên biến thể của sản phẩm"]
    "attributes_variant" JSONB [note: "Thông tin thuộc tính biến thể (dạng JSON)"]
    "price_variant" DECIMAL(11,2) [note: "Giá của biến thể sản phẩm"]
    "quantity_variant" INT [note: "Số lượng của biến thể sản phẩm"]
  }
  
  
  Table "order_order_status" {
    "order_id" INT [not null, note: "ID đơn hàng"]
    "order_status_id" INT [not null, note: "ID trạng thái đơn hàng"]
    "modified_by" INT [not null, note: "ID người xử lý đơn hàng"]
    "note" VARCHAR(255) [note: "Ghi chú của người xử lý"]
    "employee_evidence" JSONB [note: "Minh chứng của nhân viên"]
    "customer_confirmation" TINYINT(1) [note: "null nếu nhân viên mới gửi minh chứng, 1 nếu bấm xác nhận đã nhận được hàng, 0 nếu bấm xác nhận không nhận được hàng"]
    "created_at" TIMESTAMP [note: "Thời gian tạo trạng thái đơn hàng"]
    "updated_at" TIMESTAMP [note: "Thời gian cập nhật trạng thái đơn hàng"]
  }