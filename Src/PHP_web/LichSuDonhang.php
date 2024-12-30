<?php
session_start();
include 'db.php';
include 'header.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: DangNhap.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của người dùng
$sql = "SELECT * FROM DonHang WHERE MaNguoiDung = '$userId' ORDER BY NgayDatHang DESC";
$result = $conn->query($sql);
?>

<section id="order-history" class="py-5">
    <div class="container">
        <h2 class="mb-5">Lịch Sử Đơn Hàng</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <strong>Đơn hàng: </strong><?php echo htmlspecialchars($order['MaDonHang']); ?>
                                <span class="float-end"><?php echo date("d/m/Y", strtotime($order['NgayDatHang'])); ?></span>
                            </div>
                            <div class="card-body">
                                <p><strong>Tổng giá:</strong> <?php echo number_format($order['TongGia'], 0, ',', '.'); ?> VND</p>
                                <p><strong>Trạng thái:</strong> <?php echo $order['TrangThai']; ?></p>
                                
                                <h5>Sản phẩm trong đơn hàng:</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tên Sản Phẩm</th>
                                            <th>Số Lượng</th>
                                            <th>Đơn Giá</th>
                                            <th>Tổng Giá</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $orderId = $order['MaDonHang'];
                                        $detailSql = "SELECT * FROM ChiTietDonHang WHERE MaDonHang = '$orderId'";
                                        $detailResult = $conn->query($detailSql);
                                        
                                        while ($detail = $detailResult->fetch_assoc()) {
                                            $productId = $detail['MaSanPham'];
                                            $productSql = "SELECT TenSanPham FROM SanPham WHERE MaSanPham = '$productId'";
                                            $productResult = $conn->query($productSql);
                                            $product = $productResult->fetch_assoc();
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product['TenSanPham']); ?></td>
                                                <td><?php echo $detail['SoLuong']; ?></td>
                                                <td><?php echo number_format($detail['DonGia'], 0, ',', '.'); ?> VND</td>
                                                <td><?php echo number_format($detail['SoLuong'] * $detail['DonGia'], 0, ',', '.'); ?> VND</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>