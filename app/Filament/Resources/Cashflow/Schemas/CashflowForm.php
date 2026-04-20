<?php

namespace App\Filament\Resources\Cashflow\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CashflowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(['income' => 'Pemasukan', 'expense' => 'Pengeluaran'])
                    ->required(),
                Select::make('kategori')
                    ->options([
                        'SPP' => 'S p p',
                        'pendaftaran' => 'Pendaftaran',
                        'gaji_guru' => 'Gaji Guru',
                        'operasional' => 'Operasional',
                    ])
                    ->required(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TextInput::make('reference_id'),
                Select::make('reference_type')
                    ->options([
                        \App\Models\Siswa::class => 'Siswa',
                        \App\Models\Penggajian::class => 'Penggajian (Gaji Guru)',
                        \App\Models\SesiKelas::class => 'Sesi Kelas',
                    ]),
                TextInput::make('source_snapshot')
                    ->label('Source Snapshot (Legacy Name)')
                    ->helperText('Otomatis terisi jika memilih referensi di atas'),
                TextInput::make('sumber_id')
                    ->numeric()
                    ->helperText('ID Sumber (Siswa/Guru) untuk laporan'),
            ]);
    }

}
