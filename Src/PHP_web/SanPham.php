<?php
session_start();
include 'db.php';

$productId = isset($_GET['id']) ? $_GET['id'] : 0;

// Lấy thông tin chi tiết sản phẩm
$sql = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Kiểm tra sản phẩm tồn tại
if ($product) {
    echo '<div class="row">';
    // Phần ảnh sản phẩm bên trái
    echo '<div class="col-md-6 text-center">';
    echo '<img src="' . htmlspecialchars($product['HinhAnh']) . '" alt="' . htmlspecialchars($product['TenSanPham']) . '" class="img-fluid" style="max-width: 100%;">';
    echo '</div>';

    // Phần thông tin sản phẩm bên phải
    echo '<div class="col-md-6">';
    echo '<h3>' . htmlspecialchars($product['TenSanPham']) . '</h3>';
    echo '<p><strong>Thương hiệu:</strong> ' . htmlspecialchars($product['ThuongHieu']) . '</p>';
    echo '<p><strong>Giá:</strong> ' . number_format($product['Gia'], 0, ',', '.') . ' VND</p>';
    echo '<p><strong>Mô tả:</strong></p>';
    echo '<p>' . nl2br(htmlspecialchars_decode($product['MoTa'])) . '</p>';
    echo '</div>';
    echo '</div>'; // Kết thúc dòng (row)

    // Hiển thị các đánh giá
    echo '<h4 class="mt-4">Đánh giá sản phẩm</h4>';
    $sqlReviews = "SELECT * FROM DanhGiaSanPham WHERE MaSanPham = '$productId'";
    $resultReviews = $conn->query($sqlReviews);

    if ($resultReviews->num_rows > 0) {
        while ($review = $resultReviews->fetch_assoc()) {
            echo "<div>";
            echo "<strong>" . htmlspecialchars($review['MaNguoiDung']) . ":</strong> ";
            echo str_repeat('★', $review['SoSaoDanhGia']) . str_repeat('☆', 5 - $review['SoSaoDanhGia']);
            echo "<p>" . htmlspecialchars($review['NoiDungDanhGia']) . "</p>";
            
            // Nút xóa chỉ hiển thị khi người dùng là chủ của đánh giá
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['MaNguoiDung']) {
                echo '<form method="POST" action="SanPham.php?id=' . $productId . '">
                        <input type="hidden" name="review_id" value="' . htmlspecialchars($review['MaDanhGia']) . '">
                        <button type="submit" name="delete_review" class="btn btn-danger btn-sm">Xóa đánh giá</button>
                      </form>';
            }
            echo "</div>";
        }
    } else {
        echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
    }

    // Form đánh giá (nếu người dùng đã đăng nhập)
    if (isset($_SESSION['user_id'])) {
        echo '<form method="POST" action="SanPham.php?id=' . $productId . '">';
        echo '<label for="rating">Sao:</label>';
        echo '<select name="rating" id="rating">
                <option value="1">1 sao</option>
                <option value="2">2 sao</option>
                <option value="3">3 sao</option>
                <option value="4">4 sao</option>
                <option value="5">5 sao</option>
              </select>';
        echo '<br>';
        echo '<label for="review">Nội dung đánh giá:</label>';
        echo '<textarea name="review" id="review" rows="4" cols="50"></textarea>';
        echo '<br>';
        echo '<button type="submit" class="btn btn-primary mt-2">Gửi đánh giá</button>';
        echo '</form>';
    }

    // Xử lý gửi đánh giá
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['rating']) && isset($_POST['review'])) {
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
                    // Sau khi lưu đánh giá, hiển thị đánh giá mới và lời cảm ơn
                    echo "<div>";
                    echo "<strong>" . htmlspecialchars($userId) . ":</strong> ";
                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                    echo "<p>" . htmlspecialchars($reviewContent) . "</p>";
                    echo "</div>";
                    echo "<p>Cảm ơn bạn đã đánh giá sản phẩm!</p>";
                } else {
                    echo "<p>Đã xảy ra lỗi trong quá trình gửi đánh giá.</p>";
                }
            }
        }

        // Xử lý xóa đánh giá
        if (isset($_POST['delete_review'])) {
            $reviewId = $_POST['review_id'];
            // Lấy ID người dùng từ session
            $userId = $_SESSION['user_id'];

            // Kiểm tra người dùng có quyền xóa đánh giá
            $sqlCheckOwner = "SELECT * FROM DanhGiaSanPham WHERE MaDanhGia = '$reviewId' AND MaNguoiDung = '$userId'";
            $resultCheckOwner = $conn->query($sqlCheckOwner);

            if ($resultCheckOwner->num_rows > 0) {
                // Xóa đánh giá khỏi cơ sở dữ liệu
                $deleteReview = "DELETE FROM DanhGiaSanPham WHERE MaDanhGia = '$reviewId' AND MaNguoiDung = '$userId'";
                if ($conn->query($deleteReview) === TRUE) {
                    echo "<p>Đánh giá đã được xóa thành công.</p>";
                    // Tải lại trang để cập nhật lại danh sách đánh giá
                    header("Location: SanPham.php?id=" . $productId);
                    exit();
                } else {
                    echo "<p>Đã xảy ra lỗi trong quá trình xóa đánh giá.</p>";
                }
            } else {
                echo "<p>Không có quyền xóa đánh giá này.</p>";
            }
        }
    }
} else {
    echo "<p>Sản phẩm không tồn tại.</p>";
}
?>