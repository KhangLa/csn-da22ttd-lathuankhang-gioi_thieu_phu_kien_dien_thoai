<footer class="bg-primary text-white py-4 mt-auto">
    <style>
        /* Đảm bảo toàn bộ chiều cao của trang được sử dụng */
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Bố cục flex cho body */
        body {
            display: flex;
            flex-direction: column;
        }

        /* Nội dung chính chiếm phần còn lại của trang */
        main {
            flex: 1;
        }

        /* Footer vẫn ở dưới cùng */
        footer {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            position: relative;
            width: 100%;
        }
    </style>
    <div class="container">
        <div class="row">
            <!-- Cột 1: Thông tin liên hệ -->
            <div class="col-md-3 mb-3 mb-md-0">
                <h5>Phụ Kiện Điện Thoại Giá Sỉ</h5>
                <p>
                    Thời gian mở cửa: 7:00-21:00<br>
                    Hotline: 0123456789<br>
                    E-mail: phukienQuocBao@gmail.com<br>
                    Địa chỉ: Thành phố Trà Vinh
                </p>
            </div>

            <!-- Cột 2: Hỗ trợ khách hàng -->
            <div class="col-md-3 mb-3 mb-md-0">
                <h5>Hỗ trợ khách hàng</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white text-decoration-none">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Giao hàng & Thanh toán</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Hướng dẫn mua online</a></li>
                </ul>
            </div>

            <!-- Cột 3: Quy định mua sỉ -->
            <div class="col-md-3 mb-3 mb-md-0">
                <h5>Lưu ý</h5>
                <ul class="list-unstyled">
                    <li>Miễn Ship vào Thứ 7 và Chủ Nhật</li>
                    <li>Thời gian nhận hàng có thể từ 2h-4h tuỳ địa điểm của khách</li>
                    <li>Giao hàng toàn khu vực thuộc Trà Vinh</li>
                </ul>
            </div>

            <!-- Cột 4: Kết nối với chúng tôi -->
            <div class="col-md-3 text-center">
                <h5>Kết nối với chúng tôi</h5>
                <a href="#" class="mx-2"><img src="../images/facebook.png" alt="Facebook" width="30"></a>
                <a href="#" class="mx-2"><img src="../images/Instagram.png" alt="Instagram" width="30"></a>
                <a href="#" class="mx-2"><img src="../images/zalo.png" alt="Zalo" width="30"></a>
            </div>
        </div>
        <hr class="text-white">
        <p class="text-center mb-0">© phukienQuocBao@gmail.com. Copyright, All Rights Reserved.</p>
    </div>
</footer>
