<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiKelas extends Model
{
    protected $table = 'sesi_kelas';

    protected $fillable = [
        'jadwal_sesi_id',
        'pengajaran_id',
        'tanggal',
        'semester',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwalSesi()
    {
        return $this->belongsTo(JadwalSesi::class, 'jadwal_sesi_id');
    }

    public function pengajaran()
    {
        return $this->belongsTo(Pengajaran::class, 'pengajaran_id');
    }

    public function getKelasAttribute()
    {
        return $this->pengajaran?->kelas;
    }

    public function absensiGuru()
    {
        return $this->hasOne(AbsensiGuru::class, 'sesi_kelas_id');
    }

    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswa::class, 'sesi_kelas_id');
    }
}
