<?php

namespace App\Filament\Resources\Guru\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Models\SesiKelas;

class JadwalSesiRelationManager extends RelationManager
{
    protected static string $relationship = 'sesiKelas';
    protected static ?string $title = 'Riwayat Sesi Mengajar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tanggal')
            ->columns([
                Tables\Columns\TextColumn::make('pengajaran.kelas.nama_kelas')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwalSesi.jam_mulai')
                    ->label('Waktu')
                    ->getStateUsing(function (SesiKelas $record) {
                        $start = $record->jadwalSesi->jam_mulai ? \Carbon\Carbon::parse($record->jadwalSesi->jam_mulai)->format('H:i') : '?';
                        $end = $record->jadwalSesi->jam_selesai ? \Carbon\Carbon::parse($record->jadwalSesi->jam_selesai)->format('H:i') : '?';
                        return "{$start} - {$end}";
                    }),
                Tables\Columns\TextColumn::make('absensiGuru.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'ongoing' => 'Ongoing',
                        'selesai' => 'Selesai',
                        default => 'Belum Mulai',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'ongoing' => 'success',
                        'selesai' => 'gray',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->url(fn (SesiKelas $record) => \App\Filament\Resources\AbsensiGuru\AbsensiGuruResource::getUrl('view', ['record' => $record->absensiGuru?->id ?? 0]))
                    ->visible(fn (SesiKelas $record) => $record->absensiGuru !== null),
                
                Actions\Action::make('check_in')
                    ->label('Check-In')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->url(fn (SesiKelas $record) => \App\Filament\Resources\AbsensiGuru\AbsensiGuruResource::getUrl('view', ['record' => $record->id ?? 0]))
                    ->visible(fn (SesiKelas $record) => $record->absensiGuru === null),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('tanggal', 'desc');
    }
}
