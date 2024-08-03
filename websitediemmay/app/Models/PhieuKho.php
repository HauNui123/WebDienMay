<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuKho extends Model
{
    use HasFactory;
    protected $table = 'PhieuKho'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaPK'; // Khóa chính của bảng
    public $timestamps = false;
    protected $fillable = [
        'Mota',
        'NgayNhapXuatKho',
        'TongTien',
    ];
    public function LoaiPhieuKho()
    {
        return $this->belongsTo(LoaiPhieuKho::class, 'MaLPK', 'MaLPK');
    }
    public function Kho()
    {
        return $this->belongsTo(Kho::class, 'MaKho', 'MaKho');
    }
    public function NhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'MaNV');
    }
    public function ChiTietPhieuKho()
    {
        return $this->hasMany(ChiTietPhieuKho::class, 'MaPK');
    }
}
