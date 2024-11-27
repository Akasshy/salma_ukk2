@extends('assessor')

@section('content')
<div class="container mt-5" style="margin-left: 20%;">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Hasil Ujian</h6>
        </div>
        <div class="card-body">
            <!-- Form untuk memilih standar kompetensi -->
            {{--  <form action="{{ route('assessor.result', $standard->id) }}" method="GET">  --}}
                {{--  <div class="form-group">
                    <label for="standar_id">Pilih Standar Kompetensi:</label>
                    <select name="standar_id" id="standar_id" class="form-select" onchange="this.form.submit()">
                        @foreach ($standars as $standar)
                            <option value="{{ $standar->id }}" {{ $standar->id == $standard->id ? 'selected' : '' }}>
                                {{ $standar->unit_title }} - {{ $standar->major->major_name }}
                            </option>
                        @endforeach
                    </select>
                </div>  --}}
            {{--  </form>  --}}

            <!-- Tabel hasil ujian -->
            <div class="mt-4">
                @if($students->isEmpty())
                    <p class="text-center">Tidak ada data hasil ujian untuk standar kompetensi ini.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Nama Siswa</th>
                                <th>Nilai Akhir (%)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{{ $student['student_name'] }}</td>
                                    <td>{{ $student['final_score'] }}%</td>
                                    <td>
                                        @if($student['status'] === 'Sangat Kompeten')
                                            <span class="badge bg-success">{{ $student['status'] }}</span>
                                        @elseif($student['status'] === 'Kompeten')
                                            <span class="badge bg-primary">{{ $student['status'] }}</span>
                                        @elseif($student['status'] === 'Cukup Kompeten')
                                            <span class="badge bg-warning">{{ $student['status'] }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $student['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
