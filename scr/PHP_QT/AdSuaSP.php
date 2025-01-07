<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}

// Kiểm tra xem mã sản phẩm có được gửi qua URL không
if (!isset($_GET['MaSanPham'])) {
    header('Location: Admin.php');
    exit;
}

$maSanPham = $_GET['MaSanPham'];

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM sanpham WHERE MaSanPham = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $maSanPham);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Nếu không tìm thấy sản phẩm, chuyển hướng về trang quản lý sản phẩm
if (!$product) {
    header('Location: Admin.php');
    exit;
}

// Lấy danh sách danh mục từ bảng danhmuc
$categories = $conn->query("SELECT * FROM danhmuc");

// Xử lý khi người dùng gửi form sửa thông tin sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tenSanPham = $_POST['TenSanPham'];
    $gia = $_POST['Gia'];
    $thuongHieu = $_POST['ThuongHieu'];
    $soLuongTonKho = $_POST['SoLuongTonKho'];
    $moTa = $_POST['MoTa'];
    $hinhAnh = $_POST['HinhAnh'];
    $maDanhMuc = $_POST['MaDanhMuc'];

    // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
    $updateQuery = "UPDATE sanpham SET TenSanPham = ?, Gia = ?, ThuongHieu = ?, SoLuongTonKho = ?, MoTa = ?, HinhAnh = ?, MaDanhMuc = ? WHERE MaSanPham = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ssssssss', $tenSanPham, $gia, $thuongHieu, $soLuongTonKho, $moTa, $hinhAnh, $maDanhMuc, $maSanPham);
    
    if ($updateStmt->execute()) {
        // Nếu cập nhật thành công, chuyển hướng về trang quản lý sản phẩm
        header('Location: Admin.php');
        exit;
    } else {
        // Thông báo lỗi nếu không cập nhật thành công
        echo "Lỗi cập nhật sản phẩm: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm - Quản trị Quốc Bảo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa sản phẩm</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="TenSanPham" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="TenSanPham" name="TenSanPham" value="<?php echo htmlspecialchars($product['TenSanPham']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="Gia" class="form-label">Giá</label>
                <input type="text" class="form-control" id="Gia" name="Gia" value="<?php echo htmlspecialchars($product['Gia']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ThuongHieu" class="form-label">Thương hiệu</label>
                <input type="text" class="form-control" id="ThuongHieu" name="ThuongHieu" value="<?php echo htmlspecialchars($product['ThuongHieu']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="SoLuongTonKho" class="form-label">Số lượng tồn kho</label>
                <input type="number" class="form-control" id="SoLuongTonKho" name="SoLuongTonKho" value="<?php echo htmlspecialchars($product['SoLuongTonKho']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="MoTa" class="form-label">Mô tả</label>
                <textarea class="form-control" id="MoTa" name="MoTa" rows="3" required><?php echo htmlspecialchars($product['MoTa']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="HinhAnh" class="form-label">Hình ảnh</label>
                <input type="text" class="form-control" id="HinhAnh" name="HinhAnh" value="<?php echo htmlspecialchars($product['HinhAnh']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="MaDanhMuc" class="form-label">Danh mục</label>
                <select class="form-control" id="MaDanhMuc" name="MaDanhMuc" required>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['MaDanhMuc']; ?>" <?php echo $category['MaDanhMuc'] == $product['MaDanhMuc'] ? 'selected' : ''; ?>>
                            <?php echo $category['TenDanhMuc']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        </form>
    </div>
</body>
</html>
