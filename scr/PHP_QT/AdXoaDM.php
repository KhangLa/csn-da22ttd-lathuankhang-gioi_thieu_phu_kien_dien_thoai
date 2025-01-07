<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}

// Kiểm tra xem mã danh mục có được truyền vào hay không
if (isset($_GET['MaDanhMuc']) && !empty($_GET['MaDanhMuc'])) {
    $maDanhMuc = $_GET['MaDanhMuc'];

    // Kiểm tra xem danh mục có chứa sản phẩm hay không
    $stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM sanpham WHERE MaDanhMuc = ?");
    $stmtCheck->bind_param("s", $maDanhMuc);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $rowCheck = $resultCheck->fetch_assoc();

    if ($rowCheck['total'] > 0) {
        // Nếu danh mục chứa sản phẩm, không cho phép xóa
        echo "<script>
                alert('Không thể xóa danh mục này vì nó chứa sản phẩm.');
                window.location.href = 'index.php#categories';
              </script>";
    } else {
        // Xóa danh mục nếu không chứa sản phẩm
        $stmtDelete = $conn->prepare("DELETE FROM danhmuc WHERE MaDanhMuc = ?");
        $stmtDelete->bind_param("s", $maDanhMuc);

        if ($stmtDelete->execute()) {
            echo "<script>
                    alert('Danh mục đã được xóa thành công.');
                    window.location.href = 'Admin.php#categories';
                  </script>";
        } else {
            echo "<script>
                    alert('Đã xảy ra lỗi khi xóa danh mục.');
                    window.location.href = 'Admin.php#categories';
                  </script>";
        }

        $stmtDelete->close();
    }

    $stmtCheck->close();
} else {
    echo "<script>
            alert('Mã danh mục không hợp lệ.');
            window.location.href = 'Admin.php#categories';
          </script>";
}
?>
