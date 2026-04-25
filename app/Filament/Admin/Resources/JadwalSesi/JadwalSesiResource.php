<?php

namespace App\Filament\Admin\Resources\JadwalSesi;

use App\Filament\Admin\Resources\JadwalSesi\Pages\CreateJadwalSesi;
use App\Filament\Admin\Resources\JadwalSesi\Pages\EditJadwalSesi;
use App\Filament\Admin\Resources\JadwalSesi\Pages\ListJadwalSesi;
use App\Filament\Admin\Resources\JadwalSesi\Pages\ViewJadwalSesi;
use App\Filament\Admin\Resources\JadwalSesi\Schemas\JadwalSesiForm;
use App\Filament\Admin\Resources\JadwalSesi\Tables\JadwalSesiTable;
use App\Models\JadwalSesi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class JadwalSesiResource extends Resource
{
    protected static ?string $model = JadwalSesi::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Manage Sesi';
    protected static ?string $pluralLabel = 'Manage Sesi';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record ? "Sesi: {$record->jam_mulai} - {$record->jam_selesai}" : 'Sesi';
    }

    public static function form(Schema $schema): Schema
    {
        return JadwalSesiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalSesiTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\JadwalSesi\RelationManagers\KelasRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return true;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJadwalSesi::route('/'),
            'view' => ViewJadwalSesi::route('/{record}'),
        ];
    }

}
