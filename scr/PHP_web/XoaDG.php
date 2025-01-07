<?php
session_start();
include 'db.php';

// Kiểm tra nếu người dùng đã đăng nhập và có id đánh giá
if (isset($_SESSION['user_id']) && isset($_GET['review_id'])) {
    $userId = $_SESSION['user_id'];
    $reviewId = $_GET['review_id'];

    // Kiểm tra đánh giá có thuộc về người dùng hiện tại hay không
    $sqlCheckReview = "SELECT * FROM DanhGiaSanPham WHERE MaDanhGia = '$reviewId' AND MaNguoiDung = '$userId'";
    $resultCheckReview = $conn->query($sqlCheckReview);

    if ($resultCheckReview->num_rows > 0) {
        // Xóa đánh giá khỏi cơ sở dữ liệu
        $sqlDelete = "DELETE FROM DanhGiaSanPham WHERE MaDanhGia = '$reviewId'";
        if ($conn->query($sqlDelete) === TRUE) {
            echo "<p>Đánh giá của bạn đã được xóa.</p>";
        } else {
            echo "<p>Đã xảy ra lỗi khi xóa đánh giá.</p>";
        }
    } else {
        echo "<p>Không tìm thấy đánh giá của bạn hoặc bạn không có quyền xóa đánh giá này.</p>";
    }
} else {
    echo "<p>Bạn cần đăng nhập để xóa đánh giá.</p>";
}

// Sau khi xử lý, chuyển hướng về trang chủ (index.php)
echo "<script>
        setTimeout(function(){
            window.location.href = 'index.php'; // Chuyển hướng về trang chủ
        }, 2000);
      </script>";
?>
