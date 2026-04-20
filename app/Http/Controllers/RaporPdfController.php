<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rapor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RaporPdfController extends Controller
{
    public function download(Siswa $siswa, $semester)
    {
        $rapors = Rapor::where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->with(['pengajaran.mapel'])
            ->get();

        if ($rapors->isEmpty()) {
            return back()->with('error', 'Data rapor untuk semester ini belum tersedia.');
        }

        $pdf = Pdf::loadView('pdf.rapor', [
            'siswa' => $siswa,
            'semester' => $semester,
            'rapors' => $rapors,
        ]);

        $filename = "Rapor_{$siswa->nama}_Semester_{$semester}.pdf";
        
        return $pdf->download($filename);
    }
}
