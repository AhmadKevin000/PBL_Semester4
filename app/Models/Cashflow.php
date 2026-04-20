<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    protected $table = 'cashflow';

    protected $fillable = [
        'type',
        'kategori',
        'nominal',
        'tanggal',
        'source_snapshot',
        'sumber_id',
        'reference_id',
        'reference_type',
    ];

    /**
     * Polymorphic relation: bisa nyambung ke Penggajian, Siswa, atau model apapun.
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Helper untuk mendapatkan "Owner" (Guru atau Siswa) yang terkait dengan cashflow ini.
     * Digunakan untuk keperluan reporting.
     */
    public function owner()
    {
        // Jika reference_type adalah Siswa, itu ownernya
        if ($this->reference_type === \App\Models\Siswa::class) {
            return $this->reference;
        }
        
        // Jika reference_type adalah Penggajian, maka ownernya adalah Guru dari penggajian tersebut
        if ($this->reference_type === \App\Models\Penggajian::class) {
            return $this->reference?->guru;
        }

        return null;
    }
}
