<?php

namespace App\Filament\Resources\Mapel\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MapelTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('jenjang.nama', 'asc')
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenjang.nama')
                    ->label('Jenjang')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Pre School' => 'warning',
                        'SD' => 'danger',
                        'SMP' => 'info',
                        'SMA' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('program.nama')
                    ->label('Program')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
