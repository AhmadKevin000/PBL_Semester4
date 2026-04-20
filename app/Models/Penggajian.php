<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';

    protected $fillable = [
        'absensi_guru_id',
        'guru_id',
        'tarif_gaji_id',
        'nominal',
        'rate_snapshot',
        'status_pembayaran',
        'tanggal_pembayaran',
    ];

    public function absensiGuru()
    {
        return $this->belongsTo(AbsensiGuru::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tarifGaji()
    {
        return $this->belongsTo(TarifGaji::class);
    }

    public function cashflow()
    {
        return $this->morphOne(Cashflow::class, 'reference');
    }
}
