<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class VerificationController extends Controller
{
    public function checkNISN(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'nisn' => 'required'
        ]);

        $student = Student::find($request->student_id);

        if (!$student || $student->nisn !== $request->nisn) {
            return back()->with('error', 'NISN salah!');
        }

        session(['student_verified' => $student->id]);

        return redirect('/maps');
    }
}
