<?php

use App\Http\Controllers\Admin\TrangChuAdmin\DonHangController;
use App\Http\Controllers\Admin\TrangChuAdmin\KhachHangController;
use App\Http\Controllers\Admin\TrangChuAdmin\KhuyenMaiController;
use App\Http\Controllers\Admin\TrangChuAdmin\NhanVienController;
use App\Http\Controllers\Admin\TrangChuAdmin\ThongKeController;
use App\Http\Controllers\Admin\TrangChuAdmin\TichDiemController;
use App\Http\Controllers\Admin\TrangChuAdmin\TrangChuAdminController;
use App\Http\Controllers\QL_DangNhap\DangNhapController;
use App\Http\Controllers\QL_GioHang\GioHangController;
use App\Http\Controllers\ThanhToan\ThanhToanController;
use App\Http\Controllers\TrangChu\SanPhamController;
use App\Http\Controllers\TrangChu\TrangChuController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/env', function () {
    return [
        'DB_HOST' => env('DB_HOST'),
        'DB_PORT' => env('DB_PORT'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'DB_USERNAME' => env('DB_USERNAME'),
        'DB_PASSWORD' => env('DB_PASSWORD'),
    ];
});

//Admin
// Trang Chủ admin
Route::get('/admin/trangchu_admin/trang-chu-admin', [TrangChuAdminController::class, 'index'])->name('trang-chu-admin');
Route::get('/admin/trangchu_admin/trang-chu-admin/{loaiSanPham}', [TrangChuAdminController::class, 'danhSachTheoLoai'])->name('trang-chu-admin-theo-loai');
Route::get('/admin/search', [TrangChuAdminController::class, 'timKiem'])->name('admin-tim-kiem');
Route::get('/admin/trangchu_admin/don-hang-can-duyet', [DonHangController::class, 'index'])->name('duyet-don');
Route::get('/admin/trangchu_admin/chi-tiet-hoa-don/{id}', [DonHangController::class, 'chitiethoadon'])->name('chi-tiet-hoa-don');
Route::post('/chinh-sua-thong-tin-hoa-don', [DonHangController::class, 'chinhSuaThongTinDonHang'])->name('chinh-sua-thong-tin-don-hang');
Route::post('/duyet-don-hang', [DonHangController::class, 'duyetDonHang'])->name('duyet-don-hang');

Route::get('/admin/trangchu_admin/khuyen-mai', [KhuyenMaiController::class, 'index'])->name('khuyen-mai');
Route::get('/admin/trangchu_admin/chi-tiet-khuyen-mai/{id}', [KhuyenMaiController::class, 'chiTietKhuyenMai'])->name('chi-tiet-khuyen-mai');
Route::post('/chinh-sua-thong-tin-khuyen-mai', [KhuyenMaiController::class, 'chinhSuaThongTinKhuyenMai'])->name('chinh-sua-thong-tin-khuyen-mai');
Route::get('/admin/trangchu_admin/tao-moi-khuyen-mai', [KhuyenMaiController::class, 'taoMoiKhuyenMai'])->name('tao-moi-khuyen-mai');
Route::post('/them-khuyen-mai', [KhuyenMaiController::class, 'themKhuyenMai'])->name('them-khuyen-mai');
Route::get('/xoa-khuyen-mai/{id}', [KhuyenMaiController::class, 'xoaKhuyenMai'])->name('xoa-khuyen-mai');

Route::get('/admin/trangchu_admin/chi-tiet-san-pham-admin/{id}', [TrangChuAdminController::class, 'chiTietSanPham'])->name('san-pham-chi-tiet-admin');
Route::get('/xoa-san-pham/{id}', [SanPhamController::class, 'xoaSanPham'])->name('xoa-san-pham');
Route::get('/admin/trangchu_admin/cap-nhat-san-pham/{id}', [SanPhamController::class, 'index_capnhatSanPham'])->name('cap-nhat-san-pham');
Route::post('/cap-nhat-sp', [SanPhamController::class, 'capnhatSanPham'])->name('cap-nhat-sp');

Route::get('/xoa-binh-luan/{MaSP}/{MaKH}', [TrangChuAdminController::class, 'xoaBinhLuan'])->name('xoa-binh-luan');
Route::get('/admin/trangchu_admin/tich-diem', [TichDiemController::class, 'index'])->name('tich-diem');
Route::post('/cap-nhat-cau-hinh-tich-diem', [TichDiemController::class, 'capNhatTrangThai'])->name('cap-nhat-cau-hinh-tich-diem');
Route::post('/them-cau-hinh-tich-diem', [TichDiemController::class, 'themCauHinh'])->name('them-cau-hinh-tich-diem');
Route::get('/xoa-cau-hinh-tich-diem/{matichdiem}', [TichDiemController::class, 'xoaCauHinhTichDiem'])->name('xoa-cau-hinh-tich-diem');

