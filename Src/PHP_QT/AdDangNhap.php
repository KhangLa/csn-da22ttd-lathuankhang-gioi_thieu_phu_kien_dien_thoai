<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu để lấy thông tin người dùng
    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE TenNguoiDung = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu người dùng tồn tại
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // So sánh mật khẩu nhập vào với mật khẩu trong cơ sở dữ liệu
        if ($password === $user['MatKhau']) {
            // Kiểm tra vai trò người dùng
            if ($user['VaiTro'] === 'Quản Trị Viên') {
                // Đăng nhập thành công với quyền Quản Trị Viên
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['TenNguoiDung'];
                header('Location: Admin.php'); // Chuyển hướng đến trang admin
                exit;
            } else {
                // Tài khoản không tồn tại
                $error = "Tài khoản không tồn tại!";
            }
        } else {
            // Mật khẩu sai
            $error = "Sai mật khẩu!";
        }
    } else {
        // Tài khoản người dùng không đủ quyền truy cập
        $error = "Tài khoản không đủ quyền hạn để truy cập!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản trị Quốc Bảo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Đăng nhập</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
