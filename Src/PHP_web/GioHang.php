<?php
session_start();
include 'db.php';
include 'header.php';

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $productIdToRemove = $_GET['remove'];
    if (isset($_SESSION['cart'][$productIdToRemove])) {
        unset($_SESSION['cart'][$productIdToRemove]); // Xóa sản phẩm khỏi giỏ hàng
        echo "<p class='text-center text-success'>Sản phẩm đã được xóa khỏi giỏ hàng.</p>";
    } else {
        echo "<p class='text-center text-danger'>Sản phẩm không tồn tại trong giỏ hàng.</p>";
    }
}

// Hiển thị giỏ hàng
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo '<h2>Giỏ Hàng của Bạn</h2>';
    echo '<table class="table">';
    echo '<thead><tr><th>Tên Sản Phẩm</th><th>Số Lượng</th><th>Hành Động</th></tr></thead>';
    echo '<tbody>';

    foreach ($_SESSION['cart'] as $productId => $cartItem) {
        $sql = "SELECT * FROM SanPham WHERE MaSanPham = '$productId'";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();

        echo '<tr>';
        echo '<td>' . htmlspecialchars($product['TenSanPham']) . '</td>';
        echo '<td>' . htmlspecialchars($cartItem['quantity']) . '</td>';
        echo '<td><a href="GioHang.php?remove=' . htmlspecialchars($productId) . '" class="btn btn-danger">Xóa</a></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '<a href="ThanhToan.php" class="btn btn-primary">Tiến Hành Thanh Toán</a>';
} else {
    echo '<p>Giỏ hàng của bạn đang trống.</p>';
}

include 'Footer.php';
?>
