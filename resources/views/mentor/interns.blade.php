{{-- @extends('admin.layouts.app')

@section('title', 'Daftar Magang')

@section('content')
<div class="container">
    <h2>Daftar Magang di Bawah Mentor {{ $mentor->nama }}</h2>
    <a href="{{ route('mentors.index') }}" class="btn btn-secondary mb-3">Kembali ke Daftar Mentor</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($interns as $intern)
            <tr>
                <td>{{ $intern->nim }}</td>
                <td>{{ $intern->nama }}</td>
                <td>{{ $intern->jurusan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection --}}
