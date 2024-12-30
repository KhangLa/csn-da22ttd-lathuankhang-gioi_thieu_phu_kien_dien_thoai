<?php
session_start(); // Bắt đầu phiên làm việc

// Hủy tất cả dữ liệu phiên
session_unset();

// Hủy phiên làm việc
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập
header('Location: AdDangNhap.php');
exit;
?>