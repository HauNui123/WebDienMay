<?php

namespace App\Http\Controllers\QL_DangNhap;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DangNhapController extends Controller
{
    public function index()
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        $user = Session::get('user');
        if ($user) {
            return redirect('/');
        }

        return view('ql_dangnhap.dangnhap', [
            'title' => 'Đăng Nhập Hệ Thống'
        ]);
    }

    public function dangnhap(Request $request)
    {
        $email_sdt = $request->email_sdt;
        $password = $request->password;

        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'email_sdt' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra xem đầu vào là email hay số điện thoại
        if (filter_var($email_sdt, FILTER_VALIDATE_EMAIL)) {

            if ($request->has('nhanvien')) {
                $user = NhanVien::where('Email', $email_sdt)->first();

                if ($user && $user->TrangThaiXoa == 0) {
                    return redirect()->back()->with('error', 'Rất tiếc! Tài khoản của bạn đã bị vô hiệu hóa');
                }elseif ($user && $email_sdt === $user->Email) {

                    Session::put('user', $user);
                    return redirect()->route('trang-chu-admin');
                } 
                else {
                    return redirect()->back()->with('error', 'Email hoặc mật khẩu nhân viên không chính xác');
                }
            } else {
                $user = KhachHang::where('Email', $email_sdt)->first();

                if ($user && $user->TrangThaiXoa == 0) {
                    return redirect()->back()->with('error', 'Rất tiếc! Tài khoản của bạn đã bị vô hiệu hóa');
                } elseif ($user && $email_sdt === $user->Email) {

                    Session::put('user', $user);
                    return redirect()->route('trang-chu-dien-may');
                } 
                else {
                    return redirect()->back()->with('error', 'Email hoặc mật khẩu không chính xác');
                }
            }
        } elseif (preg_match('/^\d{10,11}$/', $email_sdt)) {

            if ($request->has('nhanvien')) {
                $user = NhanVien::where('SDT', $email_sdt)->first();
                // dd($user->all());

                if ( $user && $user->TrangThaiXoa == 0) {
                    return redirect()->back()->with('error', 'Rất tiếc! Tài khoản của bạn đã bị vô hiệu hóa');
                } elseif ($user && $password === $user->MatKhau) {
                    Session::put('user', $user);
                    return redirect()->route('trang-chu-admin');
                } 
                else {
                    return redirect()->back()->with('error', 'Số điện thoại hoặc mật khẩu không chính xác');
                }
            } else {
                // $user = DB::connection('sqlsrv')->table('KhachHang')->where('SDT', $email_sdt)->first();
                $user = KhachHang::where('SDT', $email_sdt)->first();
                // dd($user->all());

                if ($user && $user->TrangThaiXoa == 0) {
                    return redirect()->back()->with('error', 'Rất tiếc! Tài khoản của bạn đã bị vô hiệu hóa');
                } elseif ($user && $password === $user->MatKhau) {
                    Session::put('user', $user);
                    return redirect()->route('trang-chu-dien-may');
                } 
                else {
                    return redirect()->back()->with('error', 'Số điện thoại hoặc mật khẩu không chính xác');
                }
            }
        } else {
            // Nếu không phù hợp với cả email và số điện thoại
            return redirect()->back()
                ->withErrors(['email_sdt' => 'Email hoặc số điện thoại không hợp lệ'])
                ->withInput();
        }
    }
    public function dangxuat()
    {
        // Xóa thông tin người dùng khỏi session
        Session::forget('user');

        // Hoặc sử dụng Session::flush() để xóa tất cả dữ liệu trong session
        // Session::flush();

        // Chuyển hướng hoặc thực hiện các hành động khác sau khi đăng xuất
        return redirect()->route('trang-chu-dien-may');
    }
    public function indexquenmatkhau()
    {
        return view('ql_dangnhap.quen-mat-khau', [
            'title' => 'Quên Mật Khẩu'
        ]);
    }
    public function forgotpassword(Request $request)
    {
        $sdt = $request->sdt;
        $email = $request->email;
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'sdt' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = KhachHang::where('SDT', $sdt)->first();


        if ($user && $user->Email == $email) {
            // Tạo token đặt lại mật khẩu
            $token = Str::random(64);

            // Lưu token vào cơ sở dữ liệu
            $user->Token = $token;
            $user->TimeReset = now()->addMinutes(2);
            $user->save();

            Mail::to($user->Email)->send(new ResetPasswordMail($token, $email));
            return redirect()->back()->with('success', 'Email đã được gửi. Vui lòng kiểm tra hộp thư để đặt lại mật khẩu. Bạn chỉ có 2 phút');
        } else {
            return redirect()->back()->with('error', 'Tên đăng nhập hoặc Email không chính xác');
        }
    }
    public function indexresetpassword($token)
    {

        $user = KhachHang::where('Token', $token)->first();

        // Kiểm tra xem người dùng có tồn tại và thời gian reset còn hợp lệ không
        if (!$user) {
            return response('Liên kết đặt lại mật khẩu đã hết hạn hoặc không hợp lệ.', 404);
        } else if ($user && $user->TimeReset > now()) {
            return view('ql_dangnhap.reset-password', ['token' => $token]);
        } else {
            $user->Token = null;
            $user->TimeReset = null;
            $user->save();
            return response('Liên kết đặt lại mật khẩu đã hết hạn hoặc không hợp lệ.', 404);
        }
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'matkhau' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/',
            // 'nhaplai' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/',
        ]);

        if ($validator->fails()) {
            // Nếu có lỗi xác thực, hiển thị chúng
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $password = $request->matkhau;
        $confirmPassword = $request->nhaplai;
        $email = urldecode($request->query('email'));

        if ($password === $confirmPassword) {
            // Cập nhật mật khẩu mới
            $user = KhachHang::where('Email', $email)->first();
            $user->MatKhau = $password;
            $user->Token = null;
            $user->TimeReset = null; // Có thể cần xóa thời gian reset
            $user->save();

            // Redirect hoặc thông báo thành công
            return redirect('ql_dangnhap/dangnhap');
        } else {
            return redirect()->back()->with('error', 'Mật khẩu và xác nhận mật khẩu không khớp.');
        }
    }
    public function index_register()
    {
        return view('ql_dangnhap.tao-tai-khoan-khach-hang', [
            'title' => 'Đăng Ký'
        ]);
    }

    public function register(Request $request)
    {
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

        // Kiểm tra nếu email hoặc số điện thoại đã được sử dụng
        $existingUser = KhachHang::where('Email', $request->input('email'))
            ->orWhere('SDT', $request->input('sdt'))
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email hoặc số điện thoại đã được sử dụng.');
        }

        // // Mã hóa mật khẩu
        // $hashedPassword = Hash::make($request->input('password'));  

        // Tạo bản ghi mới trong bảng người dùng
        $user = new KhachHang();
        $user->SDT = $request->input('sdt');
        $user->Email = $request->input('email');
        $user->MatKhau = $request->input('password');
        // $user->Password = $hashedPassword;
        $user->TenKH = $request->input('hoten');
        $user->GioiTinh = $request->input('phai') == 0 ? "Nam" : "Nữ";
        $user->DiaChi = $request->input('diachi');
        $user->TrangThaiXoa = 1;
        $user->DiemTichLuy = 0;

        $user->save();

        return redirect()->route('dangnhap');
    }
}
