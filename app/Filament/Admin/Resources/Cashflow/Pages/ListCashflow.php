<?php

namespace App\Filament\Admin\Resources\Cashflow\Pages;

use App\Filament\Admin\Resources\Cashflow\CashflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Cashflow\Widgets\CashflowOverview;
use Filament\Schemas\Components\Tabs\Tab;

class ListCashflow extends ListRecords
{
    protected static string $resource = CashflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Cashflow'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CashflowOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'income' => Tab::make('Income')
                ->modifyQueryUsing(fn ($query) => $query->where('type', 'income')),
            'expense' => Tab::make('Expense')
                ->modifyQueryUsing(fn ($query) => $query->where('type', 'expense')),
        ];
    }

}
