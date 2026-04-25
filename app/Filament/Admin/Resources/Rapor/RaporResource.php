<?php

namespace App\Filament\Admin\Resources\Rapor;

use App\Filament\Admin\Resources\Rapor\Pages\InputSiswaRapor;
use App\Filament\Admin\Resources\Rapor\Pages\ListRapor;
use App\Filament\Admin\Resources\Rapor\Pages\ManageClassRapor;
use App\Models\Kelas;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RaporResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Input Rapor';
    protected static ?string $pluralLabel = 'Input Rapor';
    protected static ?string $modelLabel = 'Rapor';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('siswa_count')
                    ->label('Jumlah Siswa')
                    ->counts('siswa')
                    ->sortable(),
                TextColumn::make('teacher_list')
                    ->label('Guru Pengajar')
                    ->wrap(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('manage_rapor')
                    ->label('Buka Kelas')
                    ->icon('heroicon-o-folder-open')
                    ->color('success')
                    ->url(fn (Kelas $record) => static::getUrl('manage', ['record' => $record])),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRapor::route('/'),
            'manage' => ManageClassRapor::route('/{record}/students'),
            'input'  => InputSiswaRapor::route('/{record}/students/{siswa}/{semester}/input'),
        ];
    }
}
