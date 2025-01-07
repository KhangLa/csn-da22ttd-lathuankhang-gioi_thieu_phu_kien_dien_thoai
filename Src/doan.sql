-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 07, 2025 lúc 04:07 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `doan`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaChiTietDonHang` varchar(10) NOT NULL,
  `MaDonHang` varchar(10) NOT NULL,
  `MaSanPham` varchar(10) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`MaChiTietDonHang`, `MaDonHang`, `MaSanPham`, `SoLuong`, `DonGia`) VALUES
('DH01-SDP03', 'DH01', 'SDP03', 2, 400000.00),
('DH01-TN01', 'DH01', 'TN01', 2, 250000.00),
('DH02-SDP01', 'DH02', 'SDP01', 1, 450000.00),
('DH02-TN01', 'DH02', 'TN01', 1, 250000.00),
('DH02-TN03', 'DH02', 'TN03', 1, 100000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgiasanpham`
--

CREATE TABLE `danhgiasanpham` (
  `MaDanhGia` varchar(20) NOT NULL,
  `MaNguoiDung` varchar(10) NOT NULL,
  `MaSanPham` varchar(10) NOT NULL,
  `SoSaoDanhGia` int(11) DEFAULT NULL CHECK (`SoSaoDanhGia` >= 1 and `SoSaoDanhGia` <= 5),
  `NoiDungDanhGia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgiasanpham`
--

INSERT INTO `danhgiasanpham` (`MaDanhGia`, `MaNguoiDung`, `MaSanPham`, `SoSaoDanhGia`, `NoiDungDanhGia`) VALUES
('DG_Hao02_SDP01', 'Hao02', 'SDP01', 5, 'Được'),
('DG_Khang01_SDP01', 'Khang01', 'SDP01', 5, 'Tốt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` varchar(10) NOT NULL,
  `TenDanhMuc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`MaDanhMuc`, `TenDanhMuc`) VALUES
('CS', 'Cáp Sạc'),
('OL', 'Ốp Lưng'),
('SDP', 'Sạc Dự Phòng'),
('TN', 'Tai Nghe');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `MaDonHang` varchar(10) NOT NULL,
  `MaNguoiDung` varchar(10) NOT NULL,
  `NgayDatHang` datetime NOT NULL,
  `TongGia` decimal(10,2) NOT NULL,
  `TrangThai` enum('Đang Xử Lý','Đã Giao','Đã Hủy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`MaDonHang`, `MaNguoiDung`, `NgayDatHang`, `TongGia`, `TrangThai`) VALUES
('DH01', 'Khang01', '2025-01-06 21:34:12', 1300000.00, 'Đang Xử Lý'),
('DH02', 'Hao02', '2025-01-06 21:39:17', 800000.00, 'Đang Xử Lý');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` varchar(10) NOT NULL,
  `TenNguoiDung` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('Khách Hàng','Quản Trị Viên') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `TenNguoiDung`, `Email`, `MatKhau`, `VaiTro`) VALUES
('CoThanh', 'CoThanh', 'admin@gmail.com', 'admin1234', 'Quản Trị Viên'),
('Hao02', 'Hào', 'Hào@gmail.com', 'Hao', 'Khách Hàng'),
('Khang01', 'KhangLa', 'Khang@gmail.com', 'Khang', 'Khách Hàng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` varchar(10) NOT NULL,
  `MaDanhMuc` varchar(10) NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `ThuongHieu` varchar(255) DEFAULT NULL,
  `SoLuongTonKho` int(11) NOT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `MaDanhMuc`, `TenSanPham`, `MoTa`, `Gia`, `ThuongHieu`, `SoLuongTonKho`, `HinhAnh`) VALUES
('CS01', 'CS', 'Bộ Adapter sạc kèm cáp Type C - Type C PD 45W Samsung EP-T4511', 'Model: EP-T4511\r\nChức năng: Sạc\r\nĐầu vào:\r\n100-240V~50/60Hz, 0.7A\r\nĐầu ra:\r\nType C: 5V - 3A, 9V - 3A, 15V - 3A, 20V - 2.25A (Max 45W)\r\nDòng sạc tối đa: 45 W\r\nKích thước:\r\nDài 8.18 cm - Ngang 4.35 cm - Cao 2.8 cm\r\nSản xuất tại: Việt Nam\r\nThương hiệu của: Samsung.', 830000.00, 'SamSung', 100, '../images/SanPham/Bộ Adapter sạc kèm cáp Type C PD 45W Samsung EP-T4511.jpg'),
('SDP01', 'SDP', 'Sạc dự phòng Polymer 20000mAh Type C 10.5W G-DX258', 'Dung lượng pin: 20000 mAh\r\nLõi pin: Polymer\r\nThời gian sạc đầy pin: 9 - 11 giờ (dùng Adapter 2A)\r\nNguồn ra:\r\nUSB: 5V - 2.1A (Max 10.5W)\r\nCáp Type C: 5V - 2.1A (Max 10.5W)\r\nCáp Lightning: 5V - 2.1A (Max 10.5W)\r\nNguồn vào:\r\nType C/ Micro USB/ Cáp USB: 5V - 2A\r\nKích thước:\r\nDày 3.5 cm - Rộng 7 cm - Dài 11.4 cm\r\nKhối lượng: 340 g\r\nThương hiệu của: SamSung\r\nSản xuất tại: Trung Quốc\r\n', 450000.00, 'SamSung', 100, '../images/SanPham/Sạc dự phòng Polymer 20000mAh Type C 10.5W AVA+ G-DX258.jpg'),
('SDP02', 'SDP', 'Sạc dự phòng Polymer 10000mAh Magnetic Type C PD 20W JP331', 'Dung lượng pin: 10000 mAh\r\nLõi pin: Polymer\r\nThời gian sạc đầy pin: Khoảng 3 giờ (dùng Adapter 3A)\r\nNguồn ra:\r\nType C: 5V - 3A, 9V - 2.22A, 12V - 1.67A (Max 20 W)\r\nMagnetic: 5 W - 7.5 W - 10 W - 15 W\r\nNguồn vào:\r\nType C: 5V - 3A, 9V - 2A (Max 18 W)\r\nKích thước:\r\nDày 1.91 cm - Rộng 6.65 cm - Dài 10.3 cm\r\nKhối lượng: 192 g\r\nThương hiệu của: Oppo\r\nSản xuất tại: Trung Quốc', 455000.00, 'Oppo', 100, '../images/SanPham/Sạc dự phòng Polymer 10000mAh Magnetic Type C PD 20W Xmobile JP331.jpg'),
('SDP03', 'SDP', 'Pin sạc dự phòng 10000mAh Type C PD QC 3.0 30W Y75', 'Dung lượng pin: 10000 mAh\r\nLõi pin: Li-Ion\r\nThời gian sạc đầy pin: Khoảng 3 giờ (dùng Adapter 3A)\r\nNguồn ra:\r\nType C: 5V - 3A, 9V - 3A, 12V - 2.5A, 15V - 2A, 20V - 1.5A\r\nUSB: 5V - 3A, 9V - 2A, 12V - 1.5V (Max 18W)\r\nNguồn vào:\r\nType C: 5V - 3A, 9V - 2.22A, 12V - 1.67A\r\nKích thước:\r\nDày 2.6 cm - Rộng 5.23 cm - Dài 10.6 cm\r\nKhối lượng: 203 g\r\nThương hiệu của: Sony\r\nSản xuất tại: Trung Quốc\r\n', 400000.00, 'Sony', 100, '../images/SanPham/Pin sạc dự phòng 10000mAh Type C PD QC 3.0 30W Y75.jpg'),
('SDP04', 'SDP', 'Pin sạc dự phòng Polymer 10000mAh Type C PD 20W PPBD2-1020', 'Dung lượng pin: 10000 mAh\r\nLõi pin: Polymer\r\nThời gian sạc đầy pin: 10 - 11 giờ (dùng Adapter 1A) - 5 - 6 giờ (dùng Adapter 2A)\r\nNguồn ra:\r\nUSB: 5V - 3A, 9V - 2A, 12V - 1.5A\r\nType C: (PD) 5V - 3A, 9V - 2.22A (Max 20 W), 12V - 1.5A\r\nNguồn vào:\r\nType C: 5V - 3A, 9V - 2A\r\nKích thước:\r\nDài 13.2 cm - Rộng 6.2 cm - Dày 1.96 cm\r\nKhối lượng: 200 g\r\nThương hiệu của: XiaoMi\r\nSản xuất tại: Trung Quốc', 480000.00, 'Xiaomi', 100, '../images/SanPham/Pin sạc dự phòng Polymer 10000mAh Type C PD 20W Bipow Pro PPBD2-1020.jpg'),
('TN01', 'TN', 'Tai nghe Có Dây Samsung IA500', 'Tương thích: macOS, Android, iOS (iPhone)\r\nJack cắm: 3.5 mm\r\nĐộ dài dây: 125 cm\r\nTiện ích:\r\nCó mic thoại\r\nĐệm tai đi kèm\r\nKết nối cùng lúc: 1 thiết bị\r\nĐiều khiển:\r\nPhím nhấn\r\nPhím điều khiển:\r\nTăng/giảm âm lượng\r\nPhát/dừng chơi nhạc\r\nKhối lượng: 14.76 g\r\nThương hiệu của: Samsung\r\nSản xuất tại: Việt Nam', 250000.00, 'SamSung', 100, '../images/SanPham/Tai nghe Có Dây Samsung IA500.jpg'),
('TN02', 'TN', 'Tai nghe Có dây EP OPPO MH135', 'Tương thích: macOS, Android, iOS, Windows\r\nJack cắm: 3.5 mm\r\nĐộ dài dây: 1.2 m\r\nTiện ích:\r\nCó mic thoại\r\nKết nối cùng lúc: 1 thiết bị\r\nĐiều khiển:\r\nPhím nhấn\r\nPhím điều khiển:\r\nPhát/dừng chơi nhạc\r\nChuyển bài hát\r\nNhận/Ngắt cuộc gọi\r\nKhối lượng: 12.3 g\r\nThương hiệu của: Oppo\r\nSản xuất tại: Trung Quốc', 200000.00, 'Oppo', 100, '../images/SanPham/Tai nghe Có dây EP OPPO MH135.jpg'),
('TN03', 'TN', 'Tai nghe Có dây Sony LiveBass Y231', 'Tương thích: Windows, Android, iOS (iPhone)\r\nJack cắm: 3.5 mm\r\nĐộ dài dây: 1.2 m\r\nTiện ích: Có mic thoại\r\nKết nối cùng lúc: 1 thiết bị\r\nĐiều khiển:\r\nPhím nhấn\r\nPhím điều khiển:\r\nTăng/giảm âm lượng\r\nNhận/Ngắt cuộc gọi\r\nKhối lượng: 21 g\r\nThương hiệu của: Sony\r\nSản xuất tại: Trung Quốc', 100000.00, 'Sony', 100, '../images/SanPham/Tai nghe Có dây Sony LiveBass Y231.jpg'),
('TN04', 'TN', 'Tai nghe Có dây EP Type C Xiaomi', 'Công nghệ âm thanh: Dynamic Driver 12.4 mm\r\nTương thích: Thiết bị hỗ trợ chuyển đổi âm thanh qua cổng Type C\r\nJack cắm: Type C\r\nĐộ dài dây: 130 cm\r\nTiện ích:\r\nCó mic thoại\r\nKhử tiếng ồn AI\r\nKết nối cùng lúc: 1 thiết bị\r\nĐiều khiển:\r\nPhím nhấn\r\nPhím điều khiển:\r\nTăng/giảm âm lượng\r\nPhát/dừng chơi nhạc\r\nChuyển bài hát\r\nNhận/Ngắt cuộc gọi\r\nKhối lượng: 12.82 g\r\nThương hiệu của: Xiaomi\r\nSản xuất tại: Trung Quốc', 150000.00, 'Xiaomi', 100, '../images/SanPham/Tai nghe Có dây EP Type C Xiaomi.jpg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaChiTietDonHang`),
  ADD KEY `MaDonHang` (`MaDonHang`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `danhgiasanpham`
--
ALTER TABLE `danhgiasanpham`
  ADD PRIMARY KEY (`MaDanhGia`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDonHang`),
  ADD KEY `MaNguoiDung` (`MaNguoiDung`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`),
  ADD KEY `MaDanhMuc` (`MaDanhMuc`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `donhang` (`MaDonHang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danhgiasanpham`
--
ALTER TABLE `danhgiasanpham`
  ADD CONSTRAINT `danhgiasanpham_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgiasanpham_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
