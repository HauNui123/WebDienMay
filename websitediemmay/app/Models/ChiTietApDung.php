<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietApDung extends Model
{
    use HasFactory;
    protected $table = 'ChiTietApDung'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaKM', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoLuong',
    ];
    public function KhuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'MaKM', 'MaKM');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}
