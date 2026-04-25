<?php

namespace App\Filament\Admin\Resources\Siswa;

use App\Filament\Admin\Resources\Siswa\Pages\CreateSiswa;
use App\Filament\Admin\Resources\Siswa\Pages\EditSiswa;
use App\Filament\Admin\Resources\Siswa\Pages\ListSiswa;
use App\Filament\Admin\Resources\Siswa\Pages\ViewSiswa;
use App\Filament\Admin\Resources\Siswa\Schemas\SiswaForm;
use App\Filament\Admin\Resources\Siswa\Tables\SiswaTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $pluralLabel = 'Siswa';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswaTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Siswa')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nama')
                            ->weight('bold'),
                        TextEntry::make('no_hp')
                            ->copyable(),
                        TextEntry::make('alamat')
                            ->columnSpanFull(),
                        TextEntry::make('tanggal_lahir')
                            ->date(),
                        TextEntry::make('tanggal_daftar')
                            ->date(),
                    ]),
                
                Section::make('Program & Kelas')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('program')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('jenjang')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('jumlah_mapel')
                            ->label('Jumlah Mata Pelajaran')
                            ->visible(fn ($record) => $record->jenjang === 'SMA'),
                        TextEntry::make('kelas.nama_kelas')
                            ->label('Kelas')
                            ->placeholder('Belum masuk kelas'),
                    ]),

                Section::make('Keuangan')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('pendaftaran_fee')
                            ->label('Biaya Pendaftaran')
                            ->state(fn () => 'Rp 50.000')
                            ->color('success'),
                        TextEntry::make('spp_nominal')
                            ->label('SPP Per Bulan')
                            ->money('IDR', locale: 'id')
                            ->color('success')
                            ->weight('bold'),
                        TextEntry::make('spp_jatuh_tempo')
                            ->label('Jatuh Tempo Berikutnya')
                            ->date()
                            ->color('danger'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiswa::route('/'),
            'create' => CreateSiswa::route('/create'),
            'view' => ViewSiswa::route('/{record}'),
            'edit' => EditSiswa::route('/{record}/edit'),
        ];
    }
}
