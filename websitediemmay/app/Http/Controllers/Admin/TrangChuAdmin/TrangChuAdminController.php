<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\BinhLuanDanhGia;
use App\Models\DanhMucSanPham;
use App\Models\KhuyenMai;
use App\Models\LoaiSanPham;
use App\Models\NhanVien;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TrangChuAdminController extends Controller
{
    public function index(Request $request)
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

        $sapXep = $request->input('sapXep');
        $danhsachsanpham = SanPham::where('TrangThaiXoa', 1);
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

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.trang-chu-admin', [
            'title' => 'Trang Chủ Admin',
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
        return view('admin.trangchu_admin.trang-chu-admin', [
            'title' => 'Trang Chủ Admin',
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

        // Tìm kiếm sản phẩm dựa trên từ khóa
        $danhsachsanphamtimkiem = SanPham::where('TenSP', 'like', "%$keyword%")
                                 ->where('TrangThaiXoa', 1)
                                 ->paginate(20);

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
        return view('admin.trangchu_admin.trang-chu-admin', [
            'title' => 'Kết Quả Tìm Kiếm',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachsanphams' => $danhsachsanphamtimkiem,
        ]);
    }
    public function chiTietSanPham($id)
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
        $danhmucsanpham = DanhMucSanPham::all();
        $sanpham = SanPham::where('MaSP', $id)->first();
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
        $danhsachbinhluan = BinhLuanDanhGia::where('MaSP', $id)->get();
        return view('admin.trangchu_admin.chi-tiet-san-pham-admin', [
            'sanpham' => $sanpham,
            'danhmucsanphams' => $danhmucsanpham,
            'binhluans' => $danhsachbinhluan,
            'title' => 'Chi tiết sản phẩm'
        ]);
    }
    public function xoaBinhLuan($MaSP, $MaKH)
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
        // Tìm bình luận đánh giá của khách hàng cho sản phẩm cụ thể
        $binhLuan = BinhLuanDanhGia::where('MaSP', $MaSP)->where('MaKH', $MaKH)->first();

        // Kiểm tra xem bình luận có tồn tại không
        if ($binhLuan) {
            BinhLuanDanhGia::where('MaSP', $MaSP)
                ->where('MaKH', $MaKH)
                ->delete(); // Xóa bình luận
            return redirect()->back()->with('success', 'Bình luận đã được xóa thành công!');
        } else {
            return redirect()->back()->with('error', 'Bình luận không tồn tại!');
        }
    }
}
