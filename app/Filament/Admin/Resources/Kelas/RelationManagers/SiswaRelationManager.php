<?php

namespace App\Filament\Admin\Resources\Kelas\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use App\Filament\Admin\Resources\Siswa\Schemas\SiswaForm;

class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';

    protected static ?string $inverseRelationship = 'kelas';

    protected static ?string $title = 'Daftar Siswa';

    protected static ?string $recordTitleAttribute = 'nama';
    
    public function isReadOnly(): bool
    {
        return false;
    }

    public function canCreate(): bool
    {
        return true;
    }

    public function canAssociate(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('jenjang')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('program')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No HP')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\AssociateAction::make()
                    ->label('Pilih Siswa')
                    ->modalHeading('Pilih Siswa yang Sudah Ada')
                    ->modalSubmitActionLabel('Tambahkan')
                    ->recordSelectSearchColumns(['nama'])
                    ->preloadRecordSelect()
                    ->multiple()
                    ->button()
                    ->color('primary'),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DissociateAction::make()
                    ->label('Hapus dari Kelas'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DissociateBulkAction::make()
                        ->label('Hapus dari Kelas'),
                ]),
            ]);
    }

}
