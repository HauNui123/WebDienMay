<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\CauHinhTichDiem;
use App\Models\DanhMucSanPham;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TichDiemController extends Controller
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
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy danh sách tất cả các cấu hình tích điểm
        $cauHinhTichDiems = CauHinhTichDiem::all();
        return view('admin.trangchu_admin.tich-diem', [
            'cauhinhtichdiems' => $cauHinhTichDiems,
            'danhmucsanphams' => $danhmucsanpham,
            'title' => 'Tích Điểm'
        ]);
    }
    public function capNhatTrangThai(Request $request)
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
        // Lấy danh sách các cấu hình tích điểm
        $cauHinhTichDiems = CauHinhTichDiem::all();

        // Cập nhật trạng thái
        foreach ($cauHinhTichDiems as $cauHinh) {
            $cauHinh->TrangThaiApDung = ($cauHinh->MaTichDiem == $request->matichdiem) ? 1 : 0;
            $cauHinh->save();
        }

        // Redirect hoặc trả về view tùy vào logic của ứng dụng của bạn
        return redirect()->route('tich-diem')->with('success', 'Cập nhật thành công!!!.');
    }
    public function themCauHinh(Request $request)
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
        // Kiểm tra xem có cấu hình tích điểm trùng tỷ lệ số tiền và số điểm không
        $tienTichMoi = $request->sotien;
        $diemTichMoi = $request->sodiem;
        $tiLeMoi = $tienTichMoi / $diemTichMoi;

        $trungTiLe = CauHinhTichDiem::all()->filter(function ($cauHinh) use ($tiLeMoi) {
            $tiLeHienTai = $cauHinh->SoTienTich / $cauHinh->SoDiemTich;
            return $tiLeMoi == $tiLeHienTai;
        })->isNotEmpty();

        // Nếu có cấu hình trùng tỷ lệ, thông báo lỗi
        if ($trungTiLe) {
            return redirect()->back()->with('error', 'Đã tồn tại cấu hình tích điểm hoặc tỷ lệ số tiền và số điểm tương tự với một cấu hình khác!');
        }


        // Tạo mới cấu hình tích điểm
        $cauHinhTichDiem = new CauHinhTichDiem();
        $cauHinhTichDiem->SoTienTich = $request->sotien;
        $cauHinhTichDiem->SoDiemTich = $request->sodiem;
        $cauHinhTichDiem->TrangThaiApDung = 0; // Mặc định trạng thái là 0
        $cauHinhTichDiem->save();

        // Redirect về trang cần thiết
        return redirect()->back()->with('success', 'Thêm cấu hình tích điểm thành công!');
    }
    public function xoaCauHinhTichDiem($matichdiem)
    {
        // Kiểm tra cấu hình tích điểm có tồn tại không
        $cauHinh = CauHinhTichDiem::find($matichdiem);

        if (!$cauHinh) {
            return redirect()->back()->with('error', 'Không tìm thấy cấu hình tích điểm này!');
        }

        // Kiểm tra nếu cấu hình đang được áp dụng (TrangThaiApDung = 1) thì không cho phép xóa
        if ($cauHinh->TrangThaiApDung == 1) {
            return redirect()->back()->with('error', 'Cấu hình này đang được áp dụng, không thể xóa!');
        }

        // Xóa cấu hình tích điểm
        $cauHinh->delete();

        return redirect()->back()->with('success', 'Xóa cấu hình tích điểm thành công!');
    }
}
