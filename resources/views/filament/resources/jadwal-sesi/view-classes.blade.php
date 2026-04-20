<div class="p-4">
    @if($classes->isEmpty())
        <div class="text-center py-4 text-gray-500">
            Belum ada kelas yang di-assign untuk sesi ini.
        </div>
    @else
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b dark:border-gray-700">
                    <th class="py-2 px-3 font-semibold text-sm">Tanggal</th>
                    <th class="py-2 px-3 font-semibold text-sm">Nama Kelas</th>
                    <th class="py-2 px-3 font-semibold text-sm">Guru</th>
                    <th class="py-2 px-3 font-semibold text-sm">Mata Pelajaran</th>
                    <th class="py-2 px-3 font-semibold text-sm">Siswa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $kelas)
                    <tr class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-3 px-3 text-sm">
                            {{ \Carbon\Carbon::parse($kelas->pivot->tanggal)->translatedFormat('d F Y') }}
                        </td>
                        <td class="py-3 px-3 text-sm font-medium">
                            {{ $kelas->nama_kelas }}
                        </td>
                        <td class="py-3 px-3 text-sm">
                            {{ $kelas->guru?->nama ?? '-' }}
                        </td>
                        <td class="py-3 px-3 text-sm">
                            {{ $kelas->mata_pelajaran ?? '-' }}
                        </td>
                        <td class="py-3 px-3 text-sm">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                {{ $kelas->jumlah_siswa }} siswa
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
