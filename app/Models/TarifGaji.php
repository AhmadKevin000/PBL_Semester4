<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifGaji extends Model
{
    protected $table = 'tarif_gaji';

    protected $fillable = [
        'jenjang_id',
        'program_id',
        'status_guru',
        'jumlah_siswa_min',
        'rate',
    ];

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
