<?php

namespace App\Filament\Resources\TarifGaji;

use App\Filament\Resources\TarifGaji\Pages\CreateTarifGaji;
use App\Filament\Resources\TarifGaji\Pages\EditTarifGaji;
use App\Filament\Resources\TarifGaji\Pages\ListTarifGaji;
use App\Filament\Resources\TarifGaji\Schemas\TarifGajiForm;
use App\Filament\Resources\TarifGaji\Tables\TarifGajiTable;
use App\Models\TarifGaji;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TarifGajiResource extends Resource
{
    protected static ?string $model = TarifGaji::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Tarif Gaji';
    protected static ?string $pluralLabel = 'Tarif Gaji';

    public static function form(Schema $schema): Schema
    {
        return TarifGajiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TarifGajiTable::configure($table);
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
            'index' => ListTarifGaji::route('/'),
            'create' => CreateTarifGaji::route('/create'),
            'edit' => EditTarifGaji::route('/{record}/edit'),
        ];
    }

}
