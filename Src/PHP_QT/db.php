<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost"; // Máy chủ MySQL
$username = "root";        // Tên người dùng (mặc định trong XAMPP là 'root')
$password = "";            // Mật khẩu (mặc định là rỗng trong XAMPP)
$database = "DoAn";  // Tên cơ sở dữ liệu bạn đã tạo

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);

?>
