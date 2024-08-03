<?php

namespace App\Http\Controllers\ThanhToan;

use App\Http\Controllers\Controller;
use App\Models\CauHinhTichDiem;
use App\Models\ChiTietApDungKhuyenMai;
use App\Models\ChiTietDoiDiem;
use App\Models\ChiTietGioHang;
use App\Models\ChiTietHoaDon;
use App\Models\ChiTietKho;
use App\Models\ChiTietPhieuKho;
use App\Models\DanhMucSanPham;
use App\Models\GioHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\Kho;
use App\Models\KhuyenMai;
use App\Models\PhieuKho;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ThanhToanController extends Controller
{
    public function index_HoaDon(Request $request)
    {

        // Lấy thông tin người dùng từ session
        $user = Session::get('user');
        $maKH = $user->MaKH;
        $danhmucsanpham = DanhMucSanPham::all();
        $vouchers = ChiTietDoiDiem::where('MaKH', $maKH)->get();
        $cauHinhDangHoatDong = CauHinhTichDiem::where('TrangThaiApDung', 1)->first();
        // Lấy dữ liệu từ request
        $tongTien = $request->input('tongtien') ?? session('tongTien');
        $chiTietGioHangs = json_decode($request->input('chitietgiohangs')) ?? session('chiTietGioHangs');
        session(['tongTien' => $tongTien, 'chiTietGioHangs' => $chiTietGioHangs,]);
        return view('thanhtoan.lap-hoa-don', [
            'title' => 'Hóa Đơn',
            'tongTien' => $tongTien,
            'chiTietGioHangs' => $chiTietGioHangs,
            'danhmucsanphams' => $danhmucsanpham,
            'vouchers' => $vouchers,
            'cauHinhDangHoatDong' => $cauHinhDangHoatDong,
        ]);
    }

    public function thanhToan(Request $request)
    {
        $paymentMethod = $request->input('payment_method');
        $diaChi = $request->input('diaChiGiaoHang');
        $tongtiengiam = $request->input('tongTien');
        $diemtichduoc = $request->input('diemtichluy');
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'district' => 'required',
            'ward' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $selectedCityId = $request->input('city');
        $selectedDistrictId = $request->input('district');
        $selectedWardId = $request->input('ward');

        $pattern = "/^[^,]+\s*[^,]*$/";
        if (!preg_match($pattern, $diaChi)) {
            return redirect()->back()->withErrors(['diaChiGiaoHang' => 'Địa chỉ giao hàng không hợp lệ (ví dụ: địa chỉ, đường).']);
        }
        $ghiChu = $request->input('ghiChu');
        $maKhuyenMai = $request->input('maKhuyenMai');
        $maVoucher = $request->input('maVoucher');
        $diaChiGiaoHang = trim($diaChi) . ', ' . $selectedWardId . ', ' . $selectedDistrictId . ', ' . $selectedCityId;
        session(['diaChiGiaoHang' => $diaChiGiaoHang, 'ghiChu' => $ghiChu, 'maKhuyenMai' => $maKhuyenMai, 'maVoucher' => $maVoucher, 'tongTienGiam' => $tongtiengiam, 'diemtichduoc' => $diemtichduoc]);
        switch ($paymentMethod) {
            case 'cod':
                return $this->thanhToan_COD($request);
            case 'momo':
                if ($request->session()->get('tongTien') < 30000000)
                    return $this->thanhToan_Momo($request);
                else
                    return redirect()->back()->withErrors(['tongTien' => 'Số tiền vượt quá hạn mức thanh toán của MoMo.']);
            case 'vnpay':
                return $this->thanhtoan_vnpay($request);
            default:
                return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }
    }

    public function checkDiscounts(Request $request)
    {
        $maKhuyenMai = $request->input('maKhuyenMai');
        $maVoucher = $request->input('maVoucher');
        $tongTienHoaDon = $request->input('tongTien');

        $khuyenMai = null;
        $voucher = null;
        $discounts = [];

        // Kiểm tra mã khuyến mãi
        if ($maKhuyenMai) {
            $khuyenMai = KhuyenMai::where('MaKM', $maKhuyenMai)->first();

            if (!$khuyenMai) {
                return response()->json(['error' => 'Mã khuyến mãi không hợp lệ.'], 404);
            }

            if ($khuyenMai->SoLuong == 0) {
                return response()->json(['error' => 'Số lượng khuyến mãi đã hết.'], 400);
            }

            $now = Carbon::now();
            if ($now->lt($khuyenMai->NgayBatDau) || $now->gt($khuyenMai->NgayKetThuc)) {
                return response()->json(['error' => 'Mã khuyến mãi không nằm trong thời gian áp dụng.'], 400);
            }

            if ($tongTienHoaDon < $khuyenMai->GiaTriDonHangToiThieu) {
                return response()->json(['error' => 'Giá trị đơn hàng không đủ để áp dụng mã khuyến mãi.'], 400);
            }

            $discounts[] = [
                'MaLKM' => $khuyenMai->MaLKM,
                'GiaTriGiam' => $khuyenMai->GiaTriGiam
            ];
        }

        // Kiểm tra voucher
        if ($maVoucher) {
            $voucher = KhuyenMai::where('MaKM', $maVoucher)->first();

            $now = Carbon::now();
            if ($now->lt($voucher->NgayBatDau) || $now->gt($voucher->NgayKetThuc)) {
                return response()->json(['error' => 'Voucher không nằm trong thời gian áp dụng.'], 400);
            }

            if ($tongTienHoaDon < $voucher->GiaTriDonHangToiThieu) {
                return response()->json(['error' => 'Giá trị đơn hàng không đủ để áp dụng voucher.'], 400);
            }

            $discounts[] = [
                'MaLKM' => $voucher->MaLKM,
                'GiaTriGiam' => $voucher->GiaTriGiam
            ];
        }

        return response()->json($discounts);
    }
    public function thanhtoan_COD($request)
    {
        // Lấy cấu hình tích điểm đang hoạt động
        $cauHinhDangHoatDong = CauHinhTichDiem::where('TrangThaiApDung', 1)->first();
        $user = Session::get('user');
        $hoaDon = new HoaDon();
        $hoaDon->NgayLap = now();
        $hoaDon->TongTien = $request->session()->get('tongTienGiam');
        $hoaDon->DiaChiGiaoHang = $request->session()->get('diaChiGiaoHang');
        $hoaDon->GhiChu = $request->session()->get('ghiChu');
        $hoaDon->MaTrangThaiHD = "1";
        $hoaDon->MaNV = "1";
        $hoaDon->MaHT = "2";
        $hoaDon->MaKho = "1";
        $hoaDon->MaTichDiem = $cauHinhDangHoatDong->MaTichDiem;
        $hoaDon->DiemTichDuoc = $request->session()->get('diemtichduoc');
        $hoaDon->MaKH = $user->MaKH;
        $hoaDon->TrangThaiXoa = "1";
        $hoaDon->save();

        // Lưu chi tiết áp dụng khuyến mãi
        $MakhuyeMai = $request->session()->get('maKhuyenMai');
        if ($MakhuyeMai) {
            $khuyenMai = KhuyenMai::where('MaKM', $MakhuyeMai)->first();
            $khuyenMai->SoLuong -= 1;
            $khuyenMai->save();

            $chitietapdungkhuyenmai = new ChiTietApDungKhuyenMai();
            $chitietapdungkhuyenmai->MaKM = $MakhuyeMai;
            $chitietapdungkhuyenmai->MaHD = $hoaDon->MaHD;
            $chitietapdungkhuyenmai->UuDai = "Áp dụng khuyến mãi hóa đơn " . (string) $hoaDon->MaHD;
            $chitietapdungkhuyenmai->save();
        }

        // xóa chi tiết đổi voucher
        $MaVoucher = $request->session()->get('maVoucher');
        if ($MaVoucher) {
            ChiTietDoiDiem::where('MaKH', $user->MaKH)
                ->where('MaKM', $MaVoucher)
                ->delete();

            $chitietapdungvoucher = new ChiTietApDungKhuyenMai();
            $chitietapdungvoucher->MaKM = $MaVoucher;
            $chitietapdungvoucher->MaHD = $hoaDon->MaHD;
            $chitietapdungvoucher->UuDai = "Áp dụng voucher hóa đơn " . (string) $hoaDon->MaHD;
            $chitietapdungvoucher->save();
        }

        // Lưu chi tiết hóa đơn vào bảng ChiTietHoaDon
        $chiTietGioHangs = $request->session()->get('chiTietGioHangs');
        foreach ($chiTietGioHangs as $chiTiet) {
            $chiTietHoaDon = new ChiTietHoaDon();
            $chiTietHoaDon->MaHD = $hoaDon->MaHD; // Sử dụng khóa chính của hóa đơn
            $chiTietHoaDon->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            $chiTietHoaDon->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            $chiTietHoaDon->DonGia = $chiTiet->san_pham->GiaGiam; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            $chiTietHoaDon->ThanhTien = $chiTietHoaDon->SoLuong * $chiTietHoaDon->DonGia; // Tính toán thành tiền
            // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
            $chiTietHoaDon->save();
        }

        // Tìm giỏ hàng của người dùng
        $gioHang = GioHang::where('MaKH', $user->MaKH)->first();

        foreach ($chiTietGioHangs as $chiTiet) {
            ChiTietGioHang::where('MaGH', $gioHang->MaGH)
                ->where('MaSP', $chiTiet->MaSP)
                ->delete();
        }
        return redirect('/giohang/gio-hang-cua-ban')->with('success', 'Thanh toán thành công!');
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function thanhtoan_Momo($request)
    {
        $tonghoaDon = $request->session()->get('tongTienGiam');

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua ATM MoMo";
        $amount = $tonghoaDon;
        $orderId = time() . "";
        $redirectUrl = route('momoReturn');
        $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
        $extraData = "";


        $requestId = time() . "";
        // $requestType = "payWithATM";//thanh toán atm
        $requestType = "captureWallet"; //thanh toán QR code
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        // dd($result);
        $jsonResult = json_decode($result, true);  // decode json

        //Just a example, please check more in there
        return redirect()->to($jsonResult['payUrl']);
    }

    public function thanhtoan_vnpay($request)
    {
        $tongTien = (int)$request->session()->get('tongTienGiam');
        $chiTietGioHangs = json_decode($request->input('chitietgiohangs'));
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = "KEXYC87R"; //Mã website tại VNPAY 
        $vnp_HashSecret = "CEN23CUK4FAG0P1CCJFIQB0WSKG3LOIK"; //Chuỗi bí mật

        $vnp_TxnRef = uniqid(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn";
        $vnp_OrderType = "Điện máy HauLongPhat";
        $vnp_Amount = $tongTien * 100;
        $vnp_Locale = "vi-VN";
        $vnp_BankCode = "";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
        return redirect()->away($vnp_Url);
    }

    public function handleMomoReturn(Request $request)
    {
        $resultCode = $request->query('resultCode');
        if ($resultCode == 0) {
            // Thanh toán thành công, tạo hóa đơn
            // Lấy cấu hình tích điểm đang hoạt động
            $cauHinhDangHoatDong = CauHinhTichDiem::where('TrangThaiApDung', 1)->first();
            $user = Session::get('user');
            $hoaDon = new HoaDon();
            $hoaDon->NgayLap = now();
            $hoaDon->TongTien = $request->session()->get('tongTienGiam');
            $hoaDon->DiaChiGiaoHang = $request->session()->get('diaChiGiaoHang');
            $hoaDon->GhiChu = $request->session()->get('ghiChu');
            $hoaDon->MaTrangThaiHD = "2";
            $hoaDon->MaNV = "1";
            $hoaDon->MaHT = "2";
            $hoaDon->MaKho = "1";
            $hoaDon->MaTichDiem = $cauHinhDangHoatDong->MaTichDiem;
            $hoaDon->DiemTichDuoc = $request->session()->get('diemtichduoc');
            $hoaDon->MaKH = $user->MaKH;
            $hoaDon->TrangThaiXoa = "1";
            $hoaDon->save();

            // Lấy điểm tích lũy từ session
            $diemTichDuoc = $request->session()->get('diemtichduoc');

            // Cập nhật điểm tích lũy cho khách hàng dựa trên MaKH
            KhachHang::where('MaKH', $user->MaKH)->increment('DiemTichLuy', $diemTichDuoc);

            // Lưu chi tiết áp dụng khuyến mãi
            $MakhuyeMai = $request->session()->get('maKhuyenMai');
            if ($MakhuyeMai) {

                $khuyenMai = KhuyenMai::where('MaKM', $MakhuyeMai)->first();
                $khuyenMai->SoLuong -= 1;
                $khuyenMai->save();

                $chitietapdungkhuyenmai = new ChiTietApDungKhuyenMai();
                $chitietapdungkhuyenmai->MaKM = $MakhuyeMai;
                $chitietapdungkhuyenmai->MaHD = $hoaDon->MaHD;
                $chitietapdungkhuyenmai->UuDai = "Áp dụng khuyến mãi hóa đơn " . (string) $hoaDon->MaHD;
                $chitietapdungkhuyenmai->save();
            }

            // xóa chi tiết đổi voucher
            $MaVoucher = $request->session()->get('maVoucher');
            if ($MaVoucher) {
                ChiTietDoiDiem::where('MaKH', $user->MaKH)
                    ->where('MaKM', $MaVoucher)
                    ->delete();

                $chitietapdungvoucher = new ChiTietApDungKhuyenMai();
                $chitietapdungvoucher->MaKM = $MaVoucher;
                $chitietapdungvoucher->MaHD = $hoaDon->MaHD;
                $chitietapdungvoucher->UuDai = "Áp dụng voucher hóa đơn " . (string) $hoaDon->MaHD;
                $chitietapdungvoucher->save();
            }


            // Lưu chi tiết hóa đơn vào bảng ChiTietHoaDon
            $chiTietGioHangs = $request->session()->get('chiTietGioHangs');
            foreach ($chiTietGioHangs as $chiTiet) {
                $chiTietHoaDon = new ChiTietHoaDon();
                $chiTietHoaDon->MaHD = $hoaDon->MaHD; // Sử dụng khóa chính của hóa đơn
                $chiTietHoaDon->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                $chiTietHoaDon->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                $chiTietHoaDon->DonGia = $chiTiet->san_pham->GiaGiam; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                $chiTietHoaDon->ThanhTien = $chiTietHoaDon->SoLuong * $chiTietHoaDon->DonGia; // Tính toán thành tiền
                // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
                $chiTietHoaDon->save();
            }

            // // Tìm kho gần nhất
            // $diaChiGiaoHang = $request->session()->get('diaChiGiaoHang');
            // $danhsachcuahang = Kho::all();
            // $khoGanNhat = null;
            // $khoangCachGanNhat = PHP_INT_MAX;

            // // Sử dụng OpenStreetMap và Nominatim để tính khoảng cách
            // foreach ($danhsachcuahang as $kho) {
            //     $khoangCach = $this->tinhKhoangCach($diaChiGiaoHang, $kho->DiaChi); // Implement this function
            //     if ($khoangCach < $khoangCachGanNhat) {
            //         $khoangCachGanNhat = $khoangCach;
            //         $khoGanNhat = $kho;
            //     }
            // }

            // //Lập phiếu xuất kho
            // $phieukho = new PhieuKho();
            // $phieukho->MaLPK = "2";
            // $phieukho->Mota = "Xuất hàng hóa đơn" . (string) $hoaDon->MaHD;
            // $phieukho->NgayNhapXuatKho = now();
            // $phieukho->TongTien = $request->session()->get('tongTien');
            // $phieukho->MaNV = "1";
            // $phieukho->MaKho = $khoGanNhat->MaKho;
            // $phieukho->save();

            // // Lưu chi tiết hóa đơn vào bảng ChiTietPhieuKho
            // $chiTietGioHangs = $request->session()->get('chiTietGioHangs');
            // foreach ($chiTietGioHangs as $chiTiet) {
            //     $chiTietPhieuKho = new ChiTietPhieuKho();
            //     $chiTietPhieuKho->MaPK = $phieukho->MaPK; // Sử dụng khóa chính của phiếu kho
            //     $chiTietPhieuKho->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            //     $chiTietPhieuKho->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            //     $chiTietPhieuKho->DonGia = $chiTiet->san_pham->GiaGiam; // Sử dụng '->' để truy cập thuộc tính của đối tượng
            //     $chiTietPhieuKho->ThanhTien = $chiTietHoaDon->SoLuong * $chiTietHoaDon->DonGia; // Tính toán thành tiền
            //     // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
            //     $chiTietPhieuKho->save();
            // }

            // // kho trừ số lượng
            // foreach ($chiTietGioHangs as $chiTiet) {
            //     // Lấy số lượng sản phẩm cần trừ
            //     $soLuongCanTru = $chiTiet->SoLuong;
            //     ChiTietKho::where('MaKho', $khoGanNhat->MaKho)
            //         ->where('MaSP', $chiTiet->MaSP)
            //         ->decrement('SoLuongTrongKho', $soLuongCanTru);
            //     // Trừ số lượng trong bảng SanPham
            //     SanPham::where('MaSP', $chiTiet->MaSP)
            //         ->decrement('SoLuong', $soLuongCanTru);
            // }

            // Tìm giỏ hàng của người dùng
            $gioHang = GioHang::where('MaKH', $user->MaKH)->first();

            foreach ($chiTietGioHangs as $chiTiet) {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)
                    ->where('MaSP', $chiTiet->MaSP)
                    ->delete();
            }

            return redirect('/giohang/gio-hang-cua-ban')->with('success', 'Thanh toán thành công!');
        } else {
            // Thanh toán thất bại hoặc bị hủy
            return redirect('/giohang/gio-hang-cua-ban')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
        }
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "CEN23CUK4FAG0P1CCJFIQB0WSKG3LOIK";
        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                // Thanh toán thành công, tạo hóa đơn
                // Lấy cấu hình tích điểm đang hoạt động
                $cauHinhDangHoatDong = CauHinhTichDiem::where('TrangThaiApDung', 1)->first();
                $user = Session::get('user');
                $hoaDon = new HoaDon();
                $hoaDon->NgayLap = now();
                $hoaDon->TongTien = $request->session()->get('tongTienGiam');
                $hoaDon->DiaChiGiaoHang = $request->session()->get('diaChiGiaoHang');
                $hoaDon->GhiChu = $request->session()->get('ghiChu');
                $hoaDon->MaTrangThaiHD = "2";
                $hoaDon->MaNV = "1";
                $hoaDon->MaHT = "2";
                $hoaDon->MaKho = "1";
                $hoaDon->MaTichDiem = $cauHinhDangHoatDong->MaTichDiem;
                $hoaDon->DiemTichDuoc = $request->session()->get('diemtichduoc');
                $hoaDon->MaKH = $user->MaKH;
                $hoaDon->TrangThaiXoa = "1";
                $hoaDon->save();

                // Lấy điểm tích lũy từ session
                $diemTichDuoc = $request->session()->get('diemtichduoc');
                // Cập nhật điểm tích lũy cho khách hàng dựa trên MaKH
                KhachHang::where('MaKH', $user->MaKH)->increment('DiemTichLuy', $diemTichDuoc);

                // Lưu chi tiết áp dụng khuyến mãi
                $MakhuyeMai = $request->session()->get('maKhuyenMai');
                if ($MakhuyeMai) {
                    $khuyenMai = KhuyenMai::where('MaKM', $MakhuyeMai)->first();
                    $khuyenMai->SoLuong -= 1;
                    $khuyenMai->save();

                    $chitietapdungkhuyenmai = new ChiTietApDungKhuyenMai();
                    $chitietapdungkhuyenmai->MaKM = $MakhuyeMai;
                    $chitietapdungkhuyenmai->MaHD = $hoaDon->MaHD;
                    $chitietapdungkhuyenmai->UuDai = "Áp dụng khuyến mãi hóa đơn " . (string) $hoaDon->MaHD;
                    $chitietapdungkhuyenmai->save();
                }
                // xóa chi tiết đổi voucher
                $MaVoucher = $request->session()->get('maVoucher');
                if ($MaVoucher) {
                    ChiTietDoiDiem::where('MaKH', $user->MaKH)
                        ->where('MaKM', $MaVoucher)
                        ->delete();

                    $chitietapdungvoucher = new ChiTietApDungKhuyenMai();
                    $chitietapdungvoucher->MaKM = $MaVoucher;
                    $chitietapdungvoucher->MaHD = $hoaDon->MaHD;
                    $chitietapdungvoucher->UuDai = "Áp dụng voucher hóa đơn " . (string) $hoaDon->MaHD;
                    $chitietapdungvoucher->save();
                }
                //kiểm tra trừ số lượng

                // Lưu chi tiết hóa đơn vào bảng ChiTietHoaDon
                $chiTietGioHangs = $request->session()->get('chiTietGioHangs');
                foreach ($chiTietGioHangs as $chiTiet) {
                    $chiTietHoaDon = new ChiTietHoaDon();
                    $chiTietHoaDon->MaHD = $hoaDon->MaHD; // Sử dụng khóa chính của hóa đơn
                    $chiTietHoaDon->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                    $chiTietHoaDon->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                    $chiTietHoaDon->DonGia = $chiTiet->san_pham->GiaGiam; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                    $chiTietHoaDon->ThanhTien = $chiTietHoaDon->SoLuong * $chiTietHoaDon->DonGia; // Tính toán thành tiền
                    // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
                    $chiTietHoaDon->save();
                }

                // // Tìm kho gần nhất
                // $diaChiGiaoHang = $request->session()->get('diaChiGiaoHang');
                // $danhsachcuahang = Kho::all();
                // $khoGanNhat = null;
                // $khoangCachGanNhat = PHP_INT_MAX;

                // // Sử dụng OpenStreetMap và Nominatim để tính khoảng cách
                // foreach ($danhsachcuahang as $kho) {
                //     $khoangCach = $this->tinhKhoangCach($diaChiGiaoHang, $kho->DiaChi); // Implement this function
                //     if ($khoangCach < $khoangCachGanNhat) {
                //         $khoangCachGanNhat = $khoangCach;
                //         $khoGanNhat = $kho;
                //     }
                // }

                // //Lập phiếu xuất kho
                // $phieukho = new PhieuKho();
                // $phieukho->MaLPK = "2";
                // $phieukho->Mota = "Xuất hàng hóa đơn " . (string) $hoaDon->MaHD;
                // $phieukho->NgayNhapXuatKho = now();
                // $phieukho->TongTien = $request->session()->get('tongTien');
                // $phieukho->MaNV = "1";
                // $phieukho->MaKho = $khoGanNhat->MaKho;
                // $phieukho->save();

                // // Lưu chi tiết hóa đơn vào bảng ChiTietPhieuKho
                // $chiTietGioHangs = $request->session()->get('chiTietGioHangs');
                // foreach ($chiTietGioHangs as $chiTiet) {
                //     $chiTietPhieuKho = new ChiTietPhieuKho();
                //     $chiTietPhieuKho->MaPK = $phieukho->MaPK; // Sử dụng khóa chính của phiếu kho
                //     $chiTietPhieuKho->MaSP = $chiTiet->MaSP; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                //     $chiTietPhieuKho->SoLuong = $chiTiet->SoLuong; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                //     $chiTietPhieuKho->DonGia = $chiTiet->san_pham->GiaGiam; // Sử dụng '->' để truy cập thuộc tính của đối tượng
                //     $chiTietPhieuKho->ThanhTien = $chiTietHoaDon->SoLuong * $chiTietHoaDon->DonGia; // Tính toán thành tiền
                //     // Các thông tin khác của chi tiết hóa đơn cần phải được cập nhật từ request hoặc session
                //     $chiTietPhieuKho->save();
                // }

                // // kho trừ số lượng
                // foreach ($chiTietGioHangs as $chiTiet) {
                //     // Lấy số lượng sản phẩm cần trừ
                //     $soLuongCanTru = $chiTiet->SoLuong;
                //     ChiTietKho::where('MaKho', $khoGanNhat->MaKho)
                //         ->where('MaSP', $chiTiet->MaSP)
                //         ->decrement('SoLuongTrongKho', $soLuongCanTru);
                //     // Trừ số lượng trong bảng SanPham
                //     SanPham::where('MaSP', $chiTiet->MaSP)
                //         ->decrement('SoLuong', $soLuongCanTru);
                // }

                // Tìm giỏ hàng của người dùng
                $gioHang = GioHang::where('MaKH', $user->MaKH)->first();

                foreach ($chiTietGioHangs as $chiTiet) {
                    ChiTietGioHang::where('MaGH', $gioHang->MaGH)
                        ->where('MaSP', $chiTiet->MaSP)
                        ->delete();
                }


                return redirect('/giohang/gio-hang-cua-ban')->with('success', 'Thanh toán thành công!');
            } else {

                // Quay lại trang lap-hoa-don
                return redirect()->route('lap-hoa-don');
            }
        } else {
            return redirect('/giohang/gio-hang-cua-ban')->with('error', 'Chữ ký không hợp lệ!');
        }
    }

    private function tinhKhoangCach($diaChiGiaoHang, $diaChiKho)
    {
        $coordsGiaoHang = $this->getCoordinates($diaChiGiaoHang);
        $coordsKho = $this->getCoordinates($diaChiKho);

        if ($coordsGiaoHang && $coordsKho) {
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

            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
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
        $lat1 = deg2rad($coordsGiaoHang['lat']);
        $lon1 = deg2rad($coordsGiaoHang['lon']);
        $lat2 = deg2rad($coordsKho['lat']);
        $lon2 = deg2rad($coordsKho['lon']);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $c = 2 * asin(sqrt($a));

        $earthRadius = 6371000; // Radius of the Earth in meters

        $distance = $earthRadius * $c;
        return $distance;
    }
}
