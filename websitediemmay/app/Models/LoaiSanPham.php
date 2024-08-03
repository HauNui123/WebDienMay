<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiSanPham extends Model
{
    use HasFactory;
    protected $table = 'LoaiSanPham'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaLoaiSP'; // Khóa chính của bảng
    protected $fillable = [
        'MoTa',
    ];
    public function danhmucsanpham()
    {
        return $this->belongsTo(DanhMucSanPham::class, 'MaDM');
    }
    public function sanpham()
    {
        return $this->hasMany(SanPham::class, 'MaLoaiSP', 'MaLoaiSP');
    }
}
