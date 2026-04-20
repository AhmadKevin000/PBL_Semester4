<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    protected $table = 'rapor';

    protected $fillable = [
        'siswa_id',
        'pengajaran_id',
        'semester',
        'nilai',
        'keterangan',
    ];

    protected $casts = [
        'nilai'    => 'integer',
        'semester' => 'string',
    ];

    // ─── Relationships ───────────────────────────────────────────────

    public function siswa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pengajaran(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pengajaran::class, 'pengajaran_id');
    }

    public function getMapelAttribute()
    {
        return $this->pengajaran?->mapel;
    }

    // ─── Accessors ───────────────────────────────────────────────────

    /**
     * Label nilai: A/B/C/D/E sesuai rentang standar.
     */
    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->nilai >= 90 => 'A',
            $this->nilai >= 75 => 'B',
            $this->nilai >= 60 => 'C',
            $this->nilai >= 45 => 'D',
            default            => 'E',
        };
    }
}
