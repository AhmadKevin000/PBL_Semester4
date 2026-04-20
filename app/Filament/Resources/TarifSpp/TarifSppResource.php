<?php

namespace App\Filament\Resources\TarifSpp;

use App\Filament\Resources\TarifSpp\Pages\CreateTarifSpp;
use App\Filament\Resources\TarifSpp\Pages\EditTarifSpp;
use App\Filament\Resources\TarifSpp\Pages\ListTarifSpp;
use App\Filament\Resources\TarifSpp\Schemas\TarifSppForm;
use App\Filament\Resources\TarifSpp\Tables\TarifSppTable;
use App\Models\TarifSpp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TarifSppResource extends Resource
{
    protected static ?string $model = TarifSpp::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Tarif SPP';
    protected static ?string $pluralLabel = 'Tarif SPP';

    public static function form(Schema $schema): Schema
    {
        return TarifSppForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TarifSppTable::configure($table);
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
            'index' => ListTarifSpp::route('/'),
            'create' => CreateTarifSpp::route('/create'),
            'edit' => EditTarifSpp::route('/{record}/edit'),
        ];
    }

}
