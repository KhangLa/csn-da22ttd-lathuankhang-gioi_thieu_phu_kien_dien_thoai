<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Adlogin.php');
    exit;
}

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSanPham = $_POST['MaSanPham'];  // Lấy Mã Sản Phẩm từ form
    $tenSanPham = $_POST['TenSanPham'];
    $gia = $_POST['Gia'];
    $thuongHieu = $_POST['ThuongHieu'];
    $soLuongTonKho = $_POST['SoLuongTonKho'];
    $moTa = $_POST['MoTa'];
    $maDanhMuc = $_POST['MaDanhMuc'];

    // Xử lý upload hình ảnh
    $hinhAnh = $_FILES['HinhAnh'];
    $hinhAnhPath = "../images/SanPham/" . basename($hinhAnh['name']);
    
    // Kiểm tra xem thư mục images có tồn tại không
    if (!is_dir('../images/SanPham')) {
        mkdir('../images/SanPham', 0777, true);
    }

    // Di chuyển ảnh vào thư mục images
    if (move_uploaded_file($hinhAnh['tmp_name'], $hinhAnhPath)) {
        // Hình ảnh đã được tải lên thành công
    } else {
        echo "Lỗi khi tải lên hình ảnh.";
    }

    // Thực hiện thêm sản phẩm vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO sanpham (MaSanPham, TenSanPham, Gia, ThuongHieu, SoLuongTonKho, MoTa, HinhAnh, MaDanhMuc) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $maSanPham, $tenSanPham, $gia, $thuongHieu, $soLuongTonKho, $moTa, $hinhAnhPath, $maDanhMuc);
    
    if ($stmt->execute()) {
        // Thêm thành công, chuyển hướng về trang quản trị
        header('Location: Admin.php');
        exit;
    } else {
        echo "Lỗi khi thêm sản phẩm: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Thêm Sản Phẩm Mới</h2>
        <form action="AdThemSP.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="MaSanPham" class="form-label">Mã Sản Phẩm</label>
                <input type="text" class="form-control" id="MaSanPham" name="MaSanPham" required>
            </div>
            <div class="mb-3">
                <label for="TenSanPham" class="form-label">Tên Sản Phẩm</label>
                <input type="text" class="form-control" id="TenSanPham" name="TenSanPham" required>
            </div>
            <div class="mb-3">
                <label for="Gia" class="form-label">Giá</label>
                <input type="number" class="form-control" id="Gia" name="Gia" required>
            </div>
            <div class="mb-3">
                <label for="ThuongHieu" class="form-label">Thương Hiệu</label>
                <input type="text" class="form-control" id="ThuongHieu" name="ThuongHieu" required>
            </div>
            <div class="mb-3">
                <label for="SoLuongTonKho" class="form-label">Số Lượng Tồn Kho</label>
                <input type="number" class="form-control" id="SoLuongTonKho" name="SoLuongTonKho" required>
            </div>
            <div class="mb-3">
                <label for="MoTa" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="MoTa" name="MoTa" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="MaDanhMuc" class="form-label">Danh Mục</label>
                <select class="form-control" id="MaDanhMuc" name="MaDanhMuc" required>
                    <?php
                    // Lấy danh sách danh mục từ cơ sở dữ liệu
                    $result = $conn->query("SELECT MaDanhMuc, TenDanhMuc FROM danhmuc");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['MaDanhMuc'] . "'>" . $row['TenDanhMuc'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="HinhAnh" class="form-label">Hình Ảnh</label>
                <input type="file" class="form-control" id="HinhAnh" name="HinhAnh" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
