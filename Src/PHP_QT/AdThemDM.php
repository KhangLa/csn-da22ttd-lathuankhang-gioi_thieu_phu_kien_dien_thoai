<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhapDangNhap.php');
    exit;
}

// Xử lý khi form thêm danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $maDanhMuc = $_POST['MaDanhMuc'];
    $tenDanhMuc = $_POST['TenDanhMuc'];

    // Kiểm tra xem mã và tên danh mục có trống không
    if (empty($maDanhMuc) || empty($tenDanhMuc)) {
        echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
    } else {
        // Thêm danh mục vào cơ sở dữ liệu
        $insertQuery = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $maDanhMuc, $tenDanhMuc);

        if ($stmt->execute()) {
            echo "<script>alert('Danh mục đã được thêm thành công!');</script>";
            // Chuyển hướng về trang quản lý danh mục sau khi thêm thành công
            echo "<script>window.location.href = 'Admin.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm danh mục: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục - Quản trị Quốc Bảo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Thêm danh mục mới</h2>
        <form action="AdThemDM.php" method="POST">
            <div class="mb-3">
                <label for="MaDanhMuc" class="form-label">Mã danh mục</label>
                <input type="text" class="form-control" id="MaDanhMuc" name="MaDanhMuc" required>
            </div>
            <div class="mb-3">
                <label for="TenDanhMuc" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="TenDanhMuc" name="TenDanhMuc" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Thêm danh mục</button>
            <a href="Admin.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>
</html>
