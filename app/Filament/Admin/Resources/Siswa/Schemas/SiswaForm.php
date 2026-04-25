<?php

namespace App\Filament\Admin\Resources\Siswa\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use App\Services\FinanceService;
use App\Models\Kelas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                DatePicker::make('tanggal_lahir')
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('no_hp')
                    ->required(),
                
                Select::make('jenjang')
                    ->options(function () {
                        return \App\Models\Jenjang::pluck('nama', 'nama');
                    })
                    ->required()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set) {
                        $set('program', null);
                        $set('jumlah_mapel', null);
                    }),

                Select::make('program')
                    ->options(function (Get $get) {
                        return \App\Models\Program::pluck('nama', 'nama');
                    })
                    ->required()
                    ->disabled(fn (Get $get) => !$get('jenjang'))
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $set('jumlah_mapel', null);
                    }),

                TextInput::make('jumlah_mapel')
                    ->label('Jumlah Mata Pelajaran')
                    ->numeric()
                    ->minValue(1)
                    ->required(fn (Get $get) => $get('jenjang') === 'SMA')
                    ->visible(fn (Get $get) => $get('jenjang') === 'SMA')
                    ->live(),

                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->live(),
                
                DatePicker::make('tanggal_daftar')
                    ->required(),

                Placeholder::make('finance_preview')
                    ->label('Rincian Biaya')
                    ->content(function (Get $get) {
                        $program = $get('program');
                        $jenjang = $get('jenjang');
                        $kelasId = $get('kelas_id');
                        $jumlahMapel = (int) $get('jumlah_mapel');
                        
                        if (!$program || !$jenjang) return 'Pilih jenjang dan program untuk melihat rincian biaya.';

                        if ($jenjang === 'SMA' && !$jumlahMapel) return 'Masukkan jumlah mata pelajaran untuk SMA.';

                        $financeService = new FinanceService();
                        $subjectCount = $jumlahMapel;
                        
                        if ($jenjang !== 'SMA' && $kelasId) {
                            $kelas = Kelas::find($kelasId);
                            $subjectCount = $kelas ? $kelas->pengajaran()->count() : 0;
                        }

                        $spp = $financeService->calculateSpp($program, $jenjang, $subjectCount);
                        $registration = $financeService->getRegistrationFee();

                        return sprintf(
                            "Pendaftaran: Rp %s\nSPP: Rp %s / bulan",
                            number_format($registration, 0, ',', '.'),
                            number_format($spp, 0, ',', '.')
                        );
                    })
                    ->columnSpanFull(),
            ]);
    }

}
