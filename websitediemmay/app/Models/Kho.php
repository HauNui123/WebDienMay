<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kho extends Model
{
    use HasFactory;
    protected $table = 'Kho'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaKho'; // Khóa chính của bảng

    protected $fillable = [
        'TenKho',
        'DiaChi',
        'Mota',
        'Lat',
        'Lon',
    ];
    public function ChiTietKho()
    {
        return $this->hasMany(ChiTietKho::class, 'MaKho');
    }
    public function PhieuKho()
    {
        return $this->hasMany(PhieuKho::class, 'MaKho');
    }
    public function HoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaKho');
    }
}
