<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\DanhMucSanPham;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KhachHangController extends Controller
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
        $Khachhangs = KhachHang::where('TrangThaiXoa', '1')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-khach-hang', [
            'title' => 'Quản Lý Khách Hàng',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachkhachhangs' => $Khachhangs,
        ]);
    }

    public function index_KHvohieuhoa()
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
        $Khachhangs = KhachHang::where('TrangThaiXoa', '0')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-khach-hang-vo-hieu-hoa', [
            'title' => 'Khách Hàng Vô Hiệu Hóa',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachkhachhangs' => $Khachhangs,
        ]);
    }

    public function timKiem(Request $request)
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

        $query = $request->input('query');

        $danhsachkhachhangs = KhachHang::where('TrangThaiXoa', 1)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('SDT', 'LIKE', "%{$query}%")
                    ->orWhere('Email', 'LIKE', "%{$query}%");
            })
            ->get();

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-khach-hang', [
            'title' => 'Quản Lý Khách Hàng',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachkhachhangs' => $danhsachkhachhangs,
        ]);
    }

    public function voHieu($id)
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
        $khachhang = KhachHang::findOrFail($id);

        // Cập nhật TrangThaiXoa thành 0
        $khachhang->TrangThaiXoa = 0;
        $khachhang->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Vô hiệu hóa thành công!');
    }
}
