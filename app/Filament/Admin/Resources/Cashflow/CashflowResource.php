<?php

namespace App\Filament\Admin\Resources\Cashflow;

use App\Filament\Admin\Resources\Cashflow\Pages\CreateCashflow;
use App\Filament\Admin\Resources\Cashflow\Pages\EditCashflow;
use App\Filament\Admin\Resources\Cashflow\Pages\ListCashflow;
use App\Filament\Admin\Resources\Cashflow\Schemas\CashflowForm;
use App\Filament\Admin\Resources\Cashflow\Tables\CashflowTable;
use App\Models\Cashflow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashflowResource extends Resource
{
    protected static ?string $model = Cashflow::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Cashflow';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Schema $schema): Schema
    {
        return CashflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashflowTable::configure($table);
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
            'index' => ListCashflow::route('/'),
            'create' => CreateCashflow::route('/create'),
            'edit' => EditCashflow::route('/{record}/edit'),
        ];
    }

}
