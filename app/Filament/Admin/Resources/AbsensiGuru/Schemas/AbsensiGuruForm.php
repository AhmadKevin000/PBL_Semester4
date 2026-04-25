<?php

namespace App\Filament\Admin\Resources\AbsensiGuru\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Models\SesiKelas;

class AbsensiGuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sesi')
                    ->schema([
                        Placeholder::make('session_info')
                            ->label('Detail Sesi')
                            ->content(fn ($record) => $record?->sesiKelas 
                                ? "{$record->sesiKelas->jadwalSesi->nama} ({$record->sesiKelas->jadwalSesi->jam_mulai} - {$record->sesiKelas->jadwalSesi->jam_selesai}) - Kelas: {$record->sesiKelas->kelas->nama_kelas}"
                                : '-'),
                        
                        Placeholder::make('tanggal')
                            ->label('Tanggal Sesi')
                            ->content(fn ($record) => $record?->sesiKelas?->tanggal ? \Carbon\Carbon::parse($record->sesiKelas->tanggal)->format('d F Y') : '-'),
                    ])->columns(2),

                Section::make('Waktu Kehadiran Guru')
                    ->schema([
                        DateTimePicker::make('check_in')
                            ->label('Check-In')
                            ->disabled(),
                        DateTimePicker::make('check_out')
                            ->label('Check-Out')
                            ->disabled(),
                    ])->columns(2),

                Section::make('Hasil Absensi Siswa')
                    ->description('Daftar siswa yang hadir pada sesi ini.')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('absensiSiswa')
                            ->relationship('absensiSiswa')
                            ->label('')
                            ->schema([
                                Select::make('siswa_id')
                                    ->label('Nama Siswa')
                                    ->relationship('siswa', 'nama')
                                    ->disabled(),
                                Select::make('status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'tidak hadir' => 'Tidak Hadir',
                                        'izin' => 'Izin',
                                        'alpha' => 'Alpha',
                                    ])
                                    ->disabled(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ])
                    ->visible(fn ($record) => $record && $record->absensiSiswa()->exists()),
            ]);
    }

}
