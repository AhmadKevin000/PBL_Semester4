<?php

namespace App\Filament\Admin\Resources\Guru\Pages;

use App\Filament\Admin\Resources\Guru\GuruResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateGuru extends CreateRecord
{
    protected static string $resource = GuruResource::class;

    /**
     * Hapus field password dari data sebelum disimpan ke tabel guru,
     * karena field ini tidak ada di kolom tabel guru.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan password sementara di property agar bisa dipakai di afterCreate
        $this->guruPassword = $data['password'] ?? null;

        // Hapus field yang bukan kolom guru
        unset($data['password'], $data['password_confirmation']);

        return $data;
    }

    /**
     * Setelah guru berhasil disimpan, buat User dan hubungkan ke guru.
     */
    protected function afterCreate(): void
    {
        $guru = $this->record;

        // Buat akun user untuk guru
        $user = User::create([
            'name'     => $guru->nama,
            'email'    => $guru->email,
            'password' => Hash::make($this->guruPassword ?? 'password'),
            'role'     => 'guru',
        ]);

        // Hubungkan user ke guru
        $guru->update(['user_id' => $user->id]);
    }

    /** Temporary storage untuk password saat proses create */
    protected ?string $guruPassword = null;
}
