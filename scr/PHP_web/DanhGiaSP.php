<?php
session_start();
include 'db.php';

$productId = isset($_GET['id']) ? $_GET['id'] : 0;

// Kiểm tra nếu sản phẩm tồn tại
$sql = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Kiểm tra người dùng đã đăng nhập và tồn tại sản phẩm
if ($product && isset($_SESSION['user_id'])) {
    // Hiển thị form đánh giá sản phẩm
    echo '<h4 class="mt-4 text-center">Đánh giá sản phẩm</h4>';
    echo '<form method="POST" action="" class="needs-validation" novalidate>';
    echo '<div class="mb-3">';
    echo '<label for="rating" class="form-label">Sao:</label>';
    echo '<select name="rating" id="rating" class="form-select" required>';
    echo '<option value="1">1 sao</option>';
    echo '<option value="2">2 sao</option>';
    echo '<option value="3">3 sao</option>';
    echo '<option value="4">4 sao</option>';
    echo '<option value="5">5 sao</option>';
    echo '</select>';
    echo '</div>';
    echo '<div class="mb-3">';
    echo '<label for="review" class="form-label">Nội dung đánh giá:</label>';
    echo '<textarea name="review" id="review" rows="4" class="form-control" required></textarea>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Gửi đánh giá</button>';
    echo '</form>';

    // Xử lý đánh giá khi người dùng gửi form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['review'])) {
        $rating = $_POST['rating'];
        $reviewContent = $_POST['review'];
        $userId = $_SESSION['user_id']; // ID người dùng từ session

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $sqlCheckReview = "SELECT * FROM DanhGiaSanPham WHERE MaNguoiDung = '$userId' AND MaSanPham = '$productId'";
        $resultCheckReview = $conn->query($sqlCheckReview);

        if ($resultCheckReview->num_rows > 0) {
            echo "<p>Bạn đã đánh giá sản phẩm này rồi.</p>";
        } else {
            // Tạo mã đánh giá theo dạng DG_userID_productID
            $reviewId = "DG_" . $userId . "_" . $productId;

            // Lưu đánh giá vào cơ sở dữ liệu
            $insertReview = "INSERT INTO DanhGiaSanPham (MaDanhGia, MaNguoiDung, MaSanPham, SoSaoDanhGia, NoiDungDanhGia) 
                             VALUES ('$reviewId', '$userId', '$productId', '$rating', '$reviewContent')";
            if ($conn->query($insertReview) === TRUE) {
                // Chuyển hướng về trang index.php sau khi đánh giá xong
                echo "<p>Cảm ơn bạn đã đánh giá sản phẩm!</p>";
                echo "<script>
                        setTimeout(function(){
                            window.location.href = 'index.php'; // Chuyển hướng về trang index
                        }, 2000);
                      </script>";
            } else {
                echo "<p>Đã xảy ra lỗi trong quá trình gửi đánh giá.</p>";
            }
        }
    }

    // Hiển thị danh sách đánh giá sản phẩm
    echo '<h4 class="mt-4">Danh sách đánh giá</h4>';
    $sqlReviews = "SELECT * FROM DanhGiaSanPham WHERE MaSanPham = '$productId'";
    $resultReviews = $conn->query($sqlReviews);

    if ($resultReviews->num_rows > 0) {
        while ($review = $resultReviews->fetch_assoc()) {
            echo "<div>";
            echo "<strong>" . htmlspecialchars($review['MaNguoiDung']) . ":</strong> ";
            echo str_repeat('★', $review['SoSaoDanhGia']) . str_repeat('☆', 5 - $review['SoSaoDanhGia']);
            echo "<p>" . htmlspecialchars($review['NoiDungDanhGia']) . "</p>";

            // Hiển thị nút xóa dưới đánh giá của chính người dùng
            if ($review['MaNguoiDung'] == $_SESSION['user_id']) {
                echo '<a href="XoaDG.php?review_id=' . htmlspecialchars($review['MaDanhGia']) . '" class="btn btn-danger btn-sm">Xóa đánh giá</a>';
            }
            echo "</div><hr>";
        }
    } else {
        echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
    }

} else {
    // Nếu không có sản phẩm hoặc người dùng chưa đăng nhập
    echo "<p>Bạn cần đăng nhập để đánh giá sản phẩm này.</p>";
}
?>

<!-- Thêm CSS cho form đẹp hơn -->
<style>
    .form-label {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .form-select, .form-control {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    h4 {
        font-size: 1.5rem;
        color: #333;
    }

    .needs-validation .form-select, .needs-validation .form-control {
        border: 2px solid #ccc;
    }

    .needs-validation .form-select:focus, .needs-validation .form-control:focus {
        border-color: #007bff;
    }
</style>

<!-- Thêm Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Thêm Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