Route::get('/admin/trangchu_admin/xuat-kho-don-hang', [DonHangController::class, 'xuatKhoDonHang'])->name('xuat-kho-don-hang');
Route::post('/xuat-kho', [DonHangController::class, 'xuatKho'])->name('xuat-kho');
Route::get('/admin/trangchu_admin/trang-thai-don-hang', [DonHangController::class, 'trangThaiDonHang'])->name('trang-thai-don-hang');
Route::post('/cap-nhat-trang-thai-don-hang', [DonHangController::class, 'capNhatTrangThaiDonHang'])->name('cap-nhat-trang-thai-don-hang');

Route::get('/admin/trangchu_admin/danh-sach-khach-hang', [KhachHangController::class, 'index'])->name('danh-sach-khach-hang');
Route::get('/tim-kiem-khach-hang', [KhachHangController::class, 'timKiem'])->name('tim-kiem-khach-hang');
Route::get('/vo-hieu-khach-hang/{id}', [KhachHangController::class, 'voHieu'])->name('vo-hieu-khach-hang');
Route::get('/admin/trangchu_admin/danh-sach-khach-hang-vo-hieu-hoa', [KhachHangController::class, 'index_KHvohieuhoa'])->name('danh-sach-khach-hang-vo-hieu-hoa');
Route::get('/admin/trangchu_admin/danh-sach-nhan-vien', [NhanVienController::class, 'index'])->name('danh-sach-nhan-vien');
Route::get('/tim-kiem-nhan-vien', [NhanVienController::class, 'timKiem'])->name('tim-kiem-nhan-vien');
Route::get('/vo-hieu-nhan-vien/{id}', [NhanVienController::class, 'voHieu'])->name('vo-hieu-nhan-vien');
Route::post('/them-nhan-vien', [NhanVienController::class, 'themNhanVien'])->name('them-nhan-vien');
Route::get('/admin/trangchu_admin/danh-sach-nhan-vien-vo-hieu-hoa', [NhanVienController::class, 'index_NVvohieuhoa'])->name('danh-sach-nhan-vien-vo-hieu-hoa');
Route::get('/tim-kiem-nhan-vien-vo-hieu-hoa', [NhanVienController::class, 'timKiem_NVvohieuhoa'])->name('tim-kiem-nhan-vien-vo-hieu-hoa');
Route::get('/kich-hoat-nhan-vien/{id}', [NhanVienController::class, 'kichHoat'])->name('kich-hoat-nhan-vien');
Route::get('/admin/trangchu_admin/thong-tin-nhan-vien', [NhanVienController::class, 'thongTinCaNhan'])->name('thong-tin-nhan-vien');
Route::post('/cap-nhat-tai-khoan-nhan-vien', [NhanVienController::class, 'capNhatTaiKhoan'])->name('cap-nhat-tai-khoan-nhan-vien');

Route::get('/admin/trangchu_admin/thong-ke-doanh-thu-theo-ngay', [ThongKeController::class, 'thongKeTheoNgay'])->name('thong-ke-doanh-thu-theo-ngay');
Route::post('/thong-ke-ngay-chon', [ThongKeController::class, 'thongKeNgayChon'])->name('thong-ke-ngay-chon');
Route::get('/admin/trangchu_admin/thong-ke-doanh-thu-theo-tuan', [ThongKeController::class, 'thongKeTheoTuan'])->name('thong-ke-doanh-thu-theo-tuan');
Route::post('/thong-ke-tuan-chon', [ThongKeController::class, 'thongKeTuanChon'])->name('thong-ke-tuan-chon');
Route::get('/admin/trangchu_admin/thong-ke-doanh-thu-theo-thang', [ThongKeController::class, 'thongKeTheoThang'])->name('thong-ke-doanh-thu-theo-thang');
Route::post('/thong-ke-thang-chon', [ThongKeController::class, 'thongKeThangChon'])->name('thong-ke-thang-chon');
Route::get('/admin/trangchu_admin/thong-ke-doanh-thu-theo-quy', [ThongKeController::class, 'thongKeTheoQuy'])->name('thong-ke-doanh-thu-theo-quy');
Route::post('/thong-ke-quy-chon', [ThongKeController::class, 'thongKeQuyChon'])->name('thong-ke-quy-chon');


