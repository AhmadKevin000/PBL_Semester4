<!DOCTYPE html>
<html>
<head>
    <title>Laporan Gaji Guru</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .total { font-weight: bold; margin-top: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN GAJI GURU</h1>
        <h2>BIMBEL MANTAP</h2>
    </div>

    @foreach($data as $guruNama => $items)
    <div style="margin-top: 20px; border-top: 2px solid #000; padding-top: 10px;">
        <h3>Nama Guru: {{ $guruNama }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Sesi</th>
                    <th>Siswa</th>
                    <th>Gaji</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGuru = 0; @endphp
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->kelas->nama_kelas }}</td>
                    <td>{{ $item->sesi }}</td>
                    <td>{{ $item->jumlah_siswa }}</td>
                    <td>Rp {{ number_format($item->gaji, 0, ',', '.') }}</td>
                </tr>
                @php $totalGuru += $item->gaji; @endphp
                @endforeach
            </tbody>
        </table>
        <div class="total">Total Gaji: Rp {{ number_format($totalGuru, 0, ',', '.') }}</div>
    </div>
    @endforeach
</body>
</html>
