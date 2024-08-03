<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\DanhMucSanPham;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NhanVienController extends Controller
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
        $Nhanviens = NhanVien::where('TrangThaiXoa', '1')
            ->whereIn('MaCV', [4, 5])
            ->where('MaNV', '!=', $kt->MaNV)
            ->get();

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-nhan-vien', [
            'title' => 'Quản Lý Nhân Viên',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachnhanviens' => $Nhanviens,
        ]);
    }

    public function index_NVvohieuhoa()
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
        $Nhanviens = NhanVien::where('TrangThaiXoa', '0')
            ->whereIn('MaCV', [4, 5])
            ->where('MaNV', '!=', $kt->MaNV)
            ->get();

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-nhan-vien-vo-hieu-hoa', [
            'title' => 'Nhân Viên Vô Hiệu Hóa',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachnhanviens' => $Nhanviens,
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

        $danhsachnhanviens = NhanVien::where('TrangThaiXoa', 1)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('SDT', 'LIKE', "%{$query}%")
                    ->orWhere('Email', 'LIKE', "%{$query}%");
            })
            ->whereIn('MaCV', [4, 5])
            ->where('MaNV', '!=', $kt->MaNV)
            ->get();

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-nhan-vien', [
            'title' => 'Quản Lý Nhân Viên',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachnhanviens' => $danhsachnhanviens,
        ]);
    }

    public function timKiem_NVvohieuhoa(Request $request)
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

        $danhsachnhanviens = NhanVien::where('TrangThaiXoa', 0)
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('SDT', 'LIKE', "%{$query}%")
                    ->orWhere('Email', 'LIKE', "%{$query}%");
            })
            ->whereIn('MaCV', [4, 5])
            ->where('MaNV', '!=', $kt->MaNV)
            ->get();

        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.danh-sach-nhan-vien-vo-hieu-hoa', [
            'title' => 'Nhân Viên Vô Hiệu Hóa',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachnhanviens' => $danhsachnhanviens,
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
        $nhanvien = NhanVien::findOrFail($id);

        // Cập nhật TrangThaiXoa thành 0
        $nhanvien->TrangThaiXoa = 0;
        $nhanvien->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Vô hiệu hóa thành công!');
    }
    public function kichHoat($id)
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
        $nhanvien = NhanVien::findOrFail($id);

        // Cập nhật TrangThaiXoa thành 1
        $nhanvien->TrangThaiXoa = 1;
        $nhanvien->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Tái kích hoạt thành công!');
    }
    public function themNhanVien(Request $request)
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


        // Tạo bản ghi nhân viên mới
        $nhanVien = new NhanVien;
        $nhanVien->MaCV = $request->input('loainhanvien');
        $nhanVien->TenNV = $request->input('tennhanvien');
        $nhanVien->GioiTinh = $request->input('phai') == 0 ? "Nam" : "Nữ";
        $nhanVien->CCCD = $request->input('cccd');
        $nhanVien->DiaChi = $request->input('diachi');
        $nhanVien->SDT = $request->input('sdt');
        $nhanVien->Email = $request->input('email');
        $nhanVien->MatKhau = ($request->input('matkhau'));
        $nhanVien->TrangThaiXoa = 1;
        $nhanVien->save();

        // Redirect về trang cần thiết
        return redirect()->back()->with('success', 'Thêm nhân viên thành công!');
    }

    public function thongTinCaNhan()
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
        // Lấy thông tin người dùng từ session
        $maNV = $user->MaNV;

        $nhanvien = NhanVien::find($maNV);

        // Kiểm tra xem khách hàng có tồn tại hay không
        if (!$nhanvien) {
            // Xử lý khi không tìm thấy khách hàng
            return redirect()->back()->with('error', 'Không tìm thấy thông tin nhân viên.');
        }

        return view('admin.trangchu_admin.thong-tin-nhan-vien', [
            'title' => 'Thông Tin Nhân Viên',
            'danhmucsanphams' => $danhmucsanpham,
            'NhanVien' => $nhanvien,
        ]);
    }
    public function capNhatTaiKhoan(Request $request)
    {
        $user = Session::get('user');
        $maNV = $user->MaNV;
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            // 'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/',
            'sdt' => 'required|string|regex:/^[0-9]{10,11}$/',
            'email' => 'required|email|unique:users',
            'hoten' => 'required|string|regex:/^[^\d!@#$%^&*]+$/',
            'phai' => 'required|integer|in:0,1',
            'diachi' => 'required|string',
            'cccd' => 'required|regex:/^[0-9]{12}$/',
        ]);

        if ($validator->fails()) {
            // Nếu có lỗi xác thực, hiển thị chúng
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Kiểm tra nếu email hoặc số điện thoại đã được sử dụng bởi tài khoản khác
        $existingUser = NhanVien::where(function ($query) use ($request, $maNV) {
            $query->where('Email', $request->input('email'))
                ->orWhere('SDT', $request->input('sdt'));
        })->where('MaNV', '!=', $maNV)->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email hoặc số điện thoại đã được sử dụng.');
        }


        // // Mã hóa mật khẩu
        // $hashedPassword = Hash::make($request->input('password'));  
        // Tìm khách hàng trong cơ sở dữ liệu
        $nhanvien = NhanVien::find($maNV);

        if (!$nhanvien) {
            // Nếu không tìm thấy khách hàng, hiển thị thông báo lỗi
            return redirect()->back()->with('error', 'Không tìm thấy nhân viên.');
        }

        $nhanvien->SDT = $request->input('sdt');
        $nhanvien->Email = $request->input('email');
        $nhanvien->MatKhau = $request->input('password');
        // $user->Password = $hashedPassword;
        $nhanvien->TenNV = $request->input('hoten');
        $nhanvien->GioiTinh = $request->input('phai') == 0 ? "Nam" : "Nữ";
        $nhanvien->DiaChi = $request->input('diachi');
        $nhanvien->CCCD = $request->input('cccd');
        $nhanvien->save();

        return redirect()->route('thong-tin-nhan-vien')->with('success', 'Cập nhật thông tin thành công!');
    }
}
