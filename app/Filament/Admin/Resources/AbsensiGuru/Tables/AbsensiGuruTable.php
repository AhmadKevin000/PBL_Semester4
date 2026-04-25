<?php

namespace App\Filament\Admin\Resources\AbsensiGuru\Tables;

use App\Models\SesiKelas;
use App\Models\AbsensiGuru;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class AbsensiGuruTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('pengajaran.guru.nama')
                    ->label('Guru')
                    ->searchable(),
                TextColumn::make('pengajaran.mapel.nama')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jadwalSesi.jam_mulai')
                    ->label('Waktu')
                    ->getStateUsing(function (?SesiKelas $record) {
                        return $record ? "{$record->jadwalSesi->jam_mulai} - {$record->jadwalSesi->jam_selesai}" : "-";
                    }),
                TextColumn::make('absensiGuru.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'ongoing' => 'Ongoing',
                        'selesai' => 'Selesai',
                        default => 'Belum Mulai',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'ongoing' => 'success',
                        'selesai' => 'gray',
                        default => 'warning',
                    }),
                TextColumn::make('absensiGuru.jumlah_siswa_hadir')
                    ->label('Siswa Hadir')
                    ->badge()
                    ->color('info')
                    ->visible(fn (?SesiKelas $record) => $record?->absensiGuru?->status === 'selesai'),
            ])
            ->actions([
                // CHECK-IN ACTION
                \Filament\Actions\Action::make('check_in')
                    ->label('Check-In')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn (?SesiKelas $record) => $record && !$record->absensiGuru)
                    ->action(function (SesiKelas $record) {
                        $pengajaran = $record->pengajaran;
                        
                        AbsensiGuru::create([
                            'sesi_kelas_id' => $record->id,
                            'guru_id'    => $pengajaran?->guru_id,
                            'kelas_id'   => $pengajaran?->kelas_id,
                            'mapel_id'   => $pengajaran?->mapel_id,
                            'check_in'   => now(),
                            'status'     => 'ongoing',
                        ]);
                        Notification::make()->title('Check-in Berhasil')->success()->send();
                    }),

                // CHECK-OUT ACTION
                \Filament\Actions\Action::make('check_out')
                    ->label('Check-Out')
                    ->icon('heroicon-o-stop')
                    ->color('danger')
                    ->visible(fn (?SesiKelas $record) => $record?->absensiGuru?->status === 'ongoing')
                    ->requiresConfirmation()
                    ->modalDescription('Pastikan absensi siswa sudah diisi sebelum check-out.')
                    ->action(function (SesiKelas $record) {
                        $absensiGuru = $record->absensiGuru;
                        $hasAbsensi = $record->absensiSiswa()->exists();
                        
                        if (!$hasAbsensi) {
                            Notification::make()->title('Gagal: Absensi siswa belum diisi!')->danger()->send();
                            return;
                        }

                        if ($absensiGuru) {
                            $absensiGuru->update([
                                'check_out' => now(),
                                'status' => 'selesai',
                            ]);
                        }

                        Notification::make()->title('Check-out Berhasil, Sesi Selesai')->success()->send();
                    }),
                
                \Filament\Actions\ViewAction::make(),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('sync_today')
                    ->label('Sync Jadwal Hari Ini')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalDescription('Cari dan buat otomatis sesi absen hari ini berdasarkan jadwal yang sama persis 7 hari lalu.')
                    ->action(function () {
                        $targetDate = now()->format('Y-m-d');
                        \Illuminate\Support\Facades\Artisan::call('app:generate-daily-sessions', [
                            '--date' => $targetDate
                        ]);
                        Notification::make()->title('Sinkronisasi Jadwal Selesai!')->success()->send();
                    })
            ]);
    }

}
