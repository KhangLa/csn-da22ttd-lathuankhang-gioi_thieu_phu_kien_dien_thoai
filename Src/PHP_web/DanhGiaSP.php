<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['review'], $_POST['product_id'])) {
    $rating = intval($_POST['rating']);
    $review = htmlspecialchars(trim($_POST['review']));
    $productId = intval($_POST['product_id']);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Bạn phải đăng nhập để đánh giá sản phẩm.']);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $reviewId = "DG_" . $userId . "_" . $productId;

    $sqlCheck = "SELECT * FROM DanhGiaSanPham WHERE MaNguoiDung = ? AND MaSanPham = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param('ii', $userId, $productId);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows == 0) {
        $sqlInsert = "INSERT INTO DanhGiaSanPham (MaDanhGia, MaNguoiDung, MaSanPham, SoSaoDanhGia, NoiDungDanhGia) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param('siiss', $reviewId, $userId, $productId, $rating, $review);

        if ($stmtInsert->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Đã xảy ra lỗi, vui lòng thử lại.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bạn đã đánh giá sản phẩm này rồi.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thông tin đánh giá không hợp lệ.']);
}
?>
