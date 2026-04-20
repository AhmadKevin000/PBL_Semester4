<?php

namespace App\Filament\Resources\AbsensiGuru;

use App\Filament\Resources\AbsensiGuru\Pages\CreateAbsensiGuru;
use App\Filament\Resources\AbsensiGuru\Pages\EditAbsensiGuru;
use App\Filament\Resources\AbsensiGuru\Pages\ListAbsensiGuru;
use App\Filament\Resources\AbsensiGuru\Pages\ViewSesiKelas;
use App\Filament\Resources\AbsensiGuru\Schemas\AbsensiGuruForm;
use App\Filament\Resources\AbsensiGuru\Tables\AbsensiGuruTable;
use App\Models\SesiKelas;
use App\Models\AbsensiGuru;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AbsensiGuruResource extends Resource
{
    protected static ?string $model = SesiKelas::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Absensi Guru';
    protected static ?string $pluralLabel = 'Absensi Guru';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getRecordTitle(?\Illuminate\Database\Eloquent\Model $record): ?string
    {
        return $record ? "Absensi: {$record->kelas->nama_kelas} ({$record->tanggal->format('d M Y')})" : 'Absensi';
    }

    public static function form(Schema $schema): Schema
    {
        return AbsensiGuruForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AbsensiGuruTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Sesi')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('kelas.nama_kelas')
                            ->label('Kelas'),
                        \Filament\Infolists\Components\TextEntry::make('kelas.primary_teaching.guru.nama')
                            ->label('Guru'),
                        \Filament\Infolists\Components\TextEntry::make('kelas.primary_teaching.mapel.nama')
                            ->label('Mata Pelajaran'),
                        \Filament\Infolists\Components\TextEntry::make('tanggal')
                            ->date()
                            ->label('Tanggal'),
                        \Filament\Infolists\Components\TextEntry::make('jadwalSesi.jam_mulai')
                            ->label('Mulai'),
                        \Filament\Infolists\Components\TextEntry::make('jadwalSesi.jam_selesai')
                            ->label('Selesai'),
                        \Filament\Infolists\Components\TextEntry::make('absensiGuru.status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'ongoing' => 'warning',
                                'selesai' => 'success',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('absensiGuru.penggajian.nominal')
                            ->label('Gaji Sesi')
                            ->money('IDR')
                            ->placeholder('Belum selesai/Dihitung'),
                    ])->columns(2),
                \Filament\Schemas\Components\Section::make('Daftar Kehadiran Siswa')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('absensiSiswa')
                            ->label('')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('siswa.nama')
                                    ->label('Nama Siswa'),
                                \Filament\Infolists\Components\TextEntry::make('status')
                                    ->label('Status Kehadiran')
                                    ->badge()
                                    ->color(fn (string $state): string => match (strtolower($state)) {
                                        'hadir' => 'success',
                                        'izin' => 'warning',
                                        default => 'danger',
                                        'alpha' => 'danger',
                                    }),
                            ])
                            ->columns(2),
                    ])
                    ->visible(fn ($record) => $record && $record->absensiSiswa()->exists()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAbsensiGuru::route('/'),
            'create' => CreateAbsensiGuru::route('/create'),
            'view' => ViewSesiKelas::route('/{record}'),
            'edit' => EditAbsensiGuru::route('/{record}/edit'),
        ];
    }
}
