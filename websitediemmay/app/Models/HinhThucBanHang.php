<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhThucBanHang extends Model
{
    use HasFactory;
    protected $table = 'HinhThucBanHang'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'MaHT'; // Khóa chính của bảng
    protected $fillable = [
        'LoaiHinhThuc',
    ];
    public function HoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaHT');
    }
}
