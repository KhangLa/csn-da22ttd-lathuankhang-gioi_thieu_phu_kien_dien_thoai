<?php
session_start();
session_unset(); // Hủy tất cả các session
session_destroy(); // Hủy session

// Chuyển hướng người dùng về trang đăng nhập
header('Location: DangNhap.php');
exit;
?>