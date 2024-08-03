<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuanDanhGia extends Model
{
    use HasFactory;
    protected $table = 'BinhLuanDanhGia'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaKH', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoSao',
        'BinhLuan',
        'NgayBinhLuan',
    ];
    public function KhachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }

}
