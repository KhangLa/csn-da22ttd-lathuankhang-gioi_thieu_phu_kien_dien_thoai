<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Quốc Bảo - Phụ kiện điện thoại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 200px;
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <img src="../images/logo.png" alt="Logo" class="logo">
            <p>Trang quản trị</p>
        </div>
        <a href="#dashboard">Thống kê</a>
        <a href="#products">Quản lý sản phẩm</a>
        <a href="#categories">Quản lý danh mục</a>
        <a href="#orders">Quản lý đơn hàng</a>
        <a href="#users">Quản lý người dùng</a>
        <a href="AdDangXuat.php">Đăng xuất</a>
    </div>

    <div class="content">
        <!-- Thống kê -->
        <section id="dashboard" class="mb-5">
            <h2>Tổng quan</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Tổng số sản phẩm</div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM sanpham");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </h5>
                            <p class="card-text">Tổng số sản phẩm hiện có trong cửa hàng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Tổng số đơn hàng</div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM donhang");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </h5>
                            <p class="card-text">Tổng số đơn hàng đã được xử lý.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-header">Số lượng người dùng</div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM nguoidung");
                                echo $result->fetch_assoc()['total'];
                                ?>
                            </h5>
                            <p class="card-text">Số lượng người dùng đăng ký trên hệ thống.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quản lý sản phẩm -->
        <section id="products" class="mb-5">
            <h2>Quản lý sản phẩm</h2>
            <a href="AdThemSP.php" class="btn btn-success mb-3">Thêm sản phẩm mới</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Thương hiệu</th>
                        <th>Số lượng tồn kho</th>
                        <th>Mô tả</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM sanpham");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['MaSanPham']}</td>
                                <td>{$row['TenSanPham']}</td>
                                <td>" . number_format($row['Gia'], 0, ',', '.') . " VND</td>
                                <td>{$row['ThuongHieu']}</td>
                                <td>{$row['SoLuongTonKho']}</td>
                                <td>" . nl2br(htmlspecialchars($row['MoTa'])) . "</td>
                                <td><img src='{$row['HinhAnh']}' alt='Hình sản phẩm'></td>
                                <td>
                                    <a href='AdXoaSP.php?MaSanPham={$row['MaSanPham']}' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này không?\");'>Xóa</a>
                                    <a href='AdSuaSP.php?MaSanPham={$row['MaSanPham']}' class='btn btn-warning'>Sửa</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Quản lý danh mục -->
        <section id="categories" class="mb-5">
            <h2>Quản lý danh mục</h2>
            <a href="AdThemDM.php" class="btn btn-success mb-3">Thêm danh mục mới</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã danh mục</th>
                        <th>Tên danh mục</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM danhmuc");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['MaDanhMuc']}</td>
                                <td>{$row['TenDanhMuc']}</td>
                                <td>
                                    <a href='AdXoaDM.php?MaDanhMuc={$row['MaDanhMuc']}' class='btn btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa danh mục này không?\");'>Xóa</a>
                                    <a href='AdSuaDM.php?MaDanhMuc={$row['MaDanhMuc']}' class='btn btn-warning'>Sửa</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Quản lý đơn hàng -->
        <section id="orders" class="mb-5">
            <h2>Quản lý đơn hàng</h2>
            <a href="AdQuanLyDH.php" class="btn btn-info mb-3">Cập nhật trạng thái đơn hàng</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM donhang");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['MaDonHang']}</td>
                                <td>{$row['MaNguoiDung']}</td>
                                <td>{$row['NgayDatHang']}</td>
                                <td>{$row['TrangThai']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- Quản lý người dùng -->
        <section id="users" class="mb-5">
            <h2>Quản lý người dùng</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã người dùng</th>
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Mật khẩu</th>
                        <th>Quyền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM nguoidung");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['MaNguoiDung']}</td>
                                <td>{$row['TenNguoiDung']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['MatKhau']}</td>
                                <td>{$row['VaiTro']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
