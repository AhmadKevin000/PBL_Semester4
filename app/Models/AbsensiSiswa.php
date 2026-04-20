<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    protected $table = 'absensi_siswa';

    protected $fillable = [
        'sesi_kelas_id',
        'siswa_id',
        'status',
    ];

    public function sesiKelas()
    {
        return $this->belongsTo(SesiKelas::class, 'sesi_kelas_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
