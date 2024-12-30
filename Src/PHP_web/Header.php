<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quốc Bảo - Phụ kiện điện thoại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="../images/logo.png" alt="Logo">
            </div>
            <nav class="d-flex align-items-center">
                <a href="index.php" class="text-white mx-3">Trang Chủ</a>
                <a href="index.php#products" class="text-white mx-3">Sản Phẩm</a>
                <a href="GioHang.php" class="text-white mx-3">Giỏ Hàng</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Đưa "Đơn Hàng" lên trước tên người dùng -->
                    <a href="LichSuDonhang.php" class="text-white mx-3">Đơn Hàng</a>

                    <!-- Liên kết Đăng xuất -->
                    <a href="DangXuat.php" class="text-white mx-3">Đăng xuất</a>

                    <!-- Thêm khung cho tên người dùng sau chữ Đăng xuất -->
                    <div class="user-info-box">
                        <span class="navbar-text text-white">
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    </div>
                <?php else: ?>
                    <!-- Hiển thị nút "Tham gia" nếu chưa đăng nhập -->
                    <a href="DangNhap.php" class="text-white mx-3">Tham Gia</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Nội dung trang web tiếp theo -->
</body>
</html>
