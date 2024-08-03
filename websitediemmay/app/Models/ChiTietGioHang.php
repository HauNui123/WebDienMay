<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietGioHang extends Model
{
    use HasFactory;
    protected $table = 'ChiTietGioHang'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaGH', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoLuong',
    ];
 
    public function GioHang()
    {
        return $this->belongsTo(GioHang::class, 'MaGH', 'MaGH');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}
