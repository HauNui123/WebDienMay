<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    use HasFactory;
    protected $table = 'KhuyenMai'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaKm'; // Khóa chính của bảng
    public $timestamps = false;
    protected $fillable = [
        'MoTa',
        'GiaTriDonHangToiThieu',
        'NgayBatDau',
        'NgayketThuc',
        'SoDiemDoiDuoc',
        'GiaTriGiam',
        'SoLuong',
        'TrangThaiXoa',
    ];
    public function loaikhuyenmai()
    {
        return $this->belongsTo(LoaiKhuyenMai::class, 'MaLKM', 'MaLKM');
    }
    public function ChiTietApDung()
    {
        return $this->hasMany(ChiTietApDung::class, 'MaKM');
    }
    public function ChiTietApDungKhuyenMai()
    {
        return $this->hasMany(ChiTietApDungKhuyenMai::class, 'MaKM');
    }
    public function ChiTietDoiDiem()
    {
        return $this->hasMany(ChiTietDoiDiem::class, 'MaKM');
    }
    public function scopeDangHoatDong(Builder $query)
    {
        $now = now();
        return $query->where('NgayBatDau', '<=', $now)
        ->where('NgayKetThuc', '>=', $now)
        ->where('TrangThaiXoa', 1);
    }
}
