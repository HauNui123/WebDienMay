<?php

namespace App\Http\Controllers\Admin\TrangChuAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChiTietPhieuKho;
use App\Models\DanhMucSanPham;
use App\Models\HoaDon;
use App\Models\Kho;
use App\Models\NhanVien;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class ThongKeController extends Controller
{
    public function thongKeTheoNgay()
    {
        $danhmucsanpham = DanhMucSanPham::all();
        // Lấy ngày hôm nay
        $ngayHienTai = now()->format('Y-m-d');
        // dd($ngayHienTai);

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoNgay = [];

        // Tổng doanh thu của tất cả các kho trong ngày hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo ngày cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->whereDate('HoaDon.NgayLap', $ngayHienTai)
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0;
                $doanhThu += $item->SoLuong * ($item->GiaSP - $donGia);
            }

            // Thêm vào mảng kết quả
            $doanhThuTheoNgay[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }


        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-ngay', [
            'title' => 'Thống Kê Theo Ngày',
            'danhmucsanphams' => $danhmucsanpham,
            'doanhThuTheoNgay' => $doanhThuTheoNgay,
            'ngayThongKe' => $ngayHienTai, // Truyền ngày hiện tại vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }

    public function thongKeNgayChon(Request $request)
    {
        // Lấy ngày thống kê từ request
        $ngayThongKe = $request->input('ngaythongke');

        // Kiểm tra và xử lý ngày thống kê (nếu cần)
        // Ví dụ: Chuyển đổi định dạng ngày nếu cần thiết
        $ngayThongKe = date('Y-m-d', strtotime($ngayThongKe));

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoNgay = [];

        // Tổng doanh thu của tất cả các kho trong ngày được chọn
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo ngày cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->whereDate('HoaDon.NgayLap', $ngayThongKe)
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * ($item->GiaSP - $donGia); // Đảm bảo giá trị không âm
            }

            // Thêm vào mảng kết quả
            $doanhThuTheoNgay[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }

        // Trả về view kèm theo dữ liệu doanh thu
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-ngay', [
            'title' => 'Thống Kê',
            'danhmucsanphams' => DanhMucSanPham::all(), // Danh mục sản phẩm (nếu cần)
            'doanhThuTheoNgay' => $doanhThuTheoNgay, // Dữ liệu doanh thu theo ngày cho biểu đồ
            'ngayThongKe' => $ngayThongKe, // Truyền ngày thống kê vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }
    public function thongKeTheoTuan()
    {
        $danhmucsanpham = DanhMucSanPham::all();

        // Lấy ngày hôm nay
        $ngayHienTai = now();

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoTuan = [];

        // Tổng doanh thu của tất cả các kho trong tuần hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo tuần cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereBetween('HoaDon.NgayLap', [
                    $ngayHienTai->startOfWeek()->format('Y-m-d'),
                    $ngayHienTai->endOfWeek()->format('Y-m-d'),
                ])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }

            // Thêm vào mảng kết quả
            $doanhThuTheoTuan[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }
        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-tuan', [
            'title' => 'Thống Kê Theo Tuần',
            'danhmucsanphams' => $danhmucsanpham,
            'doanhThuTheoTuan' => $doanhThuTheoTuan,
            'ngayThongKe' => $ngayHienTai->startOfWeek(), // Truyền ngày bắt đầu tuần vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }
    public function thongKeTuanChon(Request $request)
    {
        // Lấy tuần thống kê từ request
        $tuanThongKe = $request->input('tuanthongke');

        // Xác định định dạng chuỗi và chuyển đổi thành Carbon
        $ngayHienTai = Carbon::parse($tuanThongKe);

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoTuan = [];

        // Tổng doanh thu của tất cả các kho trong tuần hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo tuần cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereBetween('HoaDon.NgayLap', [
                    $ngayHienTai->startOfWeek()->format('Y-m-d'),
                    $ngayHienTai->endOfWeek()->format('Y-m-d'),
                ])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }
            // Thêm vào mảng kết quả
            $doanhThuTheoTuan[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }
        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-tuan', [
            'title' => 'Thống Kê Theo Tuần',
            'danhmucsanphams' => DanhMucSanPham::all(),
            'doanhThuTheoTuan' => $doanhThuTheoTuan,
            'ngayThongKe' => $ngayHienTai, // Truyền ngày bắt đầu tuần vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }

    public function thongKeTheoThang()
    {
        $danhmucsanpham = DanhMucSanPham::all();

        // Lấy tháng hiện tại
        $ngayHienTai = now();
        $startOfMonth = $ngayHienTai->startOfMonth()->format('Y-m-d');
        $endOfMonth = $ngayHienTai->endOfMonth()->format('Y-m-d');

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoThang = [];

        // Tổng doanh thu của tất cả các kho trong tháng hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo tháng cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereBetween('HoaDon.NgayLap', [$startOfMonth, $endOfMonth])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }
            // Thêm vào mảng kết quả
            $doanhThuTheoThang[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];


            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }
        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-thang', [
            'title' => 'Thống Kê Theo Tháng',
            'danhmucsanphams' => $danhmucsanpham,
            'doanhThuTheoThang' => $doanhThuTheoThang,
            'ngayThongKe' => $ngayHienTai->format('Y-m'), // Truyền tháng hiện tại vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }

    public function thongKeThangChon(Request $request)
    {
        // Lấy tháng thống kê từ request
        $thangThongKe = $request->input('thangthongke');

        // Xác định định dạng chuỗi và chuyển đổi thành Carbon
        $ngayThongKe = Carbon::parse($thangThongKe);
        $startOfMonth = $ngayThongKe->startOfMonth()->format('Y-m-d');
        $endOfMonth = $ngayThongKe->endOfMonth()->format('Y-m-d');

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoThang = [];

        // Tổng doanh thu của tất cả các kho trong tháng hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo tháng cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereBetween('HoaDon.NgayLap', [$startOfMonth, $endOfMonth])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }

            // Thêm vào mảng kết quả
            $doanhThuTheoThang[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }
        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-thang', [
            'title' => 'Thống Kê Theo Tháng',
            'danhmucsanphams' => DanhMucSanPham::all(),
            'doanhThuTheoThang' => $doanhThuTheoThang,
            'ngayThongKe' => $ngayThongKe->format('Y-m'),  // Truyền ngày bắt đầu tháng vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }

    public function thongKeTheoQuy()
    {
        $currentYear = now()->year;
        $currentQuarter = ceil(now()->month / 3); // Adjust to get the correct quarter format (1, 2, 3, 4)

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoQuy = [];

        // Tổng doanh thu của tất cả các kho trong quý hiện tại
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo quý cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereYear('HoaDon.NgayLap', $currentYear)
                ->whereRaw('(MONTH(HoaDon.NgayLap) - 1) / 3 + 1 = ?', [$currentQuarter])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }

            // Thêm vào mảng kết quả
            $doanhThuTheoQuy[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }

        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-quy', [
            'title' => 'Thống Kê Theo Quý',
            'danhmucsanphams' => DanhMucSanPham::all(),
            'doanhThuTheoQuy' => $doanhThuTheoQuy,
            'quyThongKe' => $currentQuarter, // Truyền quý hiện tại vào view
            'namThongKe' => $currentYear, // Truyền năm hiện tại vào view (nếu cần)
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }

    public function thongKeQuyChon(Request $request)
    {
        $quy = $request->input('quythongke');
        $nam = $request->input('namthongke');

        // Xử lý tính toán doanh thu theo quý và năm đã chọn
        // Ví dụ: tính toán doanh thu theo quý và năm cho từng kho

        // Lấy danh sách các kho
        $khoList = Kho::all();

        // Mảng để lưu kết quả
        $doanhThuTheoQuy = [];

        // Tổng doanh thu của tất cả các kho trong quý và năm đã chọn
        $tongDoanhThu = 0;

        // Duyệt qua từng kho để tính doanh thu
        foreach ($khoList as $kho) {
            // Query để lấy doanh thu theo quý và năm cho từng kho
            $query = HoaDon::join('ChiTietHoaDon', 'HoaDon.MaHD', '=', 'ChiTietHoaDon.MaHD')
                ->join('SanPham', 'ChiTietHoaDon.MaSP', '=', 'SanPham.MaSP')
                ->leftJoin('ChiTietPhieuKho', function ($join) use ($kho) {
                    $join->on('SanPham.MaSP', '=', 'ChiTietPhieuKho.MaSP')
                        ->where('ChiTietPhieuKho.MaPK', function ($query) use ($kho) {
                            $query->select('PhieuKho.MaPK')
                                ->from('PhieuKho')
                                ->where('PhieuKho.MaKho', $kho->MaKho)
                                ->where('PhieuKho.MaLPK', 1)
                                ->orderBy('PhieuKho.NgayNhapXuatKho', 'desc')
                                ->limit(1);
                        });
                })
                ->where('HoaDon.MaKho', $kho->MaKho)
                ->whereYear('HoaDon.NgayLap', $nam)
                ->whereRaw('DATEPART(QUARTER, HoaDon.NgayLap) = ?', [$quy])
                ->whereIn('HoaDon.MaTrangThaiHD', [4, 7])
                ->where('HoaDon.TrangThaiXoa', 1)
                ->select('SanPham.GiaSP', 'ChiTietHoaDon.SoLuong', 'ChiTietPhieuKho.DonGia')
                ->get();

            // Tính tổng doanh thu cho kho hiện tại
            $doanhThu = 0;
            foreach ($query as $item) {
                $donGia = $item->DonGia ?? 0; // Xử lý giá trị null
                $doanhThu += $item->SoLuong * max($item->GiaSP - $donGia, 0); // Đảm bảo giá trị không âm
            }
            // Thêm vào mảng kết quả
            $doanhThuTheoQuy[] = [
                'TenKho' => $kho->TenKho,
                'DoanhThu' => $doanhThu
            ];

            // Cộng vào tổng doanh thu của tất cả các kho
            $tongDoanhThu += $doanhThu;
        }

        // Trả về view với dữ liệu đã tính toán
        return view('admin.trangchu_admin.thong-ke-doanh-thu-theo-quy', [
            'title' => 'Thống Kê Theo Quý',
            'danhmucsanphams' => DanhMucSanPham::all(),
            'doanhThuTheoQuy' => $doanhThuTheoQuy,
            'quyThongKe' => $quy, // Truyền quý đã chọn vào view
            'namThongKe' => $nam, // Truyền năm đã chọn vào view
            'tongDoanhThu' => $tongDoanhThu, // Truyền tổng doanh thu vào view
        ]);
    }
}
