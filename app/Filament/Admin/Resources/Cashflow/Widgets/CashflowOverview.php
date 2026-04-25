<?php

namespace App\Filament\Admin\Resources\Cashflow\Widgets;

use App\Models\Cashflow;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class CashflowOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $income = Cashflow::where('type', 'income')->sum('nominal');
        $expense = Cashflow::where('type', 'expense')->sum('nominal');
        $balance = $income - $expense;

        return [
            Stat::make('Total Income', 'Rp ' . number_format($income, 0, ',', '.'))
                ->description('Total pemasukan masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Expense', 'Rp ' . number_format($expense, 0, ',', '.'))
                ->description('Total pengeluaran keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Net Balance', 'Rp ' . number_format($balance, 0, ',', '.'))
                ->description('Selisih pemasukan - pengeluaran')
                ->descriptionIcon('heroicon-m-scale')
                ->color($balance >= 0 ? 'success' : 'danger'),
        ];
    }

}
