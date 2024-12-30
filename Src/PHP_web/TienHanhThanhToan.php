<?php
session_start();
include 'db.php';
include 'header.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: DangNhap.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Kiểm tra giỏ hàng có sản phẩm không
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Tạo mã đơn hàng mới
    $sql = "SELECT MaDonHang FROM DonHang ORDER BY MaDonHang DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $lastOrder = $result->fetch_assoc();
        $lastOrderId = (int)substr($lastOrder['MaDonHang'], 2);
        $newOrderId = 'DH' . str_pad($lastOrderId + 1, 2, '0', STR_PAD_LEFT);
    } else {
        $newOrderId = 'DH01';
    }

    // Tính tổng giá trị đơn hàng
    $orderTotal = 0;
    foreach ($_SESSION['cart'] as $productId => $item) {
        $priceQuery = "SELECT Gia FROM SanPham WHERE MaSanPham = '$productId'";
        $priceResult = $conn->query($priceQuery);
        $priceRow = $priceResult->fetch_assoc();
        $orderTotal += $priceRow['Gia'] * $item['quantity'];
    }

    // Thêm đơn hàng vào bảng DonHang
    $insertOrder = "INSERT INTO DonHang (MaDonHang, MaNguoiDung, NgayDatHang, TongGia, TrangThai) 
                    VALUES ('$newOrderId', '$userId', NOW(), '$orderTotal', 'Đang Xử Lý')";
    if ($conn->query($insertOrder) === TRUE) {
        // Thêm chi tiết đơn hàng vào bảng ChiTietDonHang
        foreach ($_SESSION['cart'] as $productId => $item) {
            $priceQuery = "SELECT Gia FROM SanPham WHERE MaSanPham = '$productId'";
            $priceResult = $conn->query($priceQuery);
            $priceRow = $priceResult->fetch_assoc();
            $price = $priceRow['Gia'];

            $insertDetail = "INSERT INTO ChiTietDonHang (MaChiTietDonHang, MaDonHang, MaSanPham, SoLuong, DonGia) 
                             VALUES ('$newOrderId-$productId', '$newOrderId', '$productId', '{$item['quantity']}', '$price')";
            $conn->query($insertDetail);
        }

        unset($_SESSION['cart']); // Xóa giỏ hàng sau khi thanh toán

        echo "<p class='text-center text-success'>Đơn hàng của bạn đã được xác nhận! Chúng tôi sẽ gửi hàng đến bạn trong thời gian sớm nhất.</p>";
        echo "<p class='text-center'><a href='LichSuDonhang.php' class='btn btn-secondary'>Xem lịch sử đơn hàng</a></p>";
    } else {
        echo "<p class='text-center text-danger'>Lỗi khi thêm đơn hàng: " . $conn->error . "</p>";
    }
} else {
    echo "<p class='text-center text-danger'>Giỏ hàng của bạn trống.</p>";
}

include 'footer.php';
?>