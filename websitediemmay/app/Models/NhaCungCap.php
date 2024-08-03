<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    use HasFactory;
    protected $table = 'NhaCungCap'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaNCC'; // Khóa chính của bảng
    protected $fillable = [
        'TenNCC',
        'DiaChi',
        'SDT',
        'Email',
    ];
    public function sanpham()
    {
        return $this->hasMany(SanPham::class, 'MaSP');
    }
}
