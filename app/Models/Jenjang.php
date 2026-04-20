<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    protected $table = 'jenjang';

    protected $fillable = ['nama'];

    public function mapel()
    {
        return $this->hasMany(Mapel::class);
    }
}
