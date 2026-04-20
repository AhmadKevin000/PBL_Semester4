<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSesi extends Model
{
    protected $table = 'jadwal_sesi';

    protected $fillable = [
        'nama',
        'jam_mulai',
        'jam_selesai',
        'durasi',
        'max_capacity',
    ];

    public function pengajaran()
    {
        return $this->belongsToMany(Pengajaran::class, 'sesi_kelas', 'jadwal_sesi_id', 'pengajaran_id')
            ->withPivot(['tanggal', 'semester', 'id'])
            ->withTimestamps();
    }
}
