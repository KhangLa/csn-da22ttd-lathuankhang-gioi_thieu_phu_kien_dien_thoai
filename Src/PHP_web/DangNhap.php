<?php
session_start();
include 'db.php';  // Kết nối cơ sở dữ liệu
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin đăng nhập từ form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu kiểm tra email và mật khẩu
    $sql = "SELECT * FROM nguoidung WHERE Email = ? AND MatKhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu có kết quả, lấy thông tin người dùng
        $user = $result->fetch_assoc();
        
        // Kiểm tra nếu là tài khoản admin
        if ($user['VaiTro'] === 'Quản Trị Viên') {
            $_SESSION['error_message'] = "Bạn không có quyền truy cập!";
            // Quay lại trang đăng nhập và không lưu thông tin admin vào session
            header('Location: DangNhap.php');
            exit;
        }

        // Lưu thông tin người dùng vào session (chỉ nếu không phải admin)
        $_SESSION['user_id'] = $user['MaNguoiDung'];
        $_SESSION['user_name'] = $user['TenNguoiDung'];
        $_SESSION['user_role'] = $user['VaiTro'];

        // Nếu là người dùng bình thường, chuyển hướng về trang người dùng
        header('Location: index.php');
        exit;
    } else {
        // Nếu không có kết quả đăng nhập
        $_SESSION['error_message'] = "Email hoặc mật khẩu không đúng!";
        header('Location: DangNhap.php');  // Quay lại trang đăng nhập
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Đăng nhập</h2>
        <?php 
        // Hiển thị thông báo lỗi nếu có
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            // Sau khi hiển thị thông báo, xóa nó khỏi session để không bị lặp lại
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="DangNhap.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
        <p class="mt-3">Chưa có tài khoản? <a href="DangKi.php">Đăng ký ngay</a></p>
    </div>
</body>
<?php include 'Footer.php'; ?>
</html>
