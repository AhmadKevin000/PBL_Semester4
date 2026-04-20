<?php

namespace App\Filament\Resources\Rapor\Pages;

use App\Filament\Resources\Rapor\RaporResource;
use App\Filament\Resources\Rapor\Schemas\RaporForm;
use App\Models\Rapor;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRapor extends EditRecord
{
    protected static string $resource = RaporResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Saat form dibuka untuk edit, siswa_id & semester sudah ada di record.
     * Kita inject 'nilai_mapel' agar Repeater terisi dengan nilai yang sudah ada.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $siswaId  = $data['siswa_id']  ?? null;
        $semester = $data['semester']  ?? null;

        if ($siswaId && $semester) {
            $siswa = \App\Models\Siswa::with(['kelas.pengajaran.mapel'])->find($siswaId);

            if ($siswa && $siswa->kelas) {
                $data['nilai_mapel'] = $siswa->kelas->pengajaran
                    ->filter(fn ($item) => $item->mapel !== null)
                    ->unique('id')
                    ->map(function ($item) use ($siswaId, $semester) {
                        $existing = Rapor::where('siswa_id', $siswaId)
                            ->where('pengajaran_id', $item->id)
                            ->where('semester', $semester)
                            ->first();

                        return [
                            'pengajaran_id' => $item->id,
                            'nama_mapel'    => $item->mapel->nama,
                            'nilai'         => $existing?->nilai,
                            'keterangan'    => $existing?->keterangan,
                        ];
                    })
                    ->values()
                    ->toArray();
            }
        }

        return $data;
    }

    /**
     * Override handleRecordUpdate: loop & updateOrCreate per mapel.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $nilaiMapel = $data['nilai_mapel'] ?? [];

        foreach ($nilaiMapel as $item) {
            Rapor::updateOrCreate(
                [
                    'siswa_id'      => $data['siswa_id'],
                    'pengajaran_id' => $item['pengajaran_id'],
                    'semester'      => $data['semester'],
                ],
                [
                    'nilai'      => $item['nilai'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            );
        }

        return $record->fresh();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
