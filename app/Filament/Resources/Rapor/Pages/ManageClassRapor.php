<?php

namespace App\Filament\Resources\Rapor\Pages;

use App\Filament\Resources\Rapor\RaporResource;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class ManageClassRapor extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = RaporResource::class;

    protected string $view = 'filament.resources.rapor.pages.manage-class-rapor';

    public function getSubheading(): ?string
    {
        return 'Pilih siswa untuk melakukan penginputan nilai rapor.';
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }

    public Kelas $record;

    public function mount(Kelas $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return "Rapor Kelas: {$this->record->nama_kelas}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Siswa::query()->where('kelas_id', $this->record->id))
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rapor_count')
                    ->label('Total Mata Pelajaran Terinput')
                    ->counts('rapor')
                    ->description('Berdasarkan semua semester'),
            ])
            ->filters([
                SelectFilter::make('semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->default('1')
                    ->query(fn ($query) => $query),
            ])
            ->recordActions([
                Action::make('input_nilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn (Siswa $siswa) => RaporResource::getUrl('input', [
                        'record' => $this->record,
                        'siswa' => $siswa,
                        'semester' => $this->tableFilters['semester']['value'] ?? '1',
                    ])),
                Action::make('cetak_pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn (Siswa $siswa) => route('rapor.pdf', [
                        'siswa' => $siswa,
                        'semester' => $this->tableFilters['semester']['value'] ?? '1',
                    ]))
                    ->openUrlInNewTab(),
            ]);
    }

}
