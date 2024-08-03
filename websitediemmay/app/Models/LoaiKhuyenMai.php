<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiKhuyenMai extends Model
{
    use HasFactory;
    protected $table = 'LoaiKhuyenMai'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaLKM'; // Khóa chính của bảng
    protected $fillable = [
        'Mota',
    ];
    public function KhuyenMai()
    {
        return $this->hasMany(KhuyenMai::class, 'MaLKM');
    }
}
