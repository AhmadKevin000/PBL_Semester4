<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'keterangan',
    ];

    protected $appends = ['jenjang', 'jumlah_siswa'];

    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswa()->count();
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function absensiGuru()
    {
        return $this->hasManyThrough(AbsensiGuru::class, Mapel::class, 'kelas_id', 'pengajaran_id');
    }

    public function pengajaran()
    {
        return $this->hasMany(Pengajaran::class, 'kelas_id');
    }

    public function getPrimaryTeachingAttribute()
    {
        return $this->pengajaran()->first();
    }

    public function getJenjangAttribute()
    {
        $firstTeaching = $this->pengajaran->first();
        if ($firstTeaching && $firstTeaching->mapel && $firstTeaching->mapel->jenjang) {
            return $firstTeaching->mapel->jenjang->nama;
        }
        return '-';
    }
    public function getTeacherListAttribute(): string
    {
        return $this->pengajaran->map(fn($t) => $t->guru?->nama ?? '-')->unique()->join(', ');
    }

    public function getSubjectListAttribute(): string
    {
        return $this->pengajaran->map(fn($t) => $t->mapel?->nama ?? '-')->unique()->join(', ');
    }

    public function jadwalSesi()
    {
        return $this->belongsToMany(JadwalSesi::class, 'sesi_kelas', 'kelas_id', 'jadwal_sesi_id')
            ->withPivot(['tanggal', 'semester', 'id'])
            ->withTimestamps();
    }
}
