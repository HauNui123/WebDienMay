<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiPhieuKho extends Model
{
    use HasFactory;
    protected $table = 'LoaiPhieuKho'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'MaLPK'; // Khóa chính của bảng
    protected $fillable = [
        'Mota',
    ];
    public function PhieuKho()
    {
        return $this->hasMany(PhieuKho::class, 'MaLPK');
    }
}
