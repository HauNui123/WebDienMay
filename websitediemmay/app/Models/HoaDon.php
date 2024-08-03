<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;
    protected $table = 'HoaDon'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaHD'; // Khóa chính của bảng
    public $timestamps = false;
    protected $fillable = [
        'NgayLap',
        'TongTien',
        'DiaChiGiaoHang',
        'MaTrangThaiHD',
        'DiemTichDuoc',
        'MaTichDiem',
        'MaNV',
        'MaKH',
        'MaKho',
        'MaHT',
        'TrangThaiXoa',
        'GhiChu',
    ];
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH');
    }
    public function NhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'MaNV');
    }
    public function Kho()
    {
        return $this->belongsTo(Kho::class, 'MaKho');
    }
     // Mối quan hệ nhiều nhiều với bảng SanPham thông qua bảng ChiTietHoaDon
     public function sanPham()
     {
         return $this->belongsToMany(SanPham::class, 'ChiTietHoaDon', 'MaHD', 'MaSP');
     }

    public function ChiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'MaHD');
    }
    public function ChiTietApDungKhuyenMai()
    {
        return $this->hasMany(ChiTietApDungKhuyenMai::class, 'MaHD');
    }
    public function TrangThaiHoaDon()
    {
        return $this->belongsTo(TrangThaiHoaDon::class, 'MaTrangThaiHD', 'MaTrangThaiHD');
    }
    public function HinhThucBanHang()
    {
        return $this->belongsTo(HinhThucBanHang::class, 'MaHT', 'MaHT');
    }
    public function CauHinhTichDiem()
    {
        return $this->belongsTo(CauHinhTichDiem::class, 'MaTichDiem', 'MaTichDiem');
    }
}
