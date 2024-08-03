<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoaDon;
use App\Models\ChiTietKho;
use App\Models\ChiTietPhieuKho;
use App\Models\DanhMucSanPham;
use App\Models\HoaDon;
use App\Models\Kho;
use App\Models\NhanVien;
use App\Models\PhieuKho;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DonHangController extends Controller
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
        $hoaDons = HoaDon::where('MaTrangThaiHD', '1')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.don-hang-can-duyet', [
            'title' => 'Đơn Hàng Cần Duyệt',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachhoadon' => $hoaDons,
        ]);
    }
    public function chitiethoadon($id)
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
        $hoadon = HoaDon::where('MaHD', $id)->first();
        $chiTiethoadon = ChiTietHoaDon::where('MaHD', $hoadon->MaHD)->with('SanPham')->get();
        return view('admin.trangchu_admin.chi-tiet-hoa-don', [
            'hoadon' => $hoadon,
            'chitiethoadon' => $chiTiethoadon,
            'danhmucsanphams' => $danhmucsanpham,
            'title' => 'Chi tiết hóa đơn'
        ]);
    }
    public function chinhSuaThongTinDonHang(Request $request)
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
        // Lấy dữ liệu từ request
        $diaChi = $request->input('diaChiGiaoHang');
        $pattern = "/^[^,]+,\s*[^,]+,\s*[^,]+,\s*[^,]+\s*$/";
        if (!preg_match($pattern, $diaChi)) {
            return redirect()->back()->withErrors(['diaChiGiaoHang' => 'Địa chỉ giao hàng không hợp lệ (ví dụ: đường phố, phường, quận, thành phố).']);
        }
        $ghichu = $request->input('ghiChu');
        $mahd = $request->input('hoadon');

        $hoadon = HoaDon::where('MaHD', $mahd)->first();
        $hoadon->DiaChiGiaoHang = $diaChi;
        $hoadon->GhiChu = $ghichu;
        $hoadon->save();

        return Redirect::back();
    }
    public function duyetDonHang(Request $request)
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
        $mahd = $request->input('mahoadon');
        $hoadon = HoaDon::where('MaHD', $mahd)->first();
        $hoadon->MaTrangThaiHD = "2";
        $hoadon->MaNV = $user->MaNV;
        $hoadon->save();
        return redirect()->route('duyet-don')->with('success', 'Đơn hàng đã được duyệt thành công!');
    }

    public function xuatKhoDonHang()
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
        $hoaDons = HoaDon::where('MaTrangThaiHD', '2')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.xuat-kho-don-hang', [
            'title' => 'Xuất Kho Đơn Hàng',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachhoadon' => $hoaDons,
        ]);
    }

    public function xuatKho(Request $request)
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
        $hoadonIds = $request->input('hoadon_ids');

        if ($hoadonIds) {
            // Xử lý logic xuất kho cho các hóa đơn được chọn
            foreach ($hoadonIds as $id) {
                // Ví dụ: tìm hóa đơn và cập nhật trạng thái
                $hoadon = HoaDon::find($id);
                if ($hoadon) {
                    // Cập nhật trạng thái xuất kho hoặc thực hiện các hành động cần thiết
                    // $hoadon->MaTrangThaiHD = '3';
                    // $hoadon->save();

                    $danhsachcuahang = Kho::all();
                    $khoGanNhat = null;
                    $khoangCachGanNhat = PHP_INT_MAX;

                    // Sử dụng OpenStreetMap và Nominatim để tính khoảng cách
                    foreach ($danhsachcuahang as $kho) {
                        $khoangCach = $this->tinhKhoangCach($hoadon->DiaChiGiaoHang, $kho->MaKho); // Implement this function
                        if ($khoangCach < $khoangCachGanNhat) {
                            $khoangCachGanNhat = $khoangCach;
                            $khoGanNhat = $kho;
                        }
                    }

                    //Lập phiếu xuất kho
                    $phieukho = new PhieuKho();
                    $phieukho->MaLPK = "2";
                    $phieukho->Mota = "Xuất hàng hóa đơn" . (string) $hoadon->MaHD;
                    $phieukho->NgayNhapXuatKho = now();
                    $phieukho->TongTien = $hoadon->TongTien;
                    $phieukho->MaNV = "1";
                    $phieukho->MaKho = $khoGanNhat->MaKho;
                    $phieukho->save();

                    // Lưu chi tiết hóa đơn vào bảng ChiTietPhieuKho
                    $chiTietHoaDons = ChiTietHoaDon::where('MaHD', $hoadon->MaHD)->get();
                    foreach ($chiTietHoaDons as $chiTiet) {
                        $chiTietPhieuKho = new ChiTietPhieuKho();
                        $chiTietPhieuKho->MaPK = $phieukho->MaPK; // Sử dụng khóa chính của phiếu kho
                        $chiTietPhieuKho->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                        $chiTietPhieuKho->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                        $chiTietPhieuKho->DonGia = $chiTiet->DonGia; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                        $chiTietPhieuKho->ThanhTien = $chiTiet->ThanhTien; // Tính toán thành tiền
                        // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
                        $chiTietPhieuKho->save();
                    }
                    // cập nhật hóa đơn
                    $hoadon->MaTrangThaiHD = '3';
                    $hoadon->MaKho = $khoGanNhat->MaKho;
                    $hoadon->save();

                    // kho trừ số lượng
                    foreach ($chiTietHoaDons as $chiTiet) {
                        // Lấy số lượng sản phẩm cần trừ
                        $soLuongCanTru = $chiTiet->SoLuong;
                        ChiTietKho::where('MaKho', $khoGanNhat->MaKho)
                            ->where('MaSP', $chiTiet->MaSP)
                            ->decrement('SoLuongTrongKho', $soLuongCanTru);
                        // Trừ số lượng trong bảng SanPham
                        SanPham::where('MaSP', $chiTiet->MaSP)
                            ->decrement('SoLuong', $soLuongCanTru);
                    }
                }
            }
            return redirect()->back()->with('success', 'Xuất kho thành công!');
        } else {
            return redirect()->back()->with('error', 'Chưa chọn hóa đơn nào để xuất kho.');
        }
    }

    private function tinhKhoangCach($diaChiGiaoHang, $makho)
    {
        // Lấy tọa độ của địa chỉ giao hàng từ OpenStreetMap
        $coordsGiaoHang = $this->getCoordinates($diaChiGiaoHang);

        // Tìm kho gần nhất dựa trên mã kho
        $khoGanNhat = Kho::select('Lat', 'Lon')
            ->where('MaKho', $makho)
            ->first();

        if ($coordsGiaoHang && $khoGanNhat) {
            // Sử dụng tọa độ của kho từ cơ sở dữ liệu
            $coordsKho = [
                'lat' => $khoGanNhat->Lat,
                'lon' => $khoGanNhat->Lon,
            ];

            // Tính khoảng cách bằng công thức Haversine
            return $this->haversineDistance($coordsGiaoHang, $coordsKho);
        } else {
            return null;
        }
    }
    // Lấy tọa độ địa lý từ địa chỉ
    private function getCoordinates($address)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&addressdetails=1&limit=1";

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if (!empty($data)) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            } else {
                throw new \Exception("Không tìm thấy tọa độ cho địa chỉ: " . $address);
            }
        } catch (\Exception $e) {
            // Handle exceptions or API errors
            return null;
        }
    }

    //Tính khoảng cách bằng công thức Haversine
    private function haversineDistance($coordsGiaoHang, $coordsKho)
    {
        // Convert độ sang radian
        $lat1 = deg2rad($coordsGiaoHang['lat']);
        $lon1 = deg2rad($coordsGiaoHang['lon']);
        $lat2 = deg2rad($coordsKho['lat']);
        $lon2 = deg2rad($coordsKho['lon']);

        // Tính độ chênh lệch giữa các vĩ độ và kinh độ
        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        // Tính theo công thức Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        // Tính góc giữa hai điểm
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Bán kính trái đất (đơn vị: mét)
        $earthRadius = 6371000;

        // Tính khoảng cách
        $distance = $earthRadius * $c;
        return $distance;
    }

    public function trangThaiDonHang()
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
        $hoaDons = HoaDon::where('MaTrangThaiHD', '3')->get();
        $danhmucsanpham = DanhMucSanPham::all();
        return view('admin.trangchu_admin.trang-thai-don-hang', [
            'title' => 'Trạng Thái Đơn Hàng',
            'danhmucsanphams' => $danhmucsanpham,
            'danhsachhoadon' => $hoaDons,
        ]);
    }
    public function capNhatTrangThaiDonHang(Request $request)
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

        $action = $request->input('action');
        $hoadonIds = $request->input('hoadon_ids', []);

        if ($hoadonIds) {
            // Xử lý logic xuất kho cho các hóa đơn được chọn
            foreach ($hoadonIds as $id) {
                // Ví dụ: tìm hóa đơn và cập nhật trạng thái
                $hoadon = HoaDon::find($id);
                if ($hoadon) {

                    if ($action == 'giao-hang-thanh-cong') {
                        // Logic cho giao hàng thành công
                        $hoadon->MaTrangThaiHD = '4';
                        $hoadon->save();
                    } elseif ($action == 'giao-hang-that-bai') {
                        // Logic cho giao hàng thất bại
                        $hoadon->MaTrangThaiHD = '5';
                        $hoadon->save();

                        // Tìm chi tiết hóa đơn dựa vào id của hóa đơn
                        $chiTietHoaDons = ChiTietHoaDon::where('MaHD', $id)->get();
                        // kho cộng số lượng
                        foreach ($chiTietHoaDons as $chiTiet) {
                            // Lấy số lượng sản phẩm cần cộng
                            $soLuongCanCong = $chiTiet->SoLuong;
                            ChiTietKho::where('MaKho', $hoadon->MaKho)
                                ->where('MaSP', $chiTiet->MaSP)
                                ->increment('SoLuongTrongKho', $soLuongCanCong);
                            // Cộng số lượng trong bảng SanPham
                            SanPham::where('MaSP', $chiTiet->MaSP)
                                ->increment('SoLuong', $soLuongCanCong);
                        }
                    }
                }
            }
            return redirect()->back()->with('success', 'Cập nhật thành công!');
        } else {
            return redirect()->back()->with('error', 'Chưa chọn hóa đơn nào để cập nhật!');
        }
    }
}
