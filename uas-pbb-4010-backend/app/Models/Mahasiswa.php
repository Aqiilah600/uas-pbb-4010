<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'tanggal_lahir',
        'jenis_kelamin',
        'foto_profil',
        'angkatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d',
    ];

    public function hobis()
    {
        return $this->belongsToMany(Hobi::class, 'mahasiswa_hobi');
    }
}