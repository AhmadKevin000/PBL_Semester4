<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';

    protected $fillable = ['nama'];

    public function mapel()
    {
        return $this->hasMany(Mapel::class);
    }
}
