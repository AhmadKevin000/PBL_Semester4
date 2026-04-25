<x-filament-widgets::widget>
<style>
.ggaji-card {
    border-radius: .75rem;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    background: #fff;
    height: 100%;
    display: flex; flex-direction: column;
}
.dark .ggaji-card {
    border-color: #374151;
    background: #1f2937;
}
.ggaji-header {
    padding: .75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.dark .ggaji-header { border-bottom-color: #374151; }

.ggaji-header-left {
    display: flex; align-items: center; gap: .625rem;
}
.ggaji-icon {
    width: 2rem; height: 2rem; border-radius: .4rem;
    background: #fef3c7;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.dark .ggaji-icon { background: #78350f44; }

.ggaji-header-title {
    font-size: .875rem; font-weight: 600; margin: 0; color: #111827;
}
.dark .ggaji-header-title { color: #f9fafb; }
.ggaji-header-sub { font-size: .7rem; color: #6b7280; margin: 0; }

/* totals */
.ggaji-totals { display: flex; gap: .875rem; }
.ggaji-total-item { text-align: right; }
.ggaji-total-label {
    font-size: .65rem; text-transform: uppercase; letter-spacing: .04em;
    color: #9ca3af; margin: 0;
}
.ggaji-total-diterima { font-size: .85rem; font-weight: 700; color: #059669; margin: 0; }
.dark .ggaji-total-diterima { color: #34d399; }
.ggaji-total-pending { font-size: .85rem; font-weight: 700; color: #d97706; margin: 0; }
.dark .ggaji-total-pending { color: #fbbf24; }

/* body */
.ggaji-body { padding: .875rem 1rem; flex: 1; }

/* empty */
.ggaji-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 1.75rem 0; text-align: center;
}
.ggaji-empty-icon { font-size: 2rem; margin-bottom: .4rem; }
.ggaji-empty-text { font-size: .85rem; font-weight: 500; color: #4b5563; margin: 0; }
.dark .ggaji-empty-text { color: #9ca3af; }
.ggaji-empty-sub { font-size: .75rem; color: #9ca3af; margin-top: .2rem; }

/* rows */
.ggaji-rows { display: flex; flex-direction: column; gap: .5rem; }
.ggaji-row {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: .5rem .75rem;
    border-radius: .5rem;
    border: 1px solid transparent;
}
.ggaji-row-paid {
    background: #ecfdf5;
    border-color: #a7f3d0;
}
.dark .ggaji-row-paid { background: #06402033; border-color: #06402066; }

.ggaji-row-pending {
    background: #fffbeb;
    border-color: #fde68a;
}
.dark .ggaji-row-pending { background: #78350f22; border-color: #78350f55; }

.ggaji-row-left { display: flex; align-items: center; gap: .5rem; }
.ggaji-row-avatar {
    width: 2rem; height: 2rem; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; flex-shrink: 0;
}
.ggaji-row-avatar-paid { background: #a7f3d0; }
.dark .ggaji-row-avatar-paid { background: #06402066; }
.ggaji-row-avatar-pending { background: #fde68a; }
.dark .ggaji-row-avatar-pending { background: #78350f55; }

.ggaji-row-bulan {
    font-size: .82rem; font-weight: 600; color: #1f2937; margin: 0;
}
.dark .ggaji-row-bulan { color: #f3f4f6; }
.ggaji-row-count { font-size: .7rem; color: #6b7280; margin: 0; }
.dark .ggaji-row-count { color: #9ca3af; }

.ggaji-row-right { text-align: right; }
.ggaji-nominal-paid { font-size: .82rem; font-weight: 700; color: #059669; margin: 0; }
.dark .ggaji-nominal-paid { color: #34d399; }
.ggaji-nominal-pending { font-size: .82rem; font-weight: 700; color: #d97706; margin: 0; }
.dark .ggaji-nominal-pending { color: #fbbf24; }
.ggaji-status-badge {
    display: inline-block; padding: 1px 6px;
    border-radius: .25rem; font-size: .65rem; font-weight: 600;
}
.ggaji-status-paid { background: #d1fae5; color: #065f46; }
.dark .ggaji-status-paid { background: #06402055; color: #6ee7b7; }
.ggaji-status-pending { background: #fef3c7; color: #92400e; }
.dark .ggaji-status-pending { background: #78350f44; color: #fcd34d; }
</style>

<div class="ggaji-card">
    {{-- Header --}}
    <div class="ggaji-header">
        <div class="ggaji-header-left">
            <div class="ggaji-icon">💰</div>
            <div>
                <h3 class="ggaji-header-title">Ringkasan Gaji</h3>
                <p class="ggaji-header-sub">6 bulan terakhir</p>
            </div>
        </div>

        {{-- Totals --}}
        <div class="ggaji-totals">
            <div class="ggaji-total-item">
                <p class="ggaji-total-label">Diterima</p>
                <p class="ggaji-total-diterima">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</p>
            </div>
            @if($totalPending > 0)
            <div class="ggaji-total-item">
                <p class="ggaji-total-label">Pending</p>
                <p class="ggaji-total-pending">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="ggaji-body">
        @if($rekapGaji->isEmpty())
            <div class="ggaji-empty">
                <div class="ggaji-empty-icon">💸</div>
                <p class="ggaji-empty-text">Belum ada data gaji</p>
                <p class="ggaji-empty-sub">Data akan muncul setelah sesi selesai & diverifikasi.</p>
            </div>
        @else
            <div class="ggaji-rows">
                @foreach($rekapGaji as $rekap)
                <div class="ggaji-row {{ $rekap['status'] === 'paid' ? 'ggaji-row-paid' : 'ggaji-row-pending' }}">
                    <div class="ggaji-row-left">
                        <div class="ggaji-row-avatar {{ $rekap['status'] === 'paid' ? 'ggaji-row-avatar-paid' : 'ggaji-row-avatar-pending' }}">
                            {{ $rekap['status'] === 'paid' ? '✓' : '⏳' }}
                        </div>
                        <div>
                            <p class="ggaji-row-bulan">{{ $rekap['bulan'] }}</p>
                            <p class="ggaji-row-count">{{ $rekap['count'] }} sesi</p>
                        </div>
                    </div>
                    <div class="ggaji-row-right">
                        <p class="{{ $rekap['status'] === 'paid' ? 'ggaji-nominal-paid' : 'ggaji-nominal-pending' }}">
                            Rp {{ number_format($rekap['total'], 0, ',', '.') }}
                        </p>
                        <span class="ggaji-status-badge {{ $rekap['status'] === 'paid' ? 'ggaji-status-paid' : 'ggaji-status-pending' }}">
                            {{ $rekap['status'] === 'paid' ? 'Dibayar' : 'Pending' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
</x-filament-widgets::widget>
