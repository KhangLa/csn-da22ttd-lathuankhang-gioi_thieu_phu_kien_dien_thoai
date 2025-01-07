<?php
session_start();
include 'db.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: AdDangNhap.php');
    exit;
}

// Cập nhật trạng thái đơn hàng khi quản trị viên gửi form
if (isset($_POST['update_status'])) {
    $maDonHang = $_POST['maDonHang'];
    $trangThai = $_POST['trangThai'];

    // Cập nhật trạng thái trong cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE donhang SET TrangThai = ? WHERE MaDonHang = ?");
    $stmt->bind_param("si", $trangThai, $maDonHang);
    
    // Kiểm tra xem câu lệnh cập nhật có thành công không
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật trạng thái đơn hàng thành công!');</script>";
    } else {
        echo "<script>alert('Cập nhật trạng thái đơn hàng thất bại!');</script>";
    }
}

// Lấy danh sách đơn hàng
$sql = "SELECT * FROM donhang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý trạng thái đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-dark text-white p-3">
                <div class="text-center mb-4">
                    <img src="../images/logo.png" alt="Logo" class="logo">
                    <p>Trang quản trị</p>
                </div>
                <a href="AdQuanLyDH.php" class="d-block text-white mb-3">Quản lý đơn hàng</a>
                <a href="AdDangXuat.php" class="d-block text-white">Đăng xuất</a>
            </div>

            <!-- Nội dung chính -->
            <div class="col-md-10 p-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Quản lý trạng thái đơn hàng</h2>
                    <a href="Admin.php" class="btn btn-secondary">Quay lại trang quản lý</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="col-small">Mã đơn hàng</th>
                            <th class="col-medium">Mã người dùng</th>
                            <th class="col-medium">Ngày đặt</th>
                            <th class="col-small">Tổng giá</th>
                            <th class="col-small">Trạng thái</th>
                            <th class="col-small">Cập nhật trạng thái</th>
                            <th class="col-small">Xem chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Kiểm tra lỗi trong quá trình truy vấn dữ liệu
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['MaDonHang']}</td>
                                        <td>{$row['MaNguoiDung']}</td>
                                        <td>{$row['NgayDatHang']}</td>
                                        <td>" . number_format($row['TongGia'], 0, ',', '.') . " VND</td>
                                        <td>{$row['TrangThai']}</td>
                                        <td>
                                            <form action='AdQuanLyDH.php' method='POST'>
                                                <input type='hidden' name='maDonHang' value='{$row['MaDonHang']}'>
                                                <select name='trangThai' class='form-control' required>
                                                    <option value='Đã Giao' " . ($row['TrangThai'] == 'Đã Giao' ? 'selected' : '') . ">Đã giao</option>
                                                    <option value='Đã Hủy' " . ($row['TrangThai'] == 'Đã Hủy' ? 'selected' : '') . ">Hủy</option>
                                                    <option value='Đang Xử Lý' " . ($row['TrangThai'] == 'Đang Xử Lý' ? 'selected' : '') . ">Đang xử lý</option>
                                                </select>
                                                <button type='submit' name='update_status' class='btn btn-primary mt-2'>Cập nhật</button>
                                            </form>
                                        </td>
                                        <td>
                                            <button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#orderDetailModal' data-ma-don-hang='{$row['MaDonHang']}'>Xem chi tiết</button>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Không có đơn hàng nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Xem chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailContent">
                    <!-- Nội dung chi tiết đơn hàng sẽ được load vào đây bằng AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS và jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#orderDetailModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Lấy nút nhấn để mở modal
            var maDonHang = button.data('ma-don-hang'); // Lấy mã đơn hàng

            // Gửi AJAX để lấy chi tiết đơn hàng
            $.ajax({
                url: 'XemChiTietDonHang.php', 
                type: 'GET',
                data: { maDonHang: maDonHang },
                success: function(response) {
                    $('#orderDetailContent').html(response);
                }
            });
        });
    </script>
</body>
</html>
