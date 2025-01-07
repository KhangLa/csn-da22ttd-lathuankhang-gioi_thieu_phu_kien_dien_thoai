<?php
session_start();
include 'db.php';
include 'header.php';

// Lấy danh sách ảnh từ thư mục images
$slideshowImages = glob('../images/1.png'); // Tìm tất cả file .png trong thư mục images
$slideshowImages = array_merge($slideshowImages, glob('../images/2.png'));
$slideshowImages = array_merge($slideshowImages, glob('../images/3.png'));
?>

<section id="slideshow" class="mb-4">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            if (!empty($slideshowImages)) {
                foreach ($slideshowImages as $index => $imagePath) {
                    echo '
                    <div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                        <img src="' . $imagePath . '" class="d-block w-100" alt="Slideshow Image ' . ($index + 1) . '" style="height: 400px; object-fit: cover;">
                    </div>';
                }
            } else {
                echo '<p class="text-center">Không tìm thấy ảnh nào trong thư mục!</p>';
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<?php
$isLoggedIn = isset($_SESSION['user_id']);
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';

$sql = "SELECT * FROM SanPham WHERE 1=1";

if ($search) {
    $sql .= " AND (TenSanPham LIKE '%$search%' OR MoTa LIKE '%$search%')";
}
if ($category) {
    $sql .= " AND MaDanhMuc = '$category'";
}
if ($brand) {
    $sql .= " AND ThuongHieu LIKE '%$brand%'";
}
if ($price_min) {
    $sql .= " AND Gia >= $price_min";
}
if ($price_max) {
    $sql .= " AND Gia <= $price_max";
}

$result = $conn->query($sql);
?>

<section id="search" class="py-3">
    <div class="container">
        <form action="index.php" method="GET">
            <div class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle w-100 border" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            if ($category) {
                                $selected_category_sql = "SELECT TenDanhMuc FROM DanhMuc WHERE MaDanhMuc = '$category'";
                                $selected_category_result = $conn->query($selected_category_sql);
                                $selected_category = $selected_category_result->fetch_assoc();
                                echo $selected_category['TenDanhMuc'];
                            } else {
                                echo "Chọn danh mục";
                            }
                            ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="index.php">Tất cả danh mục</a></li>
                            <?php
                            $category_sql = "SELECT * FROM DanhMuc";
                            $category_result = $conn->query($category_sql);
                            while ($category_row = $category_result->fetch_assoc()) {
                                echo '<li><a class="dropdown-item" href="index.php?category=' . $category_row['MaDanhMuc'] . '">' . $category_row['TenDanhMuc'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="text" name="brand" class="form-control" placeholder="Thương hiệu" value="<?php echo htmlspecialchars($brand); ?>">
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="number" name="price_min" class="form-control" placeholder="Giá từ" value="<?php echo htmlspecialchars($price_min); ?>">
                        <span class="input-group-text">đến</span>
                        <input type="number" name="price_max" class="form-control" placeholder="Giá đến" value="<?php echo htmlspecialchars($price_max); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
</section>

<section id="products" class="py-5">
    <div class="container">
        <h2 class="mb-5" style="text-align: left;">Sản phẩm nổi bật</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-md-4 mb-3">
                        <div class="p-3 card product-card">
                            <img src="' . $row["HinhAnh"] . '" alt="' . $row["TenSanPham"] . '" class="card-img-top" style="height:200px; object-fit:cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">' . $row["TenSanPham"] . '</h5>
                                <p class="card-text text-success">' . number_format($row["Gia"], 0, ',', '.') . ' đ</p>
                                <button class="btn btn-primary" onclick="showDetails(\'' . $row["MaSanPham"] . '\')">Xem Chi Tiết</button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
            }
            ?>
        </div>
    </div>
</section>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Chi Tiết Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productDetails"></div>
            <div class="modal-footer">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <label for="quantity" class="form-label me-2">Số lượng:</label>
                        <input type="number" id="quantity" class="form-control" value="1" min="1" style="width: 80px;">
                    </div>
                    <button type="button" class="btn btn-primary" id="addToCartButton" onclick="addToCart()">Đặt hàng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentProductId = null;

function showDetails(productId) {
    currentProductId = productId;
    fetch('SanPham.php?id=' + productId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('productDetails').innerHTML = data;
            var productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        });
}

function addToCart() {
    if (!<?php echo json_encode($isLoggedIn); ?>) {
        // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
        window.location.href = 'DangNhap.php'; // Thay đổi 'login.php' thành đường dẫn trang đăng nhập của bạn
        return;
    }

    const quantity = document.getElementById('quantity').value;
    const productId = currentProductId;

    fetch('ThemGioHang.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `productId=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Sản phẩm đã được thêm vào giỏ hàng!');
        } else {
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        }
    });
}
</script>
</body>
</html>
