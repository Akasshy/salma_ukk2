<?php

namespace App\Http\Controllers;

use App\Models\Assessor;
use App\Models\CompentencyStandard;
use App\Models\CompetencyElement;
use App\Models\Examination;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Element;

class ExaminationController extends Controller
{



    public function showstandar(){
        $id = Auth::user()->assessor->id;
        $competencyStandard = CompentencyStandard::where('assessor_id', $id)->get();
        return view('assessor.penilaian.pilih-standar', compact('competencyStandard'));
    }
    public function showsiswa($id)
    {

        $standars = CompentencyStandard::find($id);
        $standar_id = $standars->id;
        $students = Student::where('major_id', $standars->major_id)->get();


        return view('assessor.penilaian.pilih-siswa', compact( 'standar_id', 'students'));
    }
    public function showelement(Request $request , $id, $standar_id){
        $student_id = $id;
        $element = CompetencyElement::where('competency_id', $standar_id)->get();
        return view('assessor.penilaian.menilai', compact('element', 'student_id', 'standar_id'));
    }

    public function addexamination(Request $request)
    {
        $student_id = $request->input('student_id');
        $standar_id = $request->input('standar_id');
        $statuses = $request->input('status'); // Array dengan format [element_id => status]
        $assessor_id = Auth::user()->assessor->id;

        foreach ($statuses as $elementId => $status) {
            // Cek apakah data sudah ada
            $examination = Examination::firstOrNew([
                'student_id' => $student_id,
                'element_id' => $elementId,
                'standar_id' => $standar_id,
            ]);

            // Jika data ditemukan, lakukan update
            $examination->exam_date = now(); // Waktu saat ini
            $examination->assessor_id = $assessor_id; // ID Assessor
            $examination->status = $status; // Status (1 = selesai, 0 = tidak selesai)
            $examination->comments = 'Mantap'; // Anda dapat mengganti ini sesuai kebutuhan
            $examination->save(); // Simpan perubahan ke database
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data berhasil disimpan atau diperbarui!');
    }

    public function showhasil(){
        $id = Auth::user()->assessor->id;
        $competencyStandard = CompentencyStandard::where('assessor_id', $id)->get();
        return view('assessor.laporan.pilihstandar', compact('competencyStandard'));
    }
    public function result(Request $request, $id)
    {
        // Validasi apakah standar kompetensi milik assessor
        $assessor_id = Auth::user()->assessor->id;

        $standard = CompentencyStandard::where('id', $id)
            ->where('assessor_id', $assessor_id)
            ->with('competency_elements')
            ->first();

        if (!$standard) {
            return back()->with('error', 'Standar kompetensi tidak ditemukan atau tidak berhak mengakses.');
        }

        // Ambil data ujian
        $examinations = Examination::where('standar_id', $id)->with('student.user')->get();

        // Kelompokkan berdasarkan student_id
        $students = $examinations->groupBy('student_id')->map(function ($exams) use ($standard) {
            $totalElements = $standard->competency_elements->count();
            $completedElements = $exams->where('status', 1)->unique('element_id')->count();

            $finalScore = $totalElements > 0 ? round(($completedElements / $totalElements) * 100) : 0;

            if ($finalScore >= 91) {
                $status = "Sangat Kompeten";
            } elseif ($finalScore >= 75) {
                $status = "Kompeten";
            } elseif ($finalScore >= 61) {
                $status = "Cukup Kompeten";
            } else {
                $status = "Belum Kompeten";
            }

            return [
                'student_id' => $exams->first()->student_id,
                'student_name' => $exams->first()->student->user->name,
                'final_score' => $finalScore,
                'status' => $status,
            ];
        });

        return view('assessor.laporan.hasilujian', compact('standard', 'students'));
    }




}
