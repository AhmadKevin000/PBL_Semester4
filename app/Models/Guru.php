<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cashflow;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'pengajaran', 'guru_id', 'kelas_id');
    }

    public function getAbsensiGuru()
    {
        return AbsensiGuru::where('guru_id', $this->id)
            ->orWhereHas('sesiKelas.pengajaran', function ($q) {
                $q->where('guru_id', $this->id);
            });
    }

    public function getTotalSalaryAttribute(): int
    {
        return Cashflow::where('kategori', 'gaji_guru')
            ->where(function ($query) {
                $query->where('sumber_id', $this->id)
                    ->orWhere(function ($q) {
                        $q->whereNull('sumber_id')
                          ->where('reference_type', Penggajian::class)
                          ->whereIn('reference_id', function ($sub) {
                              $sub->select('id')->from('penggajian')->where('guru_id', $this->id);
                          });
                    });
            })
            ->sum('nominal');
    }

    public function pengajaran()
    {
        return $this->hasMany(Pengajaran::class, 'guru_id');
    }

    public function sesiKelas()
    {
        return $this->hasManyThrough(
            SesiKelas::class,
            Pengajaran::class,
            'guru_id',
            'pengajaran_id',
            'id',
            'id'
        );
    }

    public function getRekapGajiAttribute()
    {
        $penggajians = Penggajian::whereHas('absensiGuru.sesiKelas', function ($q) {
            $q->whereHas('pengajaran', function ($innerQ) {
                $innerQ->where('guru_id', $this->id);
            });
        })->where('status_pembayaran', 'paid')->get();

        $rekap = $penggajians->groupBy(function ($penggajian) {
            return \Carbon\Carbon::parse($penggajian->tanggal_pembayaran)->format('F Y');
        })->map(function ($group) {
            return $group->sum('nominal');
        });

        $result = [];
        foreach ($rekap as $month => $total) {
            $result[] = ['bulan' => $month, 'total' => $total];
        }

        return $result;
    }
}
