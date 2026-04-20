<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajaran extends Model
{
    protected $table = 'pengajaran';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mapel_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }
}
