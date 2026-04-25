<?php

namespace App\Filament\Admin\Resources\AbsensiSiswa\Schemas;

use Filament\Schemas\Schema;

class AbsensiSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Placeholder::make('info')
                    ->label('Manual Input Dinonaktifkan')
                    ->content('Gunakan tombol "Input Absensi" pada daftar tabel untuk mengisi absensi siswa.'),
            ]);
    }

}
