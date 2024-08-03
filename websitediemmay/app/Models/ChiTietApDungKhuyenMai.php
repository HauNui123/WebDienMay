<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietApDungKhuyenMai extends Model
{
    use HasFactory;
    protected $table = 'ChiTietApDungKhuyenMai'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaKM', 'MaHD']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'UuDai',
    ];
    public function KhuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'MaKM', 'MaKM');
    }
    public function HoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD');
    }
}
