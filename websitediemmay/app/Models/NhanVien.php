<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;
    protected $table = 'NhanVien'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaNV'; // Khóa chính của bảng

    protected $fillable = [
        'TenNV',
        'CCCD',
        'GioiTinh',
        'DiaChi',
        'SDT',
        'Email',
        'AnhNV',
        'MatKhau',
        'MaCV',
        'Token',
        'TimeReset',
        'TrangThaiXoa',
    ];
    
    public $timestamps = false;
  
    protected $hidden = [
        'MatKhau', 
    ];
    public function HoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaNV');
    }
    public function PhieuKho()
    {
        return $this->hasMany(PhieuKho::class, 'MaNV');
    }
    public function ChucVu()
    {
        return $this->belongsTo(ChucVu::class, 'MaCV', 'MaCV');
    }
}
