<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;
    protected $table = 'KhachHang'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaKH'; // Khóa chính của bảng

    protected $fillable = [
        'TenKH',
        'GioiTinh',
        'DiaChi',
        'SDT',
        'Email',
        'MatKhau',
        'DiemTichLuy',
        'Token',
        'TimeReset',
        'TrangThaiXoa',
    ];
    
    public $timestamps = false;
  
    protected $hidden = [
        'MatKhau', 
    ];
    public function GioHang()
    {
        return $this->hasMany(GioHang::class, 'MaKH');
    }
    public function HoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaKH');
    }
    public function BinhLuanDanhGia()
    {
        return $this->hasMany(BinhLuanDanhGia::class, 'MaKH', 'MaKH');
    }
    public function ChiTietDoiDiem()
    {
        return $this->hasMany(ChiTietDoiDiem::class, 'MaKH');
    }
}
