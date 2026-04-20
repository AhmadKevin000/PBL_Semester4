<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $table = 'absensi_guru';

    protected $fillable = [
        'sesi_kelas_id',
        'guru_id',
        'kelas_id',
        'mapel_id',
        'check_in',
        'check_out',
        'status',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelasSnapshot()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function sesiKelas()
    {
        return $this->belongsTo(SesiKelas::class, 'sesi_kelas_id');
    }

    /**
     * Helper to get class info - uses snapshot if available, fallback to sesiKelas
     */
    public function getKelasAttribute()
    {
        if ($this->kelas_id) {
            return $this->kelasSnapshot;
        }
        return $this->sesiKelas?->kelas;
    }

    public function getPengajaranAttribute()
    {
        // Snapshot Data from current model if available
        if ($this->guru_id || $this->mapel_id) {
            return (object) [
                'guru' => $this->guru ?? (object) ['nama' => 'Unknown'],
                'mapel' => (object) [
                    'nama' => $this->mapel?->nama ?? 'Unknown',
                    'program' => $this->mapel?->program ?? '-',
                    'jenjang' => $this->mapel?->jenjang ?? '-',
                ],
                'kelas' => $this->kelas,
            ];
        }

        // Legacy Fallback (traversing sesiKelas relation)
        $pengajaran = $this->sesiKelas?->pengajaran;
        
        if ($pengajaran) {
            return (object) [
                'guru' => $pengajaran->guru,
                'mapel' => (object) [
                    'nama' => $pengajaran->mapel?->nama ?? 'Unknown',
                    'program' => $pengajaran->mapel?->program ?? '-',
                    'jenjang' => $pengajaran->mapel?->jenjang ?? '-',
                ],
                'kelas' => $pengajaran->kelas,
            ];
        }

        return (object) [
            'guru' => (object) ['nama' => 'Unknown'],
            'mapel' => (object) [
                'nama' => 'Belum Diatur',
                'program' => '-',
                'jenjang' => '-',
            ],
            'kelas' => $this->kelas,
        ];
    }

    public function penggajian()
    {
        return $this->hasOne(Penggajian::class, 'absensi_guru_id');
    }

    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswa::class, 'sesi_kelas_id', 'sesi_kelas_id');
    }

    /**
     * Get count of students present dynamically
     */
    public function getJumlahSiswaHadirAttribute()
    {
        return $this->absensiSiswa()->where('status', 'hadir')->count();
    }
}
