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

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>
        <!-- Tombol Toggle Filter dengan Ikon Eye Slash -->
        <button type="button" class="btn btn-primary mb-3" onclick="toggleFilter()" id="filterButton">
            <i id="filterIcon" class="fas fa-eye-slash"></i> Filter
        </button>

        <!-- Form Filter (default: hidden, tampil jika ada filter aktif) -->
        <div id="filterForm"
            style="{{ request()->anyFilled(['searchName', 'searchEmail', 'searchPenugasan', 'searchInstansi', 'searchStatus', 'startDate', 'endDate']) ? '' : 'display: none;' }}">
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="searchName" class="form-control" placeholder="Cari Nama"
                            value="{{ request('searchName') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="searchEmail" class="form-control" placeholder="Cari Email"
                            value="{{ request('searchEmail') }}">
                    </div>
                    <div class="col-md-3">
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
                        <select name="searchStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('searchStatus') == 'Aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="Belum Masuk" {{ request('searchStatus') == 'Belum Masuk' ? 'selected' : '' }}>
                                Belum Masuk</option>
                            <option value="Selesai" {{ request('searchStatus') == 'Selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
                        <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>


        <!-- Modal Import -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="importModalLabel">Import Data Pengguna</h5>
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
                            <li><strong>start_date</strong>: Tanggal mulai (format: YYYY-MM-DD).</li>
                            <li><strong>end_date</strong>: Tanggal selesai (format: YYYY-MM-DD).</li>
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
                                <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx"
                                    required>
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
                            <h5 class="modal-title text-white" id="exportModalLabel">Export Data Pengguna</h5>
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
                                        <option value="tidak_lengkap">Data Tidak Lengkap</option>
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
                <h5 class="card-header text-right">Daftar Pengguna</h5>
                <div>
                    <a href="javascript:void(0)" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#exportModal">Export</a>
                    <a href="javascript:void(0)" class="btn btn-danger me-3" data-bs-toggle="modal"
                        data-bs-target="#importModal">Import</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
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
                                    @php $now = now()->toDateString(); @endphp
                                    @if (!$user->start_date || !$user->end_date)
                                        <span data-bs-toggle="tooltip" title="Data tidak ditemukan"
                                            class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @elseif ($now < $user->start_date)
                                        <span data-bs-toggle="tooltip" title="Belum masuk" class="badge bg-secondary">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                    @elseif ($now >= $user->start_date && $now <= $user->end_date)
                                        <span data-bs-toggle="tooltip" title="Aktif" class="badge bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @else
                                        <span data-bs-toggle="tooltip" title="Selesai" class="badge bg-danger">
                                            <i class="fas fa-flag-checkered"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-start gap-2">
                                    <!-- Edit button -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-warning btn-sm mb-2"
                                        style="padding: 0.375rem 0.75rem; height: 28px;">Edit</a>

                                    <!-- Delete button inside a form -->
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#confirmUserDeletionModal{{ $user->id }}">
                                            Hapus
                                        </button>

                                        <!-- Modal content for delete confirmation -->
                                        <div class="modal fade" id="confirmUserDeletionModal{{ $user->id }}"
                                            tabindex="-1"
                                            aria-labelledby="confirmUserDeletionModalLabel{{ $user->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header"
                                                        style="background-color: #f8d7da; color: #721c24;">
                                                        <h5 class="modal-title"
                                                            id="confirmUserDeletionModalLabel{{ $user->id }}">
                                                            Apakah Anda yakin ingin menghapus?</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Tutup"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p class="text-muted">
                                                            Setelah Anda hapus, semua data akan hilang secara permanen.
                                                        </p>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true, // Agar tabel responsif pada perangkat mobile
                columnDefs: [{
                        className: "text-center",
                        targets: "_all"
                    } // Semua kolom diatur ke text-center
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                },
                initComplete: function() {
                    // Menambahkan gaya CSS setelah DataTable dimuat
                    $('.table th, .table td').css({
                        'padding': '10px', // Mengatur padding tabel
                        'height': '15px' // Mengatur tinggi baris
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
