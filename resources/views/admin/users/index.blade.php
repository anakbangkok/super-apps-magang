@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">
<!-- Menambahkan Link CSS untuk DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

@section('content')

    <div class="container">
        <h2>Daftar Manajemen Pengguna</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Tambah Pengguna
            </a>

            <button type="button" class="btn btn-primary" onclick="toggleFilter()" id="filterButton">
                <i id="filterIcon" class="fas fa-eye-slash"></i> Filter
            </button>

            <form action="{{ route('admin.users.updateStatuses') }}" method="POST"
                onsubmit="return confirm('Yakin ingin update semua status pengguna?')" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-warning">
                    Perbarui Status
                </button>
            </form>
        </div>



        <!-- Form Filter (default: hidden, tampil jika ada filter aktif) -->
        <div class="card mb-4" id="filterForm"
            style="{{ request()->anyFilled(['searchName', 'searchEmail', 'searchPenugasan', 'searchInstansi', 'searchStatus', 'startDate', 'endDate']) ? '' : 'display: none;' }}">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="searchName" class="form-control" placeholder="Cari Nama"
                                value="{{ request('searchName') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email"
                                value="{{ request('searchEmail') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Penugasan</label>
                            <select name="searchPenugasan" class="form-select">
                                <option value="">Semua Penugasan</option>
                                @foreach ($penugasans as $penugasan)
                                    <option value="{{ $penugasan->id }}"
                                        {{ request('searchPenugasan') == $penugasan->id ? 'selected' : '' }}>
                                        {{ $penugasan->nama_unit_bisnis }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Instansi</label>
                            <select name="searchInstansi" class="form-select">
                                <option value="">Semua Instansi</option>
                                @foreach ($instansis as $instansi)
                                    <option value="{{ $instansi->id }}"
                                        {{ request('searchInstansi') == $instansi->id ? 'selected' : '' }}>
                                        {{ $instansi->nama_instansi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="searchStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('searchStatus') == 'Aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="Belum Masuk"
                                    {{ request('searchStatus') == 'Belum Masuk' ? 'selected' : '' }}>Belum Masuk</option>
                                <option value="Selesai" {{ request('searchStatus') == 'Selesai' ? 'selected' : '' }}>
                                    Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Import -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="importModalLabel">Impor Data Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <p>
                            Untuk memastikan format data yang Anda upload benar, silakan buat file Excel dengan kolom
                            berikut:
                        </p>
                        <ul>
                            <li><strong>name</strong>: Nama pengguna.</li>
                            <li><strong>email</strong>: Alamat email pengguna.</li>
                            <li><strong>password</strong>: Kata sandi pengguna.</li>
                            <li><strong>start_date</strong>: Tanggal mulai (format: DD-MM-YYYY).</li>
                            <li><strong>end_date</strong>: Tanggal selesai (format: DD-MM-YYYY).</li>
                        </ul>
                        <p class="mt-3">
                            Anda dapat menggunakan file template yang telah disediakan untuk mempermudah proses import.
                            <a href="{{ route('admin.users.downloadTemplate') }}" class="btn btn-link">Download Template
                                Excel</a>
                        </p>

                        <!-- Form Upload -->
                        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data"
                            class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih File Excel</label>
                                <input type="file" name="file" id="file" class="form-control"
                                    accept=".xls,.xlsx" required>
                                <small class="form-text text-muted">File yang didukung: .xls, .xlsx</small>
                            </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Import Data</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Export -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('admin.users.export') }}" method="GET">
                    <div class="modal-content shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title text-white" id="exportModalLabel">Ekspor Data Pengguna</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Instansi -->
                                <div class="col-md-6">
                                    <label for="instansi_id" class="form-label">Instansi</label>
                                    <select name="instansi_id" id="instansi_id" class="form-select">
                                        <option value="">Semua Instansi</option>
                                        @foreach ($instansis as $instansi)
                                            <option value="{{ $instansi->id }}">{{ $instansi->nama_instansi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="Belum Masuk">Belum Masuk</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div>

                                <!-- Rentang Tanggal -->
                                <div class="col-md-6">
                                    <label for="start_from" class="form-label">Tanggal Mulai Dari</label>
                                    <input type="date" name="start_from" id="start_from" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label for="start_to" class="form-label">Tanggal Mulai Sampai</label>
                                    <input type="date" name="start_to" id="start_to" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Export</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




        <div class="card shadow">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-header text-right">Pengguna Terdaftar</h5>
                <div>
                    <a href="javascript:void(0)" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#exportModal">Ekspor</a>
                    <a href="javascript:void(0)" class="btn btn-danger me-3" data-bs-toggle="modal"
                        data-bs-target="#importModal">Impor</a>
                </div>

            </div>
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Instansi</th>
                            <th>Penugasan</th>
                            <th>Mentor</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($users as $user)
                            <tr class="text-center">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->instansi->nama_instansi ?? 'N/A' }}</td>
                                <td>{{ $user->penugasan->nama_unit_bisnis ?? 'N/A' }}</td>
                                <td>{{ $user->mentor->name ?? 'N/A' }}</td>
                                <td>{{ $user->start_date ? \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') : 'N/A' }}
                                </td>
                                <td>{{ $user->end_date ? \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') : 'N/A' }}
                                </td>
                                <td>
                                    @if (!$user->status)
                                        <span class="badge bg-warning" title="Status tidak tersedia">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @elseif ($user->status === 'Belum Masuk')
                                        <span class="badge bg-secondary" title="Belum Masuk">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    @elseif ($user->status === 'Aktif')
                                        <span class="badge bg-success" title="Aktif">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @elseif ($user->status === 'Selesai')
                                        <span class="badge bg-danger" title="Selesai">
                                            <i class="fas fa-flag-checkered"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-start gap-2 align-items-center" style="height: 100%;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-warning btn-sm px-3 py-1">
                                        Edit
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm px-3 py-1" data-bs-toggle="modal"
                                        data-bs-target="#confirmUserDeletionModal{{ $user->id }}">
                                        Hapus
                                    </button>
                                </td>

                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                @foreach ($users as $user)
                    <div class="modal fade" id="confirmUserDeletionModal{{ $user->id }}" tabindex="-1"
                        aria-labelledby="confirmUserDeletionModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content shadow">
                                <div class="modal-header" style="background-color: #f8d7da; color: #721c24;">
                                    <h5 class="modal-title" id="confirmUserDeletionModalLabel{{ $user->id }}">
                                        Apakah Anda yakin ingin menghapus {{ $user->name }}?
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">
                                        Setelah Anda hapus, semua data akan hilang secara permanen.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                columnDefs: [{
                    scrollX: true,
                    className: "text-center",
                    targets: "_all"
                }],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada hasil untuk pencarian Anda",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-2"lf>' +
                    // length + filter di atas
                    '<"table-responsive"t>' + // table scroll
                    '<"d-flex justify-content-between align-items-center mt-2"ip>', // info + pagination
                initComplete: function() {
                    $('.table th, .table td').css({
                        'padding': '10px',
                        'height': '15px'
                    });
                },
                initComplete: function() {
                    $('.table th, .table td').css({
                        'padding': '10px',
                        'height': '15px'
                    });
                }
            });
        });

        function toggleFilter() {
            const filter = document.getElementById('filterForm');
            const icon = document.getElementById('filterIcon');
            filter.style.display = (filter.style.display === "none" || filter.style.display === "") ? "block" : "none";

            var dateColumn = table.row(dataIndex).node().querySelector('td[data-order]').getAttribute('data-order');
            var rowDate = new Date(dateColumn);

            // Ganti ikon sesuai status form filter
            if (filter.style.display === "none") {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
