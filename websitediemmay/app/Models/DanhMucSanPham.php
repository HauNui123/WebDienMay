<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMucSanPham extends Model
{
    use HasFactory;
    protected $table = 'DanhMucSanPham'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaDM'; // Khóa chính của bảng
    protected $fillable = [
        'TenDM',
    ];
    public function loaisanpham()
    {
        return $this->hasMany(LoaiSanPham::class, 'MaDM');
    }
}
