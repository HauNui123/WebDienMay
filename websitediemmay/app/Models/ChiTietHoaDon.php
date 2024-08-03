<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    use HasFactory;
    protected $table = 'ChiTietHoaDon'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaHD', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoLuong',
        'DonGia',
        'ThanhTien',
    ];
    public function HoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}
