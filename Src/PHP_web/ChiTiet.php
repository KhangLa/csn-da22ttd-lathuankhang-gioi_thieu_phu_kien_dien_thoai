<?php
session_start();
include 'db.php';
include 'header.php';

$productId = $_GET['product_id']; // Lấy ID sản phẩm từ URL

// Lấy thông tin sản phẩm từ database
$sqlProduct = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
$resultProduct = $conn->query($sqlProduct);
$product = $resultProduct->fetch_assoc();

// Kiểm tra nếu sản phẩm tồn tại
if (!$product) {
    echo "<p class='text-center text-danger'>Sản phẩm không tồn tại.</p>";
    exit;
}

// Hiển thị thông tin sản phẩm
echo '<h2>' . htmlspecialchars($product['TenSanPham']) . '</h2>';
echo '<p>' . htmlspecialchars($product['MoTa']) . '</p>';
echo '<p>Giá: ' . number_format($product['Gia'], 0, ',', '.') . ' VND</p>';

// Hiển thị nút "Đặt hàng"
echo '<form method="POST" action="cart.php">';
echo '<input type="hidden" name="product_id" value="' . $productId . '">';
echo '<button type="submit" class="btn btn-primary">Đặt hàng</button>';
echo '</form>';

// Hiển thị phần đánh giá
echo '<h3>Đánh giá sản phẩm</h3>';
$sqlReviews = "SELECT * FROM DanhGiaSanPham WHERE MaSanPham = '$productId'";
$resultReviews = $conn->query($sqlReviews);

if ($resultReviews->num_rows > 0) {
    while ($review = $resultReviews->fetch_assoc()) {
        // Hiển thị đánh giá
        echo "<div class='review'>";
        echo "<p><strong>" . htmlspecialchars($review['MaNguoiDung']) . ":</strong></p>";
        echo "<p>Sao: " . str_repeat('★', $review['SoSaoDanhGia']) . str_repeat('☆', 5 - $review['SoSaoDanhGia']) . "</p>";
        echo "<p>" . htmlspecialchars($review['NoiDungDanhGia']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
}

// Form để người dùng đánh giá sản phẩm
if (isset($_SESSION['user_id'])) { // Kiểm tra người dùng đã đăng nhập chưa
    echo '<h3>Đánh giá sản phẩm của bạn</h3>';
    echo '<form method="POST" action="product_detail.php?product_id=' . $productId . '">';
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
    echo '<button type="submit" class="btn btn-success">Gửi đánh giá</button>';
    echo '</form>';
}

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rating']) && isset($_POST['review'])) {
        $rating = $_POST['rating'];
        $reviewContent = $_POST['review'];
        $userId = $_SESSION['user_id']; // ID người dùng từ session
        $productId = $_GET['product_id']; // ID sản phẩm từ URL

        // Lưu đánh giá vào cơ sở dữ liệu
        $insertReview = "INSERT INTO DanhGiaSanPham (MaNguoiDung, MaSanPham, SoSaoDanhGia, NoiDungDanhGia) 
                         VALUES ('$userId', '$productId', '$rating', '$reviewContent')";
        if ($conn->query($insertReview) === TRUE) {
            echo "<p class='text-center text-success'>Cảm ơn bạn đã đánh giá sản phẩm!</p>";
        } else {
            echo "<p class='text-center text-danger'>Có lỗi khi gửi đánh giá: " . $conn->error . "</p>";
        }
    }
}

include 'footer.php';
?>
