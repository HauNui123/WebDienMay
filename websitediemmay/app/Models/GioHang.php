<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    use HasFactory;
    protected $table = 'GioHang'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaGH'; // Khóa chính của bảng
    public $timestamps = false;
    protected $fillable = [
        'MaKH',
        'TongTien',
    ];
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH');
    }
     // Mối quan hệ nhiều nhiều với bảng SanPham thông qua bảng ChiTietGioHang
     public function sanPham()
     {
         return $this->belongsToMany(SanPham::class, 'ChiTietGioHang', 'MaGH', 'MaSP');
     }

    public function ChiTietGioHang()
    {
        return $this->hasMany(ChiTietGioHang::class, 'MaGH');
    }
}
