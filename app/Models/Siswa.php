<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'kelas_id',
        'jumlah_mapel',
        'tanggal_daftar',
        'spp_jatuh_tempo',
    ];

    protected $appends = ['jenjang', 'program', 'jumlah_mapel', 'spp_nominal'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiSiswa::class);
    }

    public function rapor()
    {
        return $this->hasMany(Rapor::class);
    }

    /**
     * Semua cashflow yang bersumber dari siswa ini (SPP, Pendaftaran, dll).
     */
    public function cashflow()
    {
        return $this->morphMany(Cashflow::class, 'reference');
    }

    // --- Accessors ---

    public function getJenjangAttribute()
    {
        return $this->kelas?->jenjang;
    }

    public function getProgramAttribute()
    {
        $firstTeaching = $this->kelas?->pengajaran->first();
        if ($firstTeaching && $firstTeaching->mapel && $firstTeaching->mapel->program) {
            return $firstTeaching->mapel->program->nama;
        }
        return '-';
    }

    public function getJumlahMapelAttribute()
    {
        // Prioritaskan nilai dari database (input manual saat pendaftaran)
        if ($this->attributes['jumlah_mapel'] ?? null) {
            return (int) $this->attributes['jumlah_mapel'];
        }

        // Fallback ke hitungan dinamis dari kelas
        return $this->kelas?->pengajaran()->count() ?? 0;
    }

    public function getSppNominalAttribute()
    {
        $programName = $this->program;
        $jenjangName = $this->jenjang;
        $jumlahMapel = $this->jumlah_mapel;
        
        if ($programName === '-' || $jenjangName === '-') {
            return 0;
        }

        $financeService = new \App\Services\FinanceService();
        return $financeService->calculateSpp($programName, $jenjangName, $jumlahMapel);
    }
}
