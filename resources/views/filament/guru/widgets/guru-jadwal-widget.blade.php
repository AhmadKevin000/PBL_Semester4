<x-filament-widgets::widget>
<style>
.gj-card {
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    background: #fff;
    height: 100%;
    display: flex; flex-direction: column;
}
.dark .gj-card {
    border-color: #374151;
    background: #1f2937;
}
.gj-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; gap: .625rem;
}
.dark .gj-header { border-bottom-color: #374151; }

.gj-icon {
    width: 2rem; height: 2rem; border-radius: .4rem;
    background: #d1fae5;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.dark .gj-icon { background: #064e3b; }

.gj-header-title {
    font-size: .875rem; font-weight: 600; margin: 0;
    color: #111827;
}
.dark .gj-header-title { color: #f9fafb; }

.gj-header-sub {
    font-size: .7rem; color: #6b7280; margin: 0;
}
.gj-body { padding: .875rem 1rem; flex: 1; }

/* empty state */
.gj-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 1.75rem 0; text-align: center;
}
.gj-empty-icon { font-size: 2rem; margin-bottom: .4rem; }
.gj-empty-text { font-size: .85rem; font-weight: 500; color: #4b5563; margin: 0; }
.dark .gj-empty-text { color: #9ca3af; }
.gj-empty-sub  { font-size: .75rem; color: #9ca3af; margin-top: .2rem; }

/* table */
.gj-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
.gj-table th {
    padding-bottom: .4rem; text-align: left;
    font-size: .68rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .04em;
    color: #6b7280;
}
.gj-table td {
    padding: .45rem .75rem .45rem 0;
    border-top: 1px solid #f3f4f6;
    color: #374151;
}
.dark .gj-table td { border-top-color: #374151; color: #d1d5db; }

.gj-badge-kelas {
    padding: 2px 7px; border-radius: 4px;
    background: #eff6ff; color: #1d4ed8;
    font-size: .68rem; font-weight: 500;
}
.dark .gj-badge-kelas { background: #1e3a8a33; color: #93c5fd; }

.gj-status-hadir {
    padding: 2px 7px; border-radius: 999px;
    background: #d1fae5; color: #065f46;
    font-size: .68rem; font-weight: 600; white-space: nowrap;
}
.dark .gj-status-hadir { background: #06403033; color: #6ee7b7; }

.gj-status-belum {
    padding: 2px 7px; border-radius: 999px;
    background: #fef3c7; color: #92400e;
    font-size: .68rem; font-weight: 600; white-space: nowrap;
}
.dark .gj-status-belum { background: #78350f33; color: #fcd34d; }
</style>

<div class="gj-card">
    {{-- Header --}}
    <div class="gj-header">
        <div class="gj-icon">📅</div>
        <div>
            <h3 class="gj-header-title">Jadwal Hari Ini</h3>
            <p class="gj-header-sub">{{ $tanggal }}</p>
        </div>
    </div>

    {{-- Body --}}
    <div class="gj-body">
        @if($jadwalHariIni->isEmpty())
            <div class="gj-empty">
                <div class="gj-empty-icon">😊</div>
                <p class="gj-empty-text">Tidak ada sesi hari ini</p>
                <p class="gj-empty-sub">Nikmati waktu istirahat Anda 🎉</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="gj-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalHariIni as $sesi)
                        <tr>
                            <td style="white-space:nowrap;font-weight:500;">
                                🕐 {{ $sesi['waktu'] }}@if($sesi['selesai'] !== '–') – {{ $sesi['selesai'] }}@endif
                            </td>
                            <td>
                                <span class="gj-badge-kelas">{{ $sesi['kelas'] }}</span>
                            </td>
                            <td>{{ $sesi['mapel'] }}</td>
                            <td>
                                @if($sesi['status'] === 'Hadir')
                                    <span class="gj-status-hadir">✓ Hadir</span>
                                @else
                                    <span class="gj-status-belum">⏳ Belum Absen</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
</x-filament-widgets::widget>
