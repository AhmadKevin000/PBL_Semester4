<?php

namespace App\Filament\Admin\Resources\Rapor\Schemas;

use App\Models\Rapor;
use App\Models\Siswa;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RaporForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Informasi Rapor')
                    ->columns(2)
                    ->schema([
                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->relationship('siswa', 'nama')
                            ->searchable()
                            ->preload()
                            ->optionsLimit(5)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                self::loadMapel($state, $get('semester'), $set);
                            }),

                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                '1' => 'Semester 1',
                                '2' => 'Semester 2',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                self::loadMapel($get('siswa_id'), $state, $set);
                            }),
                    ]),

                Section::make('Nilai Mata Pelajaran')
                    ->description(fn (Get $get) => $get('siswa_id') && $get('semester')
                        ? 'Input nilai untuk setiap mata pelajaran di bawah ini.'
                        : 'Pilih siswa dan semester terlebih dahulu untuk memuat daftar mata pelajaran.')
                    ->schema([
                        Repeater::make('nilai_mapel')
                            ->label('')
                            ->schema([
                                Hidden::make('pengajaran_id'),

                                TextInput::make('nama_mapel')
                                    ->label('Mata Pelajaran')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(1),

                                TextInput::make('nilai')
                                    ->label('Nilai')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('/100')
                                    ->columnSpan(1),

                                TextInput::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Opsional')
                                    ->columnSpan(1),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(3)
                            ->columnSpanFull()
                            ->default([]),
                    ]),
            ]);
    }

    /**
     * Auto-load mata pelajaran dari pengajaran berdasarkan kelas siswa.
     * Juga load nilai yang sudah ada jika sedang edit.
     */
    public static function loadMapel(?int $siswaId, ?string $semester, Set $set): void
    {
        if (! $siswaId || ! $semester) {
            $set('nilai_mapel', []);
            return;
        }

        $siswa = Siswa::with(['kelas.pengajaran.mapel'])->find($siswaId);

        if (! $siswa || ! $siswa->kelas) {
            $set('nilai_mapel', []);
            return;
        }

        $subjects = $siswa->kelas->pengajaran
            ->filter(fn ($item) => $item->mapel !== null)
            ->unique('id')
            ->map(function ($item) use ($siswaId, $semester) {
                $existingRapor = Rapor::where('siswa_id', $siswaId)
                    ->where('pengajaran_id', $item->id)
                    ->where('semester', $semester)
                    ->first();

                return [
                    'pengajaran_id' => $item->id,
                    'nama_mapel'    => $item->mapel->nama,
                    'nilai'         => $existingRapor?->nilai,
                    'keterangan'    => $existingRapor?->keterangan,
                ];
            })
            ->values()
            ->toArray();

        $set('nilai_mapel', $subjects);
    }

}
