<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;
    protected $table = 'SanPham'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaSP'; // Khóa chính của bảng
    public $timestamps = false;
    protected $fillable = [
        'TenSP',
        'MoTa',
        'SoLuong',
        'GiaSP',
        'NgaySX',
        'TrangThaiXoa',
    ];
    public function nhacungcap()
    {
        return $this->belongsTo(NhaCungCap::class, 'MaNCC');
    }
    public function loaisanpham()
    {
        return $this->belongsTo(LoaiSanPham::class, 'MaLoaiSP', 'MaLoaiSP');
    }
    public function ChiTietGioHang()
    {
        return $this->hasMany(ChiTietGioHang::class, 'MaSP', 'MaSP');
    }
    public function ChiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'MaSP', 'MaSP');
    }
    public function ChiTietKho()
    {
        return $this->hasMany(ChiTietKho::class, 'MaKho', 'MaKho');
    }
    public function ChiTietApDung()
    {
        return $this->hasMany(ChiTietApDung::class, 'MaSP', 'MaSP');
    }
    public function BinhLuanDanhGia()
    {
        return $this->hasMany(BinhLuanDanhGia::class, 'MaSP', 'MaSP');
    }
    public function HinhAnhSP()
    {
        return $this->hasMany(HinhAnhSanPham::class, 'MaSP');
    }
}
