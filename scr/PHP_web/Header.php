<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quốc Bảo - Phụ kiện điện thoại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS trực tiếp để xóa gạch chân liên kết */
        header nav a {
            text-decoration: none;
            color: inherit;
        }

        header nav a:hover {
            text-decoration: none;
        }
    </style>
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
                    <a href="LichSuDonhang.php" class="text-white mx-3">Đơn Hàng</a>
                    <a href="DangXuat.php" class="text-white mx-3">Đăng xuất</a>
                    <div class="user-info-box">
                        <span class="navbar-text text-white">
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    </div>
                <?php else: ?>
                    <a href="DangNhap.php" class="text-white mx-3">Tham Gia</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
</body>
</html>
