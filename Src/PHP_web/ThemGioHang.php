<?php
session_start();

// Kiểm tra nếu đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện hành động này.']);
    exit;
}

// Kiểm tra nếu có dữ liệu gửi đến từ form
if (isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $quantity = (int)$_POST['quantity'];

    // Kiểm tra nếu giỏ hàng đã tồn tại trong session, nếu chưa tạo mới
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$productId])) {
        // Nếu có rồi, tăng số lượng
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Nếu chưa, thêm sản phẩm mới vào giỏ hàng
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }

    echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm hoặc số lượng.']);
}
?>