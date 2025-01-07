<?php
include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $maNguoiDung = $_POST['maNguoiDung']; // Lấy mã người dùng
    $tenNguoiDung = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Mật khẩu sẽ được lưu dưới dạng văn bản thô
    $vaiTro = 'Khách Hàng'; // Giá trị mặc định cho vai trò là 'KhachHang'. Có thể thay đổi sau

    // Kiểm tra xem mã người dùng có được nhập không
    if (empty($maNguoiDung)) {
        $error = "Mã người dùng không thể để trống.";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $sql = "SELECT * FROM nguoidung WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email đã tồn tại.";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu mà không mã hóa mật khẩu
            $sql = "INSERT INTO nguoidung (MaNguoiDung, TenNguoiDung, Email, MatKhau, VaiTro) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $maNguoiDung, $tenNguoiDung, $email, $password, $vaiTro);
            if ($stmt->execute()) {
                header('Location: DangNhap.php');
                exit;
            } else {
                $error = "Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Đăng ký</h2>
        <?php if (isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
        <form method="POST" action="DangKi.php">
            <div class="mb-3">
                <label for="maNguoiDung" class="form-label">Mã người dùng</label>
                <input type="text" class="form-control" id="maNguoiDung" name="maNguoiDung" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên người dùng</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </form>
        <p class="mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</body>
<?php include 'Footer.php'; ?>
</html>
