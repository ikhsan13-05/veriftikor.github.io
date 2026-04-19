<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;
use App\Services\GoogleSheetsService;
use App\Models\Student;

class MapsController extends Controller
{
    public function index()
    {
        if (!session('student_verified')) {
            return redirect('/');
        }

        return view('maps.pick-location');
    }

    public function store(Request $request, GoogleSheetsService $sheets)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $studentId = session('student_verified');

        $student = Student::findOrFail($studentId);

        // 🚫 Cegah double input
        if ($student->verification) {
            return redirect('/')
                ->with('error', 'Anda sudah melakukan verifikasi sebelumnya!');
        }

        // ✅ Simpan ke database
        $verification = $student->verification()->create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'verified_at' => now()
        ]);

        // ✅ Kirim ke Google Sheets
        $sheets->append(
            env('GOOGLE_SHEET_ID'),
            [
                $student->name,
                $student->nisn,
                $student->class,
                $verification->latitude,
                $verification->longitude,
                now()->format('Y-m-d H:i:s')
            ]
        );

        return redirect('/')
            ->with('success', 'Lokasi berhasil disimpan & terkirim ke Google Sheets!');
    }
}
