<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Siswa - {{ $siswa->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a1a; }

        .page-header {
            text-align: center;
            border-bottom: 3px double #2c3e50;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .page-header h1 { font-size: 20px; letter-spacing: 3px; color: #2c3e50; }
        .page-header h2 { font-size: 13px; color: #555; margin-top: 2px; }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row { display: table-row; }
        .info-label, .info-value {
            display: table-cell;
            padding: 3px 6px;
        }
        .info-label { width: 130px; font-weight: bold; }
        .info-value::before { content: ': '; }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.nilai thead tr {
            background-color: #2c3e50;
            color: #fff;
        }
        table.nilai th, table.nilai td {
            border: 1px solid #ccc;
            padding: 7px 10px;
            text-align: left;
        }
        table.nilai tbody tr:nth-child(even) {
            background-color: #f5f7fa;
        }
        .nilai-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-info    { background: #d1ecf1; color: #0c5460; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger  { background: #f8d7da; color: #721c24; }

        .avg-row td { font-weight: bold; background-color: #eaf0fb; }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h1>RAPOR SISWA</h1>
        <h2>BIMBEL KAIFA</h2>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Nama Siswa</div>
            <div class="info-value">{{ $siswa->nama }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Kelas</div>
            <div class="info-value">{{ $siswa->kelas?->nama_kelas ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenjang</div>
            <div class="info-value">{{ $siswa->jenjang ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Semester</div>
            <div class="info-value">Semester {{ $semester }}</div>
        </div>
    </div>

    <table class="nilai">
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Mata Pelajaran</th>
                <th style="width:80px; text-align:center">Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $total = 0; @endphp
            @foreach ($rapors as $rapor)
                @php
                    $total += $rapor->nilai;
                    $badgeClass = match(true) {
                        $rapor->nilai >= 90 => 'badge-success',
                        $rapor->nilai >= 75 => 'badge-info',
                        $rapor->nilai >= 60 => 'badge-warning',
                        default             => 'badge-danger',
                    };
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $rapor->pengajaran?->mapel?->nama ?? '-' }}</td>
                    <td style="text-align:center">
                        <span class="nilai-badge {{ $badgeClass }}">{{ $rapor->nilai }}</span>
                    </td>
                    <td>{{ $rapor->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        @if ($rapors->count() > 0)
            @php $avg = round($total / $rapors->count(), 1); @endphp
            <tfoot>
                <tr class="avg-row">
                    <td colspan="2" style="text-align:right">Rata-rata Nilai</td>
                    <td style="text-align:center">{{ $avg }}</td>
                    <td>{{ $avg >= 75 ? 'LULUS' : 'PERLU PERBAIKAN' }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
