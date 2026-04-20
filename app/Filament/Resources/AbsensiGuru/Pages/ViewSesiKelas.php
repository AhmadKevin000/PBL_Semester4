<?php

namespace App\Filament\Resources\AbsensiGuru\Pages;

use App\Filament\Resources\AbsensiGuru\AbsensiGuruResource;
use App\Models\SesiKelas;
use App\Models\AbsensiGuru;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSesiKelas extends ViewRecord
{
    protected static string $resource = AbsensiGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('check_in')
                ->label('Check-In')
                ->icon('heroicon-o-play')
                ->color('success')
                ->visible(fn (SesiKelas $record) => !$record->absensiGuru)
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

            \Filament\Actions\Action::make('check_out')
                ->label('Check-Out')
                ->icon('heroicon-o-stop')
                ->color('danger')
                ->visible(fn (SesiKelas $record) => $record->absensiGuru?->status === 'ongoing')
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
                        // Observer akan generate Penggajian & Cashflow
                    }

                    Notification::make()->title('Check-out Berhasil, Sesi Selesai')->success()->send();
                }),
        ];
    }

}
