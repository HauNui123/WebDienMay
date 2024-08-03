<?php

namespace App\Http\Controllers\QL_GioHang;

use App\Http\Controllers\Controller;
use App\Models\ChiTietGioHang;
use App\Models\DanhMucSanPham;
use App\Models\GioHang;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\NhanVien;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GioHangController extends Controller
{
    public function index_GioHang(Request $request)
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');

        // Kiểm tra xem user là loại KhachHang hay NhanVien
        if ($user instanceof KhachHang) {
            $user_id = $user->MaKH;
        } elseif ($user instanceof NhanVien) {
            $user_id = $user->MaNV;
        }

        // Lấy thông tin giỏ hàng của người dùng từ bảng Giỏ Hàng
        $gioHang = GioHang::where('MaKH', $user_id)->first();

        // Nếu không tìm thấy giỏ hàng của người dùng, có thể xử lý tùy thuộc vào yêu cầu của bạn
        if (!$gioHang) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn hiện đang trống');
        }


        // Lấy danh sách sản phẩm trong giỏ hàng thông qua bảng Chi Tiết Giỏ Hàng
        $chiTietGioHangs = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with('SanPham')->get();

        // Tính toán giá giảm và cập nhật đối tượng sản phẩm
        foreach ($chiTietGioHangs as $chiTietGioHang) {
            $sanpham = $chiTietGioHang->SanPham;

            $khuyenMai = KhuyenMai::dangHoatDong()
                ->whereHas('chiTietApDung', function ($query) use ($sanpham) {
                    $query->where('MaSP', $sanpham->MaSP);
                })->first();

            if ($khuyenMai) {
                // Nếu khuyến mãi là giảm giá phần trăm
                if ($khuyenMai->MaLKM == 1) {
                    $sanpham->GiaGiam = $sanpham->GiaSP - ($sanpham->GiaSP * $khuyenMai->GiaTriGiam / 100);
                } elseif ($khuyenMai->MaLKM == 2) { // Nếu khuyến mãi là giảm giá số tiền cố định
                    $sanpham->GiaGiam = $sanpham->GiaSP - $khuyenMai->GiaTriGiam;
                }
            } else {
                $sanpham->GiaGiam = $sanpham->GiaSP; // Không có khuyến mãi
            }
        }
        return view('giohang.gio-hang-cua-ban', [
            'title' => 'Giỏ Hàng',
            'danhmucsanphams' => $danhmucsanpham,
            'chiTietGioHangs' => $chiTietGioHangs,
        ]);
    }

    public function themVaoGioHang(Request $request)
    {
        // Lấy ID sản phẩm từ request POST
        $productId = $request->input('productId');

        // Lấy thông tin người dùng từ session
        $user = Session::get('user');

        if (!$user) {
            // Nếu không có người dùng, chuyển hướng đến trang đăng nhập
            return redirect()->route('dangnhap');
        }

        // Kiểm tra xem user là loại KhachHang hay NhanVien
        if ($user instanceof KhachHang) {
            $user_id = $user->MaKH;
        } elseif ($user instanceof NhanVien) {
            $user_id = $user->MaNV;
        }

        // Tìm giỏ hàng của người dùng
        $gioHang = GioHang::where('MaKH', $user_id)->first();

        // Nếu không tìm thấy giỏ hàng của người dùng, có thể xử lý tùy thuộc vào yêu cầu của bạn
        if (!$gioHang) {
            $gioHang = $this->taoGioHang($user_id);
        }

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
        $chiTietGioHang = ChiTietGioHang::where('MaGH', $gioHang->MaGH)
            ->where('MaSP', $productId)
            ->first();
        // dd($chiTietGioHang);
        if ($chiTietGioHang) {
            // Nếu sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng

            // Lấy sản phẩm từ cơ sở dữ liệu
            $sanpham = SanPham::find($productId);

            // Kiểm tra số lượng sản phẩm hiện có
            if ($chiTietGioHang->SoLuong+1 > $sanpham->SoLuong) {
                return Redirect::back()->with('error', 'Số lượng sản phẩm vượt quá số lượng hiện có.');
            }

            ChiTietGioHang::where('MaGH', $chiTietGioHang->MaGH)
                ->where('MaSP', $chiTietGioHang->MaSP)
                ->increment('SoLuong', 1);
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới vào giỏ hàng
            $newChiTietGioHang = new ChiTietGioHang();
            $newChiTietGioHang->MaGH = $gioHang->MaGH;
            $newChiTietGioHang->MaSP = $productId;
            $newChiTietGioHang->SoLuong = 1;
            $newChiTietGioHang->save();
        }

        // Redirect hoặc trả về view tùy thuộc vào yêu cầu của bạn
        return Redirect::back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function taoGioHang($user_id)
    {

        $gioHangMoi = new GioHang();
        $gioHangMoi->MaKH = $user_id;
        // Lưu giỏ hàng mới vào cơ sở dữ liệu
        $gioHangMoi->save();

        // Trả về giỏ hàng mới được tạo
        return $gioHangMoi;
    }

    public function XoaKhoiGioHang($MaGH, $MaSP)
    {
        // Tìm chi tiết giỏ hàng cần xóa dựa trên MaGH và MaSP
        $chiTietGioHang = ChiTietGioHang::where('MaGH', $MaGH)
            ->where('MaSP', $MaSP)
            ->first();

        if ($chiTietGioHang) {
            ChiTietGioHang::where('MaGH', $chiTietGioHang->MaGH)
                ->where('MaSP', $chiTietGioHang->MaSP)
                ->delete();
        }
        return Redirect::back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }

    public function CapNhatSoLuong(Request $request)
    {
        // Lấy dữ liệu từ request
        $maGH = $request->input('MaGH');
        $maSP = $request->input('MaSP');
        $soLuong = $request->input('SoLuong');

        // Lấy sản phẩm từ cơ sở dữ liệu
        $sanpham = SanPham::find($maSP);

        // Kiểm tra số lượng sản phẩm hiện có
        if ($soLuong > $sanpham->SoLuong) {
            return Redirect::back()->with('error', 'Số lượng sản phẩm vượt quá số lượng hiện có.');
        }

        ChiTietGioHang::where('MaGH', $maGH)
            ->where('MaSP', $maSP)
            ->update(['SoLuong' => $soLuong]);

        return Redirect::back();
    }
}
