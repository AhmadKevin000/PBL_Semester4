<?php

namespace App\Filament\Admin\Resources\JadwalSesi\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Kelas;
use Filament\Notifications\Notification;

class KelasRelationManager extends RelationManager
{
    protected static string $relationship = 'pengajaran';

    protected static ?string $title = 'Jadwal Pengajaran';
    
    protected static ?string $recordTitleAttribute = 'id';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Sesi')
                    ->required(),
                Forms\Components\Select::make('semester')
                    ->options([
                        'Semester 1' => 'Semester 1',
                        'Semester 2' => 'Semester 2',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('guru.nama')->label('Guru')->sortable(),
                Tables\Columns\TextColumn::make('mapel.nama')->label('Mata Pelajaran')->sortable(),
                Tables\Columns\TextColumn::make('kelas.jumlah_siswa')->label('Jumlah Siswa')->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->label('Edit'),
                \Filament\Actions\DetachAction::make()
                    ->label('Delete')
                    ->modalHeading('Hapus Jadwal (Lepas Kelas)')
                    ->button()
                    ->color('danger'),
            ]);
    }

}
