<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChiTietApDung;
use App\Models\ChiTietDoiDiem;
use App\Models\DanhMucSanPham;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\LoaiKhuyenMai;
use App\Models\NhanVien;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KhuyenMaiController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        $danhsachkhuyenmai = KhuyenMai::dangHoatDong()->orderBy('NgayBatDau', 'desc')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.khuyen-mai', [
            'title' => 'Khuyến Mãi',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachkhuyenmais' => $danhsachkhuyenmai,
        ]);
    }
    public function chiTietKhuyenMai($id)
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        $khuyenmai = KhuyenMai::where('MaKM', $id)->first();;
        $danhmucsanpham = DanhMucSanPham::all();
        $danhsachsanpham = SanPham::all(); // Lấy tất cả sản phẩm

        // Lấy chi tiết áp dụng khuyến mãi nếu loại khuyến mãi là 1 hoặc 2
        $chiTietApDung = null;
        if ($khuyenmai->MaLKM == 1 || $khuyenmai->MaLKM == 2) {
            $chiTietApDung = ChiTietApDung::where('MaKM', $khuyenmai->MaKM)->get();
        }
        // Lấy danh sách các sản phẩm đã chọn
        $sanphamDaChon = $chiTietApDung ? $chiTietApDung->pluck('MaSP')->toArray() : [];
        return view('admin.trangchu_admin.chi-tiet-khuyen-mai', [
            'title' => 'Chi Tiết Khuyến Mãi',
            'danhmucsanphams' => $danhmucsanpham,
            'khuyenmai' =>  $khuyenmai,
            'chiTietApDung' => $chiTietApDung,
            'danhsachsanpham' => $danhsachsanpham,
            'sanphamDaChon' => $sanphamDaChon,
        ]);
    }
    public function chinhSuaThongTinKhuyenMai(Request $request)
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        // Lấy thông tin khuyến mãi
        $khuyenmai = KhuyenMai::find($request->input('khuyenmai'));
        if (!$khuyenmai) {
            return redirect()->back()->with('error', 'Không tìm thấy khuyến mãi.');
        }
        // Xử lý dữ liệu từ request
        $data = $request->all();
        if ($khuyenmai->MaLKM == 1 || $khuyenmai->MaLKM == 2) {
            // Loại khuyến mãi áp dụng cho từng sản phẩm
            $ngaybatdau = $data['ngaybatdau'];
            $ngayketthuc = $data['ngayketthuc'];
            $giatrigiam = $data['giatrigiam'];
            $mota = $data['motakhuyenmai'];

            // Cập nhật thông tin khuyến mãi
            KhuyenMai::where('MaKM', $request->input('khuyenmai'))->update([
                'NgayBatDau' => $ngaybatdau,
                'NgayKetThuc' => $ngayketthuc,
                'GiaTriGiam' => $giatrigiam,
                'Mota' => $mota,
                'TrangThaiXoa' => 1,
                // Thêm các trường cập nhật khác tại đây nếu có
            ]);

            // Xóa chi tiết áp dụng cũ
            ChiTietApDung::where('MaKM', $khuyenmai->MaKM)->delete();

            // Thêm chi tiết áp dụng mới
            $sanpham_ids = $data['sanpham_ids'];
            $quantities = $data['quantities'];
            foreach ($sanpham_ids as $sanpham) {
                $chiTietapdung = new ChiTietApDung();
                $chiTietapdung->MaKM = $khuyenmai->MaKM;
                $chiTietapdung->MaSP = $sanpham;
                $chiTietapdung->SoLuong = $quantities[$sanpham] ?? 0; // Số lượng của sản phẩm
                $chiTietapdung->save();
            }
        } else {

            // Loại khuyến mãi áp dụng cho đơn hàng
            $giatridonhangtoithieu=$data['donhangtoithieu'];
            $ngaybatdau = $data['ngaybatdau'];
            $ngayketthuc = $data['ngayketthuc'];
            $giatrigiam = $data['giatrigiam'];
            $mota = $data['motakhuyenmai'];
            $sodiemdoiduoc = $data['sodiemdoiduoc'];
            $soluong = $data['soluong'];

            // Tìm và cập nhật thông tin khuyến mãi trực tiếp
            KhuyenMai::where('MaKM', $request->input('khuyenmai'))->update([
                'GiaTriDonHangToiThieu' => $giatridonhangtoithieu,
                'NgayBatDau' => $ngaybatdau,
                'NgayKetThuc' => $ngayketthuc,
                'GiaTriGiam' => $giatrigiam,
                'SoDiemDoiDuoc' => $sodiemdoiduoc,
                'SoLuong' => $soluong,
                'Mota' => $mota,
                'TrangThaiXoa' => 1,
                // Thêm các trường cập nhật khác tại đây nếu có
            ]);
        }
        return redirect()->route('khuyen-mai')->with('success', 'Thông tin khuyến mãi đã được cập nhật thành công!');
    }
    public function taoMoiKhuyenMai()
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        $danhsachsanpham = SanPham::all();
        $loaikhuyemai = LoaiKhuyenMai::all();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.tao-moi-khuyen-mai', [
            'title' => 'Tạo Khuyến Mãi',
            'danhmucsanphams' => $danhmucsanpham,
            'loaikhuyenmai' => $loaikhuyemai,
            'danhsachsanpham' => $danhsachsanpham,
        ]);
    }
    public function themKhuyenMai(Request $request)
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        // Lấy toàn bộ dữ liệu từ request
        $data = $request->all();
        $makhuyemai = $data['makhuyenmai'];
        $exists = KhuyenMai::where('MaKM', $makhuyemai)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['makhuyenmai' => 'Mã khuyến mãi đã tồn tại.'])->withInput();
        }

        $khuyenmai_id = $data['khuyenmai_id'];
        if ($khuyenmai_id == 1 || $khuyenmai_id == 2) {

            $ngaybatdau = $data['ngaybatdau'];
            $ngayketthuc = $data['ngayketthuc'];
            $giatrigiam = $data['giatrigiam'];
            $mota = $data['mota'];
            $sanpham_ids = $data['sanpham_ids'];
            $quantities = $data['quantities']; // Mảng số lượng từ request

            //Thêm Khuyến mãi
            $khuyenmai = new KhuyenMai();
            $khuyenmai->MaKM = $makhuyemai;
            $khuyenmai->Mota = $mota;
            $khuyenmai->NgayBatDau = $ngaybatdau;
            $khuyenmai->NgayKetThuc = $ngayketthuc;
            $khuyenmai->GiaTriGiam = $giatrigiam;
            $khuyenmai->MaLKM = $khuyenmai_id;
            $khuyenmai->TrangThaiXoa = 1;
            $khuyenmai->save();


            //Thêm chi tiết áp dụng
            $sanpham_ids = $data['sanpham_ids'];
            foreach ($sanpham_ids as $sanpham) {
                $chiTietapdung = new ChiTietApDung();
                $chiTietapdung->MaKM = $makhuyemai;
                $chiTietapdung->MaSP = $sanpham;
                $chiTietapdung->SoLuong = $quantities[$sanpham]; // Số lượng của sản phẩm
                $chiTietapdung->save();
            }
        } 
        elseif($khuyenmai_id == 3 || $khuyenmai_id == 4){

            $ngaybatdau = $data['ngaybatdau'];
            $ngayketthuc = $data['ngayketthuc'];
            $giatritoithieu = $data['giatritoithieu'];
            $giatrigiam = $data['giatrigiam'];
            $soluongapdung = $data['soluongapdung'];
            $mota = $data['mota'];

            //Thêm Khuyến mãi
            $khuyenmai = new KhuyenMai();
            $khuyenmai->MaKM = $makhuyemai;
            $khuyenmai->Mota = $mota;
            $khuyenmai->GiaTriDonHangToiThieu = $giatritoithieu;
            $khuyenmai->NgayBatDau = $ngaybatdau;
            $khuyenmai->NgayKetThuc = $ngayketthuc;
            $khuyenmai->GiaTriGiam = $giatrigiam;
            $khuyenmai->SoLuong = $soluongapdung;
            $khuyenmai->MaLKM = $khuyenmai_id;
            $khuyenmai->TrangThaiXoa = 1;
            $khuyenmai->save();
        }
        else {
            $ngaybatdau = $data['ngaybatdau'];
            $ngayketthuc = $data['ngayketthuc'];
            $giatritoithieu = $data['giatritoithieu'];
            $giatrigiam = $data['giatrigiam'];
            $diemdoiduoc = $data['diemdoiduoc'];
            $soluongapdung = $data['soluongapdung'];
            $mota = $data['mota'];

            //Thêm Khuyến mãi
            $khuyenmai = new KhuyenMai();
            $khuyenmai->MaKM = $makhuyemai;
            $khuyenmai->Mota = $mota;
            $khuyenmai->GiaTriDonHangToiThieu = $giatritoithieu;
            $khuyenmai->NgayBatDau = $ngaybatdau;
            $khuyenmai->NgayKetThuc = $ngayketthuc;
            $khuyenmai->GiaTriGiam = $giatrigiam;
            $khuyenmai->SoDiemDoiDuoc = $diemdoiduoc;
            $khuyenmai->SoLuong = $soluongapdung;
            $khuyenmai->MaLKM = $khuyenmai_id;
            $khuyenmai->TrangThaiXoa = 1;
            $khuyenmai->save();
        }

        return redirect()->route('khuyen-mai')->with('success', 'Khuyến mãi đã được thêm thành công!');
    }

    public function xoaKhuyenMai($id)
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('dangnhap');
        }
        $kt = NhanVien::where('Email', $user->Email)->first();
        if (!$kt) {
            return redirect()->route('trang-chu-dien-may');
        }
        
        // Tìm khách hàng theo ID
        // Cập nhật TrangThaiXoa thành 0
        KhuyenMai::where('MaKM', $id)->update(['TrangThaiXoa' => 0]);
       

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Xóa khuyến mãi thành công!');
    }

    public function voucherCaNhan()
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;

        // Lấy danh sách voucher của khách hàng
        $vouchers = ChiTietDoiDiem::where('MaKH', $maKH)->get();

        return view('trangchu.voucher-ca-nhan', [
            'title' => 'Voucher Cá Nhân',
            'danhmucsanphams' => $danhmucsanpham,
            'vouchers' => $vouchers,
        ]);
    }
    public function doiDiem()
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;
        // Lấy thông tin khách hàng
        $khachHang = KhachHang::find($maKH);
        if (!$khachHang) {
            return redirect()->back()->withErrors(['Khách hàng không tồn tại']);
        }

        // Lấy danh sách các mã khuyến mãi mà khách hàng đã có
        $maKMsDaCo = ChiTietDoiDiem::where('MaKH', $maKH)->pluck('MaKM');

        // Lấy danh sách khuyến mãi với MaLKM là 5 và 6 và đang hoạt động
        $danhsachkhuyenmai = KhuyenMai::whereIn('MaLKM', [5, 6])
            ->where('SoLuong', '>', 0)
            ->dangHoatDong()
            ->whereNotIn('MaKM', $maKMsDaCo)
            ->get();
        return view('trangchu.doi-diem-lay-voucher', [
            'title' => 'Đổi Voucher',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachkhuyenmais' => $danhsachkhuyenmai,
            'khachhang' => $khachHang,
        ]);
    }
    public function doiVoucher($id)
    {
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;

        // Tìm khuyến mãi dựa vào id
        $khuyenMai = KhuyenMai::find($id);
        if (!$khuyenMai) {
            return redirect()->back()->with('error', 'Khuyến mãi không tồn tại');
        }

        // Lấy thông tin khách hàng
        $khachHang = KhachHang::find($maKH);
        if (!$khachHang) {
            return redirect()->back()->with('error', 'Khách hàng không tồn tại');
        }

        // Kiểm tra điểm của khách hàng
        $soDiemHienCo = $khachHang->DiemTichLuy;
        if ($soDiemHienCo < $khuyenMai->SoDiemDoiDuoc) {
            return redirect()->back()->with('error', 'Điểm của bạn không đủ để đổi khuyến mãi này');
        }

        // Trừ số lượng voucher
        KhuyenMai::where('MaKM', $id)->where('SoLuong', '>', 0)->decrement('SoLuong', 1);

        // Trừ điểm của khuyến mãi từ điểm của khách hàng
        $khachHang->DiemTichLuy -= $khuyenMai->SoDiemDoiDuoc;
        $khachHang->save();

        // Thêm vào chi tiết đổi điểm
        $chitietdoidiem = new ChiTietDoiDiem();
        $chitietdoidiem->MaKM = $khuyenMai->MaKM;
        $chitietdoidiem->MaKH = $maKH;
        $chitietdoidiem->UuDaiDoiDuoc = $khuyenMai->Mota;
        $chitietdoidiem->save();

        return redirect()->route('voucher-ca-nhan')->with('success', 'Thành công đổi voucher!');
    }
}
