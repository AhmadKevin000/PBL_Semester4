<?php

namespace App\Filament\Admin\Resources\Rapor\Tables;

use App\Models\Kelas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RaporTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => "Semester {$state}"),

                TextColumn::make('pengajaran.mapel.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 90 => 'success',
                        $state >= 75 => 'info',
                        $state >= 60 => 'warning',
                        default      => 'danger',
                    }),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->placeholder('-'),
            ])
            ->defaultSort('siswa.nama')
            ->filters([
                SelectFilter::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->label('Filter Siswa')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->label('Filter Semester'),

                SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options(
                        Kelas::pluck('nama_kelas', 'id')->toArray()
                    )
                    ->query(function ($query, $data) {
                        if (filled($data['value'])) {
                            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']));
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        // Export semua nilai rapor siswa pada semester yang sama
                        $siswa    = $record->siswa;
                        $semester = $record->semester;

                        $rapors = \App\Models\Rapor::with('pengajaran.mapel')
                            ->where('siswa_id', $record->siswa_id)
                            ->where('semester', $semester)
                            ->get();

                        return response()->streamDownload(function () use ($siswa, $semester, $rapors) {
                            echo \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.rapor', [
                                'siswa'    => $siswa,
                                'semester' => $semester,
                                'rapor'   => $rapors,
                            ])->output();
                        }, "rapor-{$siswa->nama}-sem{$semester}.pdf");
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

}
