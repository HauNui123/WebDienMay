<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHinhTichDiem extends Model
{
    use HasFactory;
    protected $table = 'CauHinhTichDiem'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaTichDiem'; // Khóa chính của bảng
    public $timestamps = false;

    protected $fillable = [
        'SoDiemTich',
        'SoTienTich',
        'TrangThaiApDung',
    ];
    public function HoaDon()
    {
        return $this->hasMany(SanPham::class, 'MaTichDiem', 'MaTichDiem');
    }
}
