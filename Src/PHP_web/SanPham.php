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
            echo "</div>";
        }
    } else {
        echo "<p>Chưa có đánh giá nào cho sản phẩm này.</p>";
    }

    // Hiển thị nút đánh giá sản phẩm nếu người dùng đã đăng nhập
    if (isset($_SESSION['user_id'])) {
        // Link trực tiếp đến trang DanhGiaSP.php để đánh giá sản phẩm
        echo '<a href="DanhGiaSP.php?id=' . $productId . '" class="btn btn-primary mt-2">Đánh giá sản phẩm</a>';
    }

} else {
    echo "<p>Sản phẩm không tồn tại.</p>";
}
?>
