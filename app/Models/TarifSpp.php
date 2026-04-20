<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifSpp extends Model
{
    protected $table = 'tarif_spp';

    protected $fillable = [
        'jenjang_id',
        'program_id',
        'nominal',
        'type',
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
