<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhSanPham extends Model
{
    use HasFactory;
    protected $table = 'HinhAnhSanPham'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'MaHinh'; // Khóa chính của bảng
    protected $fillable = [
        'AnhSanPham',
    ];
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP');
    }
}
