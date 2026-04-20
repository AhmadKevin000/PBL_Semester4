<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $fillable = [
        'nama',
        'jenjang_id',
        'program_id',
    ];

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function pengajaran()
    {
        return $this->hasMany(Pengajaran::class, 'mapel_id');
    }
}
