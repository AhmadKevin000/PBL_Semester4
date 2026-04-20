<?php

namespace App\Filament\Resources\Siswa\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nama', 'asc')
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pre School' => 'warning',
                        'SD' => 'danger',
                        'SMP' => 'info',
                        'SMA' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('program')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('no_hp')
                    ->searchable(),
                TextColumn::make('tanggal_daftar')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('jenjang')
                    ->options([
                        'Pre School' => 'Pre School',
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('kelas.pengajaran.mapel.jenjang', function ($q) use ($data) {
                                $q->where('nama', $data['value']);
                            });
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('program')
                    ->options([
                        'Calistung' => 'Calistung',
                        'Reguler' => 'Reguler',
                        'TKA' => 'TKA',
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('kelas.pengajaran.mapel.program', function ($q) use ($data) {
                                $q->where('nama', $data['value']);
                            });
                        }
                    }),
                \Filament\Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Filter Kelas'),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
