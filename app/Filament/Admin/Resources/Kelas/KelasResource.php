<?php

namespace App\Filament\Admin\Resources\Kelas;

use App\Filament\Admin\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Admin\Resources\Kelas\Pages\EditKelas;
use App\Filament\Admin\Resources\Kelas\Pages\ListKelas;
use App\Filament\Admin\Resources\Kelas\Pages\ViewKelas;
use App\Filament\Admin\Resources\Kelas\Schemas\KelasForm;
use App\Filament\Admin\Resources\Kelas\Tables\KelasTable;
use App\Models\Kelas;
use BackedEnum;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelas')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nama_kelas')
                            ->label('Nama Kelas')
                            ->weight('bold'),
                        TextEntry::make('jenjang')
                            ->label('Jenjang')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('siswa_count')
                            ->label('Jumlah Siswa')
                            ->state(fn ($record) => $record->siswa()->count())
                            ->suffix(' Siswa')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Daftar Pengajaran')
                    ->schema([
                        RepeatableEntry::make('pengajaran')
                            ->label('')
                            ->schema([
                                TextEntry::make('guru.nama')
                                    ->label('Guru')
                                    ->icon('heroicon-m-user'),
                                TextEntry::make('mapel.nama')
                                    ->label('Mata Pelajaran')
                                    ->icon('heroicon-m-book-open'),
                            ])
                            ->columns(2)
                            ->grid(2),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\Kelas\RelationManagers\SiswaRelationManager::class,
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
            'index' => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'view' => ViewKelas::route('/{record}'),
            'edit' => EditKelas::route('/{record}/edit'),
        ];
    }

}
