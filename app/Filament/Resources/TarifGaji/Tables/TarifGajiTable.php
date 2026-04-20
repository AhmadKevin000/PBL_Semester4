<?php

namespace App\Filament\Resources\TarifGaji\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TarifGajiTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->sortable(),
                TextColumn::make('jenjang.nama')
                    ->label('Jenjang')
                    ->default('Semua Jenjang')
                    ->sortable(),
                TextColumn::make('status_guru')
                    ->label('Status Guru')
                    ->badge()
                    ->sortable(),
                TextColumn::make('rate')
                    ->label('Honor')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
