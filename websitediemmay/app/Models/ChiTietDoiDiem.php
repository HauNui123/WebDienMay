<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDoiDiem extends Model
{
    use HasFactory;
    protected $table = 'ChiTietDoiDiem'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaKM', 'MaKH']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'UuDaiDoiDuoc',
    ];
    public function KhuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'MaKM', 'MaKM');
    }
    public function KhachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH');
    }
}
