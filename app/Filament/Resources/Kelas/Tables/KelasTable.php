<?php

namespace App\Filament\Resources\Kelas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nama_kelas', 'asc')
            ->columns([
                TextColumn::make('nama_kelas')
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
                    })
                    ->sortable(),
                TextColumn::make('siswa_count')
                    ->label('Jumlah Siswa')
                    ->counts('siswa')
                    ->sortable(),
                TextColumn::make('pengajaran_list')
                    ->label('Daftar Pengajaran')
                    ->state(function ($record) {
                        return $record->pengajaran->map(function ($teaching) {
                            $guru = $teaching->guru ? $teaching->guru->nama : '-';
                            $mapel = $teaching->mapel ? $teaching->mapel->nama : '-';
                            return $guru . ' - ' . $mapel;
                        })->join(', ');
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('jenjang')
                    ->options([
                        'Pre School' => 'Pre School',
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], function ($query, $jenjang) {
                            $query->whereHas('pengajaran.mapel.jenjang', function ($q) use ($jenjang) {
                                $q->where('nama', $jenjang);
                            });
                        });
                    }),
                \Filament\Tables\Filters\SelectFilter::make('guru_id')
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'], function ($query, $guruId) {
                            $query->whereHas('pengajaran', function ($q) use ($guruId) {
                                $q->where('guru_id', $guruId);
                            });
                        });
                    })
                    ->options(\App\Models\Guru::pluck('nama', 'id'))
                    ->label('Filter Guru'),
            ])
            ->recordActions([
                ViewAction::make()->label('Detail'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
