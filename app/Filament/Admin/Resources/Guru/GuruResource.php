<?php

namespace App\Filament\Admin\Resources\Guru;

use App\Filament\Admin\Resources\Guru\Pages\CreateGuru;
use App\Filament\Admin\Resources\Guru\Pages\EditGuru;
use App\Filament\Admin\Resources\Guru\Pages\ListGuru;
use App\Filament\Admin\Resources\Guru\Pages\ViewGuru;
use App\Filament\Admin\Resources\Guru\Schemas\GuruForm;
use App\Filament\Admin\Resources\Guru\Tables\GuruTable;
use App\Filament\Admin\Resources\Guru\RelationManagers\JadwalSesiRelationManager;
use App\Models\Guru;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Guru';
    protected static ?string $pluralLabel = 'Guru';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return GuruForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuruTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Kepegawaian')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('nama')
                            ->label('Nama Lengkap')
                            ->size(\Filament\Support\Enums\TextSize::Large)
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        \Filament\Infolists\Components\TextEntry::make('email')
                            ->label('Email Address')
                            ->icon('heroicon-m-envelope'),
                        \Filament\Infolists\Components\TextEntry::make('no_hp')
                            ->label('No. Handphone')
                            ->icon('heroicon-m-phone'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        \Filament\Infolists\Components\TextEntry::make('total_salary')
                            ->label('Total Pendapatan')
                            ->money('IDR')
                            ->color('success'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Rekap Gaji Per Bulan')
                    ->description('Ringkasan pendapatan yang telah cair (Sesi Selesai) per bulan.')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('rekap_gaji')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('bulan')
                                    ->label('Bulan')
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                                \Filament\Infolists\Components\TextEntry::make('total')
                                    ->label('Total Gaji')
                                    ->money('IDR')
                                    ->color('success')
                                    ->weight(\Filament\Support\Enums\FontWeight::SemiBold),
                            ])
                            ->columns(2)
                    ])
                    ->visible(fn ($record) => count($record->rekap_gaji) > 0),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            JadwalSesiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuru::route('/'),
            'create' => CreateGuru::route('/create'),
            'view' => ViewGuru::route('/{record}'),
            'edit' => EditGuru::route('/{record}/edit'),
        ];
    }

}
