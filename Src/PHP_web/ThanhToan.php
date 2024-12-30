<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: DangNhap.php'); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p class='text-center'>Giỏ hàng của bạn hiện tại trống. Vui lòng thêm sản phẩm vào giỏ hàng trước.</p>";
    exit;
}

$totalAmount = 0;
foreach ($_SESSION['cart'] as $productId => $item) {
    $sql = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $totalAmount += $product['Gia'] * $item['quantity'];
    }
}
?>

<section id="checkout" class="py-5">
    <div class="container">
        <h2 class="mb-5">Thanh Toán</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Thông Tin Giỏ Hàng</h4>
                <ul class="list-group">
                    <?php
                    foreach ($_SESSION['cart'] as $productId => $item) {
                        $sql = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $product = $result->fetch_assoc();
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                ' . $product['TenSanPham'] . ' x ' . $item['quantity'] . '
                                <span>' . number_format($product['Gia'] * $item['quantity'], 0, ',', '.') . ' VND</span>
                            </li>';
                        }
                    }
                    ?>
                </ul>
                <h4 class="mt-3">Tổng cộng: <?php echo number_format($totalAmount, 0, ',', '.') ?> VND</h4>
            </div>

            <div class="col-md-6">
                <h4>Thông Tin Thanh Toán</h4>
                <form action="TienHanhThanhToan.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và Tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ giao hàng</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'Footer.php'; ?>