//Người dùng
// Trang Chủ
Route::get('/trangchu/trang-chu-dien-may', [TrangChuController::class, 'index'])->name('trang-chu-dien-may');
Route::get('/trangchu/trang-chu-dien-may/{loaiSanPham}', [TrangChuController::class, 'danhSachTheoLoai'])->name('trang-chu-dien-may-theo-loai');
Route::get('/search', [TrangChuController::class, 'timKiem'])->name('tim-kiem');
Route::get('/trangchu/chi-tiet-san-pham/{id}', [SanPhamController::class, 'index'])->name('sanpham.chitiet');
Route::get('/trangchu/thong-ke', [TrangChuController::class, 'thongKe'])->name('thong-ke');
Route::get('/trangchu/don-hang-ca-nhan', [TrangChuController::class, 'donHangCaNhan'])->name('don-hang-ca-nhan');
Route::get('/trangchu/chi-tiet-don-hang/{id}', [TrangChuController::class, 'chitietdonhang'])->name('chi-tiet-don-hang');
Route::post('/them-binh-luan', [TrangChuController::class, 'themBinhLuan'])->name('them-binh-luan');
Route::get('/trangchu/thong-tin-can-nhan', [TrangChuController::class, 'thongTinCaNhan'])->name('thong-tin-can-nhan');
Route::post('/cap-nhat-tai-khoan', [TrangChuController::class, 'capNhatTaiKhoan'])->name('cap-nhat-tai-khoan');
Route::get('/trangchu/voucher-ca-nhan', [KhuyenMaiController::class, 'voucherCaNhan'])->name('voucher-ca-nhan');
Route::get('/trangchu/doi-diem-voucher', [KhuyenMaiController::class, 'doiDiem'])->name('doi-diem');
Route::get('/doi-voucher/{id}', [KhuyenMaiController::class, 'doiVoucher'])->name('doi-voucher');



// Giỏ Hảng
Route::get('/giohang/gio-hang-cua-ban', [GioHangController::class, 'index_GioHang'])->name('gio-hang-cua-ban');
Route::post('/them-vao-gio-hang', [GioHangController::class, 'themVaoGioHang'])->name('them-vao-gio-hang');
Route::get('/xoa-san-pham/{MaGH}/{MaSP}', [GioHangController::class, 'XoaKhoiGioHang'])->name('xoa-khoi-gio-hang');
Route::post('/cap-nhat-so-luong', [GioHangController::class, 'CapNhatSoLuong'])->name('cap-nhat-so-luong-gio-hang');

//Thanh toán
Route::match(['get', 'post'],'/thanhtoan/lap-hoa-don', [ThanhToanController::class, 'index_HoaDon'])->name('lap-hoa-don');
Route::post('/check-discounts', [ThanhToanController::class, 'checkDiscounts'])->name('check-discounts');
Route::post('/thanh-toan', [ThanhToanController::class, 'thanhToan'])->name('thanh-toan');
Route::post('/vnpay-payment', [ThanhToanController::class, 'thanhtoan_vnpay'])->name('thanh-toan-vnpay');
Route::get('/thanhtoan/vnpay-return', [ThanhToanController::class, 'vnpayReturn'])->name('vnpay.return');
Route::post('/momo-payment', [ThanhToanController::class, 'thanhtoan_Momo'])->name('thanh-toan-momo');
Route::get('/thanhtoan/momo-return', [ThanhToanController::class, 'handleMomoReturn'])->name('momoReturn');

//Chức năng đăng nhập, đăng kí, resetpassword
Route::get('/ql_dangnhap', [DangNhapController::class, 'index'])->name('dangnhap');
Route::post('/ql_dangnhap/dangnhap', [DangNhapController::class, 'dangnhap'])->name('dangnhap.post');
Route::get('/ql_dangnhap/dangnhap', [DangNhapController::class, 'dangxuat'])->name('dangxuat');
Route::get('/ql_dangnhap/quen-mat-khau', [DangNhapController::class, 'indexquenmatkhau'])->name('quen-mat-khau');
Route::post('/ql_dangnhap/quen-mat-khau', [DangNhapController::class, 'forgotpassword'])->name('quen-mat-khau.post');
Route::get('/ql_dangnhap/reset-password/{token}', [DangNhapController::class, 'indexresetpassword'])->name('reset-password');
Route::post('/ql_dangnhap/reset-password/{token}', [DangNhapController::class, 'resetPassword'])->name('reset-password.post');
route::get('/ql_dangnhap/tao-tai-khoan-khach-hang', [DangNhapController::class, 'index_register'])->name('tao-tai-khoan-khach-hang');
route::post('/ql_dangnhap/tao-tai-khoan-khach-hang', [DangNhapController::class, 'register'])->name('tao-tai-khoan-khach-hang.post');