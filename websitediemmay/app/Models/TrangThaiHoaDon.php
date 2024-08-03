<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrangThaiHoaDon extends Model
{
    use HasFactory;
    protected $table = 'TrangThaiHoaDon'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaTrangThaiHD'; // Khóa chính của bảng
    protected $fillable = [
        'MoTaTrangThai',
    ];
    public function HoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaTrangThaiHD');
    }
}
