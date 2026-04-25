<?php

namespace App\Filament\Guru\Widgets;

use App\Models\Guru;
use App\Models\Penggajian;
use Filament\Widgets\Widget;

class GuruGajiWidget extends Widget
{
    protected string $view = 'filament.guru.widgets.guru-gaji-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 4;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();

        $rekapGaji    = collect();
        $totalDiterima = 0;
        $totalPending  = 0;

        if ($guru) {
            // Rekap gaji per bulan
            $penggajians = Penggajian::where('guru_id', $guru->id)
                ->orderByDesc('tanggal_pembayaran')
                ->limit(6)
                ->get();

            $rekapGaji = $penggajians->groupBy(function ($p) {
                return $p->tanggal_pembayaran
                    ? \Carbon\Carbon::parse($p->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                    : 'Belum Dibayar';
            })->map(function ($group, $bulan) {
                return [
                    'bulan'  => $bulan,
                    'total'  => $group->sum('nominal'),
                    'status' => $group->first()->status_pembayaran,
                    'count'  => $group->count(),
                ];
            })->values();

            $totalDiterima = Penggajian::where('guru_id', $guru->id)
                ->where('status_pembayaran', 'paid')
                ->sum('nominal');

            $totalPending = Penggajian::where('guru_id', $guru->id)
                ->where('status_pembayaran', '!=', 'paid')
                ->sum('nominal');
        }

        return [
            'rekapGaji'     => $rekapGaji,
            'totalDiterima' => $totalDiterima,
            'totalPending'  => $totalPending,
        ];
    }
}
