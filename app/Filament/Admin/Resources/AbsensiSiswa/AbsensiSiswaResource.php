<?php

namespace App\Filament\Admin\Resources\AbsensiSiswa;

use App\Filament\Admin\Resources\AbsensiSiswa\Pages\CreateAbsensiSiswa;
use App\Filament\Admin\Resources\AbsensiSiswa\Pages\EditAbsensiSiswa;
use App\Filament\Admin\Resources\AbsensiSiswa\Pages\ListAbsensiSiswa;
use App\Filament\Admin\Resources\AbsensiSiswa\Schemas\AbsensiSiswaForm;
use App\Filament\Admin\Resources\AbsensiSiswa\Tables\AbsensiSiswaTable;
use App\Models\SesiKelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AbsensiSiswaResource extends Resource
{
    // Point base model to SesiKelas to list sessions that need student absensi
    protected static ?string $model = SesiKelas::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Absensi Siswa';
    protected static ?string $pluralLabel = 'Absensi Siswa';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function canCreate(): bool
    {
        return false;
    }

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AbsensiSiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AbsensiSiswaTable::configure($table);
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
            'index' => ListAbsensiSiswa::route('/'),
            'create' => CreateAbsensiSiswa::route('/create'),
            'edit' => EditAbsensiSiswa::route('/{record}/edit'),
        ];
    }

}
