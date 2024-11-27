@extends('assessor')
@section('content')

<div class="container mt-50" id="" style="margin-left: 20%;">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kompetensi Element</h6>
        </div>
        <div class="card-body">
            <div class="col-md-6"></div>
        </div>
        <div class="form-body">
            <div class="row">
                <div class="container">
                    <!-- Form untuk menilai kompetensi -->
                    <form action="/add/examination" method="post">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>CRITERIA</th>
                                    <th>COMPETENCY STANDAR</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="3"></th>
                                    <th>
                                        <input type="submit" value="Menilai" class="btn btn-primary w-100" id="">
                                    </th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($element as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->criteria }}</td>
                                    <td>{{ $item->competencystandard->unit_title }}</td>
                                    <td>
                                        <!-- Menggunakan array untuk menyimpan status elemen -->
                                        <select name="status[{{ $item->id }}]" class="form-select">
                                            <option value="1">Selesai</option>
                                            <option value="0">Tidak Selesai</option>
                                        </select>
                                        <!-- Input hidden untuk mengirimkan ID student -->
                                        <input type="hidden" name="student_id" value="{{ $student_id }}">
                                        <input type="hidden" name="standar_id" value="{{ $standar_id }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        title: 'Gagal!',
        text: '{{ session("error") }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

@endsection
