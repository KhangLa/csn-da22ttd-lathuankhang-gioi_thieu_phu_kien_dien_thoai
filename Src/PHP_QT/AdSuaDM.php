<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}

// Kiểm tra xem có tham số MaDanhMuc trong URL không
if (isset($_GET['MaDanhMuc'])) {
    $MaDanhMuc = $_GET['MaDanhMuc'];

    // Lấy dữ liệu danh mục từ cơ sở dữ liệu
    $result = $conn->query("SELECT * FROM danhmuc WHERE MaDanhMuc = '$MaDanhMuc'");
    $row = $result->fetch_assoc();

    // Nếu không tìm thấy danh mục với MaDanhMuc này
    if (!$row) {
        echo "Danh mục không tồn tại.";
        exit;
    }

    $TenDanhMuc = $row['TenDanhMuc'];
}

// Xử lý khi người dùng gửi form sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaDanhMuc = $_POST['MaDanhMuc'];
    $TenDanhMuc = $_POST['TenDanhMuc'];

    // Cập nhật thông tin danh mục trong cơ sở dữ liệu
    $sql = "UPDATE danhmuc SET TenDanhMuc = '$TenDanhMuc' WHERE MaDanhMuc = '$MaDanhMuc'";

    if ($conn->query($sql) === TRUE) {
        echo "Danh mục đã được cập nhật thành công.";
        header("Location: Admin.php"); // Chuyển hướng về trang quản trị
        exit;
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục - Quản trị Quốc Bảo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa danh mục</h2>
        <form action="AdSuaDM.php" method="POST">
            <div class="mb-3">
                <label for="MaDanhMuc" class="form-label">Mã danh mục</label>
                <input type="text" class="form-control" id="MaDanhMuc" name="MaDanhMuc" value="<?php echo $MaDanhMuc; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="TenDanhMuc" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="TenDanhMuc" name="TenDanhMuc" value="<?php echo htmlspecialchars($TenDanhMuc); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="Admin.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
