<?php

namespace App\Filament\Admin\Resources\AbsensiSiswa\Tables;

use App\Models\SesiKelas;
use App\Models\AbsensiSiswa;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class AbsensiSiswaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (Builder $query) => SesiKelas::whereHas('absensiGuru', fn($q) => $q->where('status', 'ongoing')))
            ->columns([
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas'),
                TextColumn::make('kelas.teacher_list')
                    ->label('Guru'),
                TextColumn::make('kelas.subject_list')
                    ->label('Mata Pelajaran'),
                TextColumn::make('tanggal')
                    ->date(),
                TextColumn::make('jadwalSesi.jam_mulai')
                    ->label('Mulai'),
                TextColumn::make('absensi_status')
                    ->label('Status Absensi')
                    ->state(fn ($record) => $record->absensiSiswa()->exists() ? 'Sudah Diisi' : 'Belum Diisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah Diisi' => 'success',
                        'Belum Diisi' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('input_absensi')
                    ->label('Isi Absensi')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->form(function (SesiKelas $record) {
                        $students = $record->kelas->siswa;
                        
                        $currentPresentIds = $record->absensiSiswa()
                            ->where('status', 'hadir')
                            ->pluck('siswa_id')
                            ->toArray();

                        return [
                            CheckboxList::make('present_students')
                                ->label('Siswa Hadir')
                                ->helperText('Pilih siswa yang hadir di sesi ini.')
                                ->options($students->pluck('nama', 'id'))
                                ->default(!empty($currentPresentIds) ? $currentPresentIds : $students->pluck('id')->toArray())
                                ->required(),
                        ];
                    })
                    ->action(function (SesiKelas $record, array $data) {
                        $record->absensiSiswa()->delete();

                        $allStudents = $record->kelas->siswa;
                        foreach ($allStudents as $siswa) {
                            $isPresent = in_array($siswa->id, $data['present_students']);
                            AbsensiSiswa::create([
                                'sesi_kelas_id' => $record->id,
                                'siswa_id' => $siswa->id,
                                'status' => $isPresent ? 'hadir' : 'tidak hadir',
                            ]);
                        }

                        Notification::make()->title('Absensi siswa berhasil disimpan')->success()->send();
                    }),
            ]);
    }

}
