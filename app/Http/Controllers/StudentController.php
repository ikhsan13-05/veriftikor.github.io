<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Verification;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $class = $request->get('class');

        $students = Student::with('verification')
            ->when($class, function ($q) use ($class) {
                $q->where('class', $class);
            })->get();

        // 📊 Statistik
        $totalStudents = Student::count();
        $verifiedStudents = Verification::count();
        $unverifiedStudents = $totalStudents - $verifiedStudents;

        return view('students.index', compact(
            'students',
            'totalStudents',
            'verifiedStudents',
            'unverifiedStudents'
        ));
    }

    public function verifyForm($id)
    {
        $student = Student::with('verification')->findOrFail($id);

        // 🚫 Blok kalau sudah verifikasi
        if ($student->verification) {
            return redirect('/')
                ->with('error', 'Siswa ini sudah melakukan verifikasi!');
        }

        return view('students.verify', compact('student'));
    }

    public function verify(Request $request)
    {
        // ✅ Validasi dasar
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'nisn' => 'required'
        ]);

        $student = Student::find($request->student_id);

        // ❌ Cegah jika sudah verifikasi
        if ($student->verification) {
            return response()->json([
                'message' => 'Siswa sudah verifikasi'
            ], 422);
        }

        // ❌ Jika NISN salah
        if ($student->nisn !== $request->nisn) {
            return response()->json([
                'message' => 'NISN salah'
            ], 422); // ⛔ ini kunci penting
        }

        // ✅ Simpan session
        session(['student_verified' => $student->id]);

        return response()->json([
            'message' => 'Berhasil verifikasi'
        ], 200);
    }
}
