<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    use HasFactory;
    protected $table = 'ChucVu'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaCV'; // Khóa chính của bảng
    protected $fillable = [
        'MoTa',
        'LuongTheoCa',
        'LuongLamThem',
        'LuongThuong',
        'PhuCap',
    ];
    public function NhanVien()
    {
        return $this->hasMany(NhanVien::class, 'MaCV', 'MaCV');
    }
}
