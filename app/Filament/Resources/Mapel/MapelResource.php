<?php

namespace App\Filament\Resources\Mapel;

use App\Filament\Resources\Mapel\Pages\CreateMapel;
use App\Filament\Resources\Mapel\Pages\EditMapel;
use App\Filament\Resources\Mapel\Pages\ListMapel;
use App\Filament\Resources\Mapel\Pages\ViewMapel;
use App\Filament\Resources\Mapel\Schemas\MapelForm;
use App\Filament\Resources\Mapel\Tables\MapelTable;
use App\Models\Mapel;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel = 'Mata Pelajaran';
    protected static ?string $pluralModelLabel = 'Mata Pelajaran';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return MapelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MapelTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Pelajaran')
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Mata Pelajaran'),
                        TextEntry::make('jenjang.nama')
                            ->label('Jenjang'),
                        TextEntry::make('program.nama')
                            ->label('Program'),
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime(),
                    ])->columns(2),
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
            'index' => ListMapel::route('/'),
            'create' => CreateMapel::route('/create'),
            'view' => ViewMapel::route('/{record}'),
            'edit' => EditMapel::route('/{record}/edit'),
        ];
    }
}
