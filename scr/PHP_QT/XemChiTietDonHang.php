<?php
include 'db.php';

if (isset($_GET['maDonHang'])) {
    $maDonHang = $_GET['maDonHang'];

    // Lấy thông tin chi tiết sản phẩm từ bảng chitietdonhang
    $sql = "SELECT c.MaSanPham, c.SoLuong, c.DonGia, s.TenSanPham
            FROM chitietdonhang c
            JOIN sanpham s ON c.MaSanPham = s.MaSanPham
            WHERE c.MaDonHang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maDonHang);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hiển thị thông tin các sản phẩm trong đơn hàng
    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            $thanhTien = $row['SoLuong'] * $row['DonGia'];
            echo "<tr>
                    <td>{$row['TenSanPham']}</td>
                    <td>{$row['SoLuong']}</td>
                    <td>" . number_format($row['DonGia'], 0, ',', '.') . " VND</td>
                    <td>" . number_format($thanhTien, 0, ',', '.') . " VND</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Không có sản phẩm nào trong đơn hàng này.";
    }
}
?>
