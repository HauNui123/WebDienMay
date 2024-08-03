<?php

namespace App\Http\Controllers\TrangChu;

use App\Http\Controllers\Controller;
use App\Models\BinhLuanDanhGia;
use App\Models\DanhMucSanPham;
use App\Models\KhuyenMai;
use App\Models\NhanVien;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SanPhamController extends Controller
{
    public function index($id)
    {
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
        return view('trangchu.chi-tiet-san-pham', [
            'sanpham' => $sanpham,
            'danhmucsanphams' => $danhmucsanpham,
            'binhluans' => $danhsachbinhluan,
            'title' => 'Chi tiết sản phẩm'
        ]);
    }
    public function xoaSanPham($id)
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

        // Tìm sản phẩm theo ID
        $sanpham = SanPham::findOrFail($id);

        // Cập nhật TrangThaiXoa thành 0
        $sanpham->TrangThaiXoa = 0;
        $sanpham->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->route('trang-chu-admin')->with('success', 'Xóa sản phẩm thành công!');
    }
    
    public function capnhatSanPham(Request $request)
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
        $sanpham = SanPham::where('MaSP', $request->input('sanpham'))->first();
        $sanpham->TenSP = $request->input('tensanpham');
        $sanpham->SoLuong = $request->input('soluong');
        $sanpham->NgaySX = Carbon::parse($request->ngaysanxuat)->format('Y-m-d');
        $sanpham->MoTa = $request->mota;
        $sanpham->save();
        
        return redirect()->back()->with('success', 'Thêm nhân viên thành công!');
    }
    public function index_capnhatSanPham($id)
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
        return view('admin.trangchu_admin.cap-nhat-san-pham', [
            'sanpham' => $sanpham,
            'danhmucsanphams' => $danhmucsanpham,
            'title' => 'Cập nhật sản phẩm'
        ]);
    }
}
