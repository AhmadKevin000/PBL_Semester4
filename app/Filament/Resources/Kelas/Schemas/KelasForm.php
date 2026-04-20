<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use App\Models\Mapel;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kelas')
                    ->required(),
                Placeholder::make('jenjang_display')
                    ->label('Jenjang')
                    ->content(function (Get $get) {
                        $pengajaran = $get('pengajaran');
                        if (empty($pengajaran)) return '-';
                        foreach ($pengajaran as $item) {
                            if (!empty($item['mapel_id'])) {
                                $mapel = Mapel::find($item['mapel_id']);
                                if ($mapel) return $mapel->jenjang?->nama ?? '-';
                            }
                        }
                        return '-';
                    }),
                Placeholder::make('jumlah_siswa')
                    ->label('Jumlah Siswa')
                    ->content(fn ($record) => $record?->siswa()->count() ?? 0),
                TextInput::make('keterangan'),
                
                Repeater::make('pengajaran')
                    ->relationship('pengajaran')
                    ->schema([
                        Select::make('guru_id')
                            ->relationship('guru', 'nama')
                            ->label('Guru')
                            ->required(),
                        Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->required()
                            ->reactive() 
                            ->options(function (Get $get) {
                                $allPengajaran = $get('../../pengajaran') ?? [];
                                $targetJenjang = null;
                                
                                foreach ($allPengajaran as $item) {
                                    if (!empty($item['mapel_id'])) {
                                        $mapel = Mapel::find($item['mapel_id']);
                                        if ($mapel) {
                                            $targetJenjang = $mapel->jenjang_id;
                                            break;
                                        }
                                    }
                                }
                                
                                if ($targetJenjang) {
                                    return Mapel::where('jenjang_id', $targetJenjang)->pluck('nama', 'id');
                                }
                                return Mapel::pluck('nama', 'id');
                            }),
                    ])
                    ->live()
                    ->columns(2)
                    ->addActionLabel('Tambah Pengajaran')
                    ->minItems(1)
                    ->required()
                    ->columnSpanFull(),


            ]);
    }

}
