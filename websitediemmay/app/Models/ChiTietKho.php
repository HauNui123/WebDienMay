<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietKho extends Model
{
    use HasFactory;
    protected $table = 'ChiTietKho'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = ['MaKho', 'MaSP']; // Khóa chính của bảng
    public $incrementing = false; // Nếu bảng có siêu khóa thì cần dòng này
    public $timestamps = false;
    protected $fillable = [
        'SoLuongTrongKho',
    ];
    public function Kho()
    {
        return $this->belongsTo(Kho::class, 'MaKho', 'MaKho');
    }
    public function SanPham()
    {
        return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP');
    }
}
