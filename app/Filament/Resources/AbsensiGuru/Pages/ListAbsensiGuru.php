<?php

namespace App\Filament\Resources\AbsensiGuru\Pages;

use App\Filament\Resources\AbsensiGuru\AbsensiGuruResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListAbsensiGuru extends ListRecords
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make()
                ->label('Semua'),
            'aktif' => Tab::make()
                ->label('Aktif / Belum Selesai')
                ->modifyQueryUsing(fn ($query) => $query->whereDoesntHave('absensiGuru')
                    ->orWhereHas('absensiGuru', fn($q) => $q->where('status', 'ongoing'))
                ),
            'riwayat' => Tab::make()
                ->label('Riwayat Selesai')
                ->modifyQueryUsing(fn ($query) => $query->whereHas('absensiGuru', fn($q) => $q->where('status', 'selesai'))),
        ];
    }

}
