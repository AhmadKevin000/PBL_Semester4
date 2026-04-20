<?php

namespace App\Filament\Resources\Cashflow\Pages;

use App\Filament\Resources\Cashflow\CashflowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCashflow extends EditRecord
{
    protected static string $resource = CashflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

}
