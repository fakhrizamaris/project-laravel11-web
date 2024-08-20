<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMahasiswa extends Model
{
    use HasFactory;
    public $table = 'data_pts_aktif';
    public $fillable = [
        // 'name',
        // 'nim',
        // 'angkatan',
        // 'jurusan',
        'nopts',
        'namapts',
        'fakultas',
        'prodi',
        'jurusan',
    ];
}
