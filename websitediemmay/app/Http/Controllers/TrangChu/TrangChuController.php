<?php

namespace App\Http\Controllers\TrangChu;

use App\Http\Controllers\Controller;
use App\Models\BinhLuanDanhGia;
use App\Models\ChiTietApDung;
use App\Models\ChiTietHoaDon;
use App\Models\DanhMucSanPham;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\Kho;
use App\Models\KhuyenMai;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class TrangChuController extends Controller
{
    public function index(Request $request)
    {
        // $danhsachcuahang = Kho::all();
        // dd($danhsachcuahang);
        // Lấy tham số sắp xếp từ request
        $sapXep = $request->input('sapXep');
        $danhmucsanpham = DanhMucSanPham::all();
        $danhsachsanpham =  $danhsachsanpham = SanPham::where('TrangThaiXoa', 1);
        // Sắp xếp theo yêu cầu
        switch ($sapXep) {
            case 'gia_tien_tang':
                $danhsachsanpham->orderBy('GiaSP');
                break;
            case 'gia_tien_giam':
                $danhsachsanpham->orderBy('GiaSP', 'desc');
                break;
            case 'moi_nhat':
                $danhsachsanpham->orderBy('MaSP', 'desc');
                break;
            case 'cu_nhat':
                $danhsachsanpham->orderBy('MaSP');
                break;
            default:
                $danhsachsanpham->orderBy('MaSP', 'desc');
                break;
        }
        $danhsachsanphamkhilay = $danhsachsanpham->paginate(16); // Phân trang với mỗi trang chứa 16 sản phẩm

        foreach ($danhsachsanphamkhilay as $sanpham) {
            // Lấy khuyến mãi đang hoạt động cho sản phẩm
            $khuyenMai = KhuyenMai::dangHoatDong()
                ->whereHas('chiTietApDung', function ($query) use ($sanpham) {
                    $query->where('MaSP', $sanpham->MaSP);
                })->first();
            if ($khuyenMai) {
                // Nếu khuyến mãi là giảm giá phần trăm
                if ($khuyenMai->MaLKM == 1) {
                    $sanpham->GiaGiam = $sanpham->GiaSP - ($sanpham->GiaSP * $khuyenMai->GiaTriGiam / 100);
                    $sanpham->LGG = $khuyenMai->GiaTriGiam . '%';
                } elseif ($khuyenMai->MaLKM == 2) { // Nếu khuyến mãi là giảm giá số tiền cố định
                    $sanpham->GiaGiam = $sanpham->GiaSP - $khuyenMai->GiaTriGiam;
                    $sanpham->LGG = number_format($khuyenMai->GiaTriGiam, 0, ',', '.') . ' VNĐ';
                }
            } else {
                $sanpham->GiaGiam = null; // Không có khuyến mãi
                $sanpham->LGG = '';
            }
        }


        return view('trangchu.trang-chu-dien-may', [
            'title' => 'Trang Chủ',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachsanphams' => $danhsachsanphamkhilay,
        ]);
    }

    public function danhSachTheoLoai(Request $request, $loaiSanPham)
    {
        $danhmucsanpham = DanhMucSanPham::all();

        // Lấy thông tin của loại sản phẩm
        $loaiSanPhamInfo = LoaiSanPham::find($loaiSanPham);
        $moTa = $loaiSanPhamInfo->MoTa;
        // Lọc danh sách sản phẩm theo loại sản phẩm
        // Lấy tham số sắp xếp từ request
        $sapXep = $request->input('sapXep');

        $danhsachsanphamdalay = SanPham::where('MaLoaiSP', $loaiSanPham)
        ->where('TrangThaiXoa', 1);
        // Sắp xếp theo yêu cầu
        switch ($sapXep) {
            case 'gia_tien_tang':
                $danhsachsanphamdalay->orderBy('GiaSP');
                break;
            case 'gia_tien_giam':
                $danhsachsanphamdalay->orderBy('GiaSP', 'desc');
                break;
            case 'moi_nhat':
                $danhsachsanphamdalay->orderBy('MaSP', 'desc');
                break;
            case 'cu_nhat':
                $danhsachsanphamdalay->orderBy('MaSP');
                break;
            default:
                $danhsachsanphamdalay->orderBy('MaSP', 'desc');
                break;
        }
        // dd($danhsachsanphamdalay);
        // Phân trang với mỗi trang chứa 12 sản phẩm
        $danhsachsanphamdalay = $danhsachsanphamdalay->paginate(12);
        foreach ($danhsachsanphamdalay as $sanpham) {
            // Lấy khuyến mãi đang hoạt động cho sản phẩm
            $khuyenMai = KhuyenMai::dangHoatDong()
                ->whereHas('chiTietApDung', function ($query) use ($sanpham) {
                    $query->where('MaSP', $sanpham->MaSP);
                })->first();

            if ($khuyenMai) {
                // Nếu khuyến mãi là giảm giá phần trăm
                if ($khuyenMai->MaLKM == 1) {
                    $sanpham->GiaGiam = $sanpham->GiaSP - ($sanpham->GiaSP * $khuyenMai->GiaTriGiam / 100);
                    $sanpham->LGG = $khuyenMai->GiaTriGiam . '%';
                } elseif ($khuyenMai->MaLKM == 2) { // Nếu khuyến mãi là giảm giá số tiền cố định
                    $sanpham->GiaGiam = $sanpham->GiaSP - $khuyenMai->GiaTriGiam;
                    $sanpham->LGG = number_format($khuyenMai->GiaTriGiam, 0, ',', '.') . ' VNĐ';
                }
            } else {
                $sanpham->GiaGiam = null; // Không có khuyến mãi
                $sanpham->LGG = '';
            }
        }
        // Trả về view hiển thị danh sách sản phẩm
        return view('trangchu.trang-chu-dien-may', [
            'title' => 'Trang Chủ',
            'loaiSanPham' => $loaiSanPham, // Truyền biến $loaiSanPham vào view
            'moTa' => $moTa, // Truyền mô tả của loại sản phẩm vào view
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachsanphams' => $danhsachsanphamdalay,
        ]);
    }

    public function timKiem(Request $request)
    {
        $keyword = $request->input('timkiem');

        $danhmucsanpham = DanhMucSanPham::all();
        $sapXep = $request->input('sapXep');

        // Tìm kiếm sản phẩm dựa trên từ khóa
        $danhsachsanphamtimkiem = SanPham::where('TenSP', 'like', "%$keyword%")
                                 ->where('TrangThaiXoa', 1);
        // Sắp xếp theo yêu cầu
        switch ($sapXep) {
            case 'gia_tien_tang':
                $danhsachsanphamtimkiem->orderBy('GiaSP');
                break;
            case 'gia_tien_giam':
                $danhsachsanphamtimkiem->orderBy('GiaSP', 'desc');
                break;
            case 'moi_nhat':
                $danhsachsanphamtimkiem->orderBy('MaSP', 'desc');
                break;
            case 'cu_nhat':
                $danhsachsanphamtimkiem->orderBy('MaSP');
                break;
            default:
                $danhsachsanphamtimkiem->orderBy('MaSP', 'desc');
                break;
        }
        $danhsachsanphamtimkiem = $danhsachsanphamtimkiem->paginate(12);
        foreach ($danhsachsanphamtimkiem as $sanpham) {
            // Lấy khuyến mãi đang hoạt động cho sản phẩm
            $khuyenMai = KhuyenMai::dangHoatDong()
                ->whereHas('chiTietApDung', function ($query) use ($sanpham) {
                    $query->where('MaSP', $sanpham->MaSP);
                })->first();

            if ($khuyenMai) {
                // Nếu khuyến mãi là giảm giá phần trăm
                if ($khuyenMai->MaLKM == 1) {
                    $sanpham->GiaGiam = $sanpham->GiaSP - ($sanpham->GiaSP * $khuyenMai->GiaTriGiam / 100);
                    $sanpham->LGG = $khuyenMai->GiaTriGiam . '%';
                } elseif ($khuyenMai->MaLKM == 2) { // Nếu khuyến mãi là giảm giá số tiền cố định
                    $sanpham->GiaGiam = $sanpham->GiaSP - $khuyenMai->GiaTriGiam;
                    $sanpham->LGG = number_format($khuyenMai->GiaTriGiam, 0, ',', '.') . ' VNĐ';
                }
            } else {
                $sanpham->GiaGiam = null; // Không có khuyến mãi
                $sanpham->LGG = '';
            }
        }

        // Trả về view hiển thị kết quả tìm kiếm
        return view('trangchu.trang-chu-dien-may', [
            'title' => 'Kết Quả Tìm Kiếm',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachsanphams' => $danhsachsanphamtimkiem,
        ]);
    }
    public function thongKe()
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;

        // Lấy danh sách hóa đơn của khách hàng với trạng thái 'DaThanhToan'
        $hoaDons = HoaDon::where('MaKH', $maKH)
            ->where('MaTrangThaiHD', '4')
            ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
            ->get();
        return view('trangchu.thong-ke', [
            'title' => 'Thống Kê',
            'danhmucsanphams' => $danhmucsanpham,
            'hoaDons' => $hoaDons,
        ]);
    }
    public function thongTinCaNhan()
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;

        $khachHang = KhachHang::find($maKH);

        // Kiểm tra xem khách hàng có tồn tại hay không
        if (!$khachHang) {
            // Xử lý khi không tìm thấy khách hàng
            return redirect()->back()->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        return view('trangchu.thong-tin-ca-nhan', [
            'title' => 'Thông Tin Cá Nhân',
            'danhmucsanphams' => $danhmucsanpham,
            'khachHang' => $khachHang,
        ]);
    }
    public function donHangCaNhan(Request $request)
    {
        $sapXep = $request->input('sapXep'); 
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;

        // Sắp xếp theo yêu cầu
        switch ($sapXep) {
            case 'da_hoan_thanh':
                // Lấy danh sách hóa đơn của khách hàng với trạng thái giao hàng thành công
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->where('MaTrangThaiHD', '4')
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng thành công của bạn';
                break;
            case 'chua_duyet':
                // Lấy danh sách hóa đơn của khách hàng với trạng thái chưa duyệt
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->where('MaTrangThaiHD', '1')
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng chưa được duyệt của bạn';
                break;
            case 'da_duyet':
                // Lấy danh sách hóa đơn của khách hàng với trạng thái đã duyệt
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->where('MaTrangThaiHD', '2')
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng đã được duyệt của bạn';
                break;
            case 'dang_van_chuyen':
                // Lấy danh sách hóa đơn của khách hàng với trạng thái giao hàng đang vận chuyển
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->where('MaTrangThaiHD', '3')
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng đang của bạn';
                break;
            case 'giao_hang_that_bai':
                // Lấy danh sách hóa đơn của khách hàng với trạng thái giao hàng thất bại
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->where('MaTrangThaiHD', '5')
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng thất bại của bạn';
                break;
            default:
                // Lấy danh sách hóa đơn của khách hàng 
                $hoaDons = HoaDon::where('MaKH', $maKH)
                    ->orderBy('NgayLap', 'desc') // Sắp xếp ngược lại theo 'NgayLap'
                    ->get();
                $moTa = 'Đơn Hàng của bạn';
                break;
        }

        return view('trangchu.don-hang-ca-nhan', [
            'title' => 'Đơn Hàng Cá Nhân',
            'moTa' => $moTa, 
            'danhmucsanphams' => $danhmucsanpham,
            'hoaDons' => $hoaDons,
        ]);
    }
    public function chitietdonhang($id)
    {
        $danhmucsanpham = DanhMucSanPham::all();
        $hoadon = HoaDon::where('MaHD', $id)->first();
        $chiTiethoadon = ChiTietHoaDon::where('MaHD', $hoadon->MaHD)->with('SanPham')->get();
        return view('trangchu.chi-tiet-don-hang', [
            'hoadon' => $hoadon,
            'chitiethoadon' => $chiTiethoadon,
            'danhmucsanphams' => $danhmucsanpham,
            'title' => 'Chi tiết đơn hàng'
        ]);
    }
    public function themBinhLuan(Request $request)
    {
        $user = Session::get('user');
        $maKH = $user->MaKH;
        $data = $request->all();
        $quantities = $data['quantities']; // Mảng số lượng từ request

        //Thêm bình luận sản phẩm
        $sanpham_ids = $data['sanpham_ids'];
        foreach ($sanpham_ids as $sanpham) {
            $binhluan = new BinhLuanDanhGia();
            $binhluan->MaKH = $maKH;
            $binhluan->MaSP = $sanpham;
            $binhluan->BinhLuan = $quantities[$sanpham]; // Số lượng của sản phẩm
            $binhluan->NgayBinhLuan = now();
            $binhluan->save();
        }

        return redirect()->route('don-hang-ca-nhan')->with('success', 'Bình luận đã được thêm thành công!');
    }
    public function capNhatTaiKhoan(Request $request)
    {
        $user = Session::get('user');
        $maKH = $user->MaKH;
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            // 'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/',
            'sdt' => 'required|string|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email|unique:users',
            'hoten' => 'required|string|regex:/^[^\d!@#$%^&*]+$/',
            'phai' => 'required|integer|in:0,1',
            'diachi' => 'required|string',
        ]);

        if ($validator->fails()) {
            // Nếu có lỗi xác thực, hiển thị chúng
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Kiểm tra nếu email hoặc số điện thoại đã được sử dụng bởi tài khoản khác
        $existingUser = KhachHang::where(function ($query) use ($request, $maKH) {
            $query->where('Email', $request->input('email'))
                ->orWhere('SDT', $request->input('sdt'));
        })->where('MaKH', '!=', $maKH)->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email hoặc số điện thoại đã được sử dụng.');
        }


        // // Mã hóa mật khẩu
        // $hashedPassword = Hash::make($request->input('password'));  
        // Tìm khách hàng trong cơ sở dữ liệu
        $khachhang = KhachHang::find($maKH);

        if (!$khachhang) {
            // Nếu không tìm thấy khách hàng, hiển thị thông báo lỗi
            return redirect()->back()->with('error', 'Không tìm thấy khách hàng.');
        }

        $khachhang->SDT = $request->input('sdt');
        $khachhang->Email = $request->input('email');
        $khachhang->MatKhau = $request->input('password');
        // $user->Password = $hashedPassword;
        $khachhang->TenKH = $request->input('hoten');
        $khachhang->GioiTinh = $request->input('phai') == 0 ? "Nam" : "Nữ";
        $khachhang->DiaChi = $request->input('diachi');
        $khachhang->save();

        return redirect()->route('thong-tin-can-nhan')->with('success', 'Cập nhật thông tin thành công!');
    }
}
