<?php

namespace App\Filament\Resources\Rapor\Pages;

use App\Filament\Resources\Rapor\RaporResource;
use App\Models\Rapor;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class ViewRapor extends ViewRecord
{
    protected static string $resource = RaporResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        $record   = $this->record;
        $semester = $record->semester;

        // Load semua nilai rapor siswa pada semester yang ditampilkan
        $allRapors = Rapor::with('subject')
            ->where('siswa_id', $record->siswa_id)
            ->where('semester', $semester)
            ->get();

        $avgNilai = $allRapors->count() > 0
            ? round($allRapors->avg('nilai'), 1)
            : 0;

        $nilaiComponents = $allRapors->map(fn ($r) => Grid::make(4)->schema([
            TextEntry::make("mapel_{$r->id}")
                ->label('Mata Pelajaran')
                ->state($r->subject?->name ?? '-'),

            TextEntry::make("nilai_{$r->id}")
                ->label('Nilai')
                ->state($r->nilai)
                ->badge()
                ->color(match (true) {
                    $r->nilai >= 90 => 'success',
                    $r->nilai >= 75 => 'info',
                    $r->nilai >= 60 => 'warning',
                    default         => 'danger',
                }),

            TextEntry::make("grade_{$r->id}")
                ->label('Grade')
                ->state($r->grade)
                ->badge()
                ->color(match ($r->grade) {
                    'A'     => 'success',
                    'B'     => 'info',
                    'C'     => 'warning',
                    default => 'danger',
                }),

            TextEntry::make("ket_{$r->id}")
                ->label('Keterangan')
                ->state($r->keterangan ?? '-')
                ->placeholder('-'),
        ]))->toArray();

        return $schema->components([
            Section::make('Informasi Siswa')
                ->columns(3)
                ->schema([
                    TextEntry::make('siswa.nama')
                        ->label('Nama Siswa')
                        ->weight('bold'),

                    TextEntry::make('siswa.kelas.nama_kelas')
                        ->label('Kelas')
                        ->badge()
                        ->color('warning'),

                    TextEntry::make('semester')
                        ->label('Semester')
                        ->badge()
                        ->color('info')
                        ->formatStateUsing(fn ($state) => "Semester {$state}"),
                ]),

            Section::make("Daftar Nilai — Semester {$semester}")
                ->description("Rata-rata Nilai: {$avgNilai} | Total Mata Pelajaran: {$allRapors->count()}")
                ->schema($nilaiComponents),
        ]);
    }

}
