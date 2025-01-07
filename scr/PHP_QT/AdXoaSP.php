<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}

// Kiểm tra xem mã sản phẩm có được truyền vào hay không
if (isset($_GET['MaSanPham']) && !empty($_GET['MaSanPham'])) {
    $maSanPham = $_GET['MaSanPham'];

    // Thực hiện xóa sản phẩm dựa trên mã sản phẩm
    $stmt = $conn->prepare("DELETE FROM sanpham WHERE MaSanPham = ?");
    $stmt->bind_param("s", $maSanPham);

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý sản phẩm sau khi xóa thành công
        header('Location: Admin.php?status=success');
    } else {
        echo "Đã xảy ra lỗi khi xóa sản phẩm. Vui lòng thử lại.";
    }

    $stmt->close();
} else {
    echo "Mã sản phẩm không hợp lệ.";
}
?>
