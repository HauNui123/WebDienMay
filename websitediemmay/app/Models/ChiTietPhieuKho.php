<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuKho extends Model
{
    use HasFactory;
    protected $table = 'ChiTietPhieuKho'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaPK', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoLuong',
        'MaSP',
        'DonGia',
        'ThanhTien',
    ];

    public function PhieuKho()
    {
        return $this->belongsTo(PhieuKho::class, 'MaPK', 'MaPK');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}
