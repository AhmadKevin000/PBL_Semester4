<?php

namespace App\Filament\Admin\Resources\Rapor\Pages;

use App\Filament\Admin\Resources\Rapor\RaporResource;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\Pengajaran;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class InputSiswaRapor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = RaporResource::class;

    protected string $view = 'filament.resources.rapor.pages.input-siswa-rapor';

    public Kelas $record;
    public Siswa $siswa;
    public string $semester;

    public ?array $data = [];

    public function getSubheading(): ?string
    {
        return "Kelas: {$this->record->nama_kelas} | Semester: {$this->semester}";
    }

    public static function getNavigationLabel(): string
    {
        return 'Input Rapor';
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }

    public function mount(Kelas $record, Siswa $siswa, string $semester): void
    {
        $this->record = $record;
        $this->siswa = $siswa;
        $this->semester = $semester;

        $this->form->fill($this->loadGrades());
    }

    protected function loadGrades(): array
    {
        $grades = [];
        $pengajaran = Pengajaran::where('kelas_id', $this->record->id)->with('mapel', 'guru')->get();

        foreach ($pengajaran as $item) {
            $rapor = Rapor::where('siswa_id', $this->siswa->id)
                ->where('pengajaran_id', $item->id)
                ->where('semester', $this->semester)
                ->first();

            $grades["nilai_{$item->id}"] = $rapor?->nilai;
            $grades["ket_{$item->id}"] = $rapor?->keterangan;
        }

        return $grades;
    }

    public function form(Schema $form): Schema
    {
        $pengajaran = Pengajaran::where('kelas_id', $this->record->id)->with('mapel', 'guru')->get();
        $components = [];

        foreach ($pengajaran as $item) {
            $subjectName = $item->mapel?->nama ?? 'Unknown Mapel';
            $teacherName = $item->guru?->nama ?? 'Unknown Guru';

            $components[] = Section::make("{$subjectName} (Guru: {$teacherName})")
                ->icon('heroicon-o-book-open')
                ->collapsible()
                ->compact()
                ->columns(2)
                ->schema([
                    TextInput::make("nilai_{$item->id}")
                        ->label('Nilai')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                        ->placeholder('0-100'),
                    Textarea::make("ket_{$item->id}")
                        ->label('Keterangan')
                        ->rows(1)
                        ->placeholder('Opsional'),
                ]);
        }

        return $form
            ->components([
                Grid::make(['default' => 1, 'sm' => 2, '2xl' => 3])
                    ->schema($components),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $pengajaran = Pengajaran::where('kelas_id', $this->record->id)->get();

        foreach ($pengajaran as $item) {
            $nilaiKey = "nilai_{$item->id}";
            $ketKey = "ket_{$item->id}";

            if (isset($data[$nilaiKey])) {
                Rapor::updateOrCreate(
                    [
                        'siswa_id' => $this->siswa->id,
                        'pengajaran_id' => $item->id,
                        'semester' => $this->semester,
                    ],
                    [
                        'nilai' => $data[$nilaiKey],
                        'keterangan' => $data[$ketKey] ?? null,
                    ]
                );
            }
        }

        Notification::make()
            ->title('Nilai Berhasil Disimpan')
            ->success()
            ->send();
    }

    public function saveAndNext(): void
    {
        $this->save();

        $nextSiswa = Siswa::where('kelas_id', $this->record->id)
            ->where('id', '>', $this->siswa->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($nextSiswa) {
            $this->redirect(RaporResource::getUrl('input', [
                'record' => $this->record,
                'siswa' => $nextSiswa,
                'semester' => $this->semester,
            ]));
        } else {
            Notification::make()
                ->title('Semua siswa di kelas ini telah selesai diinput.')
                ->info()
                ->send();
            
            $this->redirect(RaporResource::getUrl('manage', ['record' => $this->record]));
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Nilai')
                ->submit('save')
                ->color('primary'),
            Action::make('saveAndNext')
                ->label('Simpan & Lanjut Siswa Berikutnya')
                ->action('saveAndNext')
                ->color('success'),
            Action::make('reset')
                ->label('Reset Form')
                ->color('gray')
                ->outlined()
                ->action(fn () => $this->form->fill($this->loadGrades())),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali ke Daftar Siswa')
                ->color('gray')
                ->outlined()
                ->icon('heroicon-m-arrow-left')
                ->url(RaporResource::getUrl('manage', ['record' => $this->record])),
        ];
    }

}
