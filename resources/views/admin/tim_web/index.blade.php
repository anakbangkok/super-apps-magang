@extends('admin.layouts.app')

@section('title', 'Daftar Tim Web')

<link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}">

@section('content')
    <div class="container">
        <h2>Data Tim Web</h2>
        <div class="d-flex justify-content-between">
            <div class="alert alert-info flex-grow-1 me-2">
                <h5>Data Hari Ini</h5>
                <strong>Jumlah Artikel Hari Ini:</strong> {{ $jumlahArtikel }} <br>
                <strong>Jumlah Kata Hari Ini:</strong> {{ $jumlahKata }}
            </div>
            <div class="alert alert-success flex-grow-1 ms-2">
                <h5>Total Keseluruhan</h5>
                <strong>Total Jumlah Artikel:</strong> {{ $totalJumlahArtikel }} <br>
                <strong>Total Jumlah Kata:</strong> {{ $totalJumlahKata }}
            </div>
        </div>



        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow" style="border-radius: 4px">
            {{-- <h5 class="card-header text-right">Daftar Tim Web</h5> --}}
            <div class="container row mb-4 mt-4">
                <div class="col-md-4">
                    <label for="min-date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="min-date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="max-date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" id="max-date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="nama-filter" class="form-label">Filter Nama:</label>
                    <select id="nama-filter" class="form-control select2" placeholder="Cari Nama...">
                        <option value="">Pilih Nama</option>
                        @foreach ($tim_webs as $tim_web)
                            <option value="{{ $tim_web->user->name }}">{{ $tim_web->user->name }}</option>
                            <!-- Menampilkan nama user -->
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table id="tim-web-table" class="table">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah Artikel Hari Ini</th>
                            <th>Jumlah Kata Hari Ini</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tim_webs as $tim_web)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tim_web->user->name }}</td>
                                <td>{{ $tim_web->jumlah_artikel }}</td>
                                <td>{{ $tim_web->jumlah_kata }}</td>
                                <td>{{ $tim_web->keterangan }}</td>
                                <td>{{ \Carbon\Carbon::parse($tim_web->tanggal)->translatedformat('d F Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('tim_web.edit', $tim_web->id) }}"
                                            class="btn btn-warning btn-sm button-action me-2" title="Edit">Edit</a>
                                        <form id="deleteTimWebForm{{ $tim_web->id }}"
                                            action="{{ route('tim_web.destroy', $tim_web->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm button-action"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal{{ $tim_web->id }}">Hapus</button>
                                        </form>
                                    </div>

                                    <!-- Modal Konfirmasi Penghapusan -->
                                    <div class="modal fade" id="confirmDeleteModal{{ $tim_web->id }}" tabindex="-1"
                                        aria-labelledby="confirmDeleteModalLabel{{ $tim_web->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content shadow">
                                                <div class="modal-header"
                                                    style="background-color: #f8d7da; color: #721c24;">
                                                    <h5 class="modal-title"
                                                        id="confirmDeleteModalLabel{{ $tim_web->id }}">Konfirmasi
                                                        Penghapusan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="document.getElementById('deleteTimWebForm{{ $tim_web->id }}').submit();">Ya,
                                                        Hapus</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Modal Konfirmasi Penghapusan -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Penghapusan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include jQuery, DataTables, Moment.js, and Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/datetime/1.3.0/js/dataTables.dateTime.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />

        <script>
            $(document).ready(function() {
                // Initialize Select2 for the Nama filter
                $('#nama-filter').select2({
                    placeholder: 'Pilih Nama...',
                    allowClear: true
                });


                var table = $('#tim-web-table').DataTable({
                    responsive: true, // Enable responsive table
                    language: {
                        search: "Cari:", // Custom search label
                        lengthMenu: "Tampilkan _MENU_ data per halaman", // Page length options
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data", // Info about data range
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data", // Info when no data is found
                        paginate: {
                            previous: "Sebelumnya", // Custom text for previous button
                            next: "Berikutnya" // Custom text for next button
                        }
                    },
                    columnDefs: [{
                            className: "text-center",
                            targets: "_all"
                        } // Semua kolom diatur ke text-center
                    ]

                });

                // Event handler for changes in the date filters and name filter
                $('#min-date, #max-date, #nama-filter').on('change input', function() {
                    table.draw(); // Redraw the table when the filter is applied
                });

                // Custom search function for filtering based on date and name
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var date = data[5]; // Column date (index starts from 0)
                    var nama = data[1].toLowerCase(); // Column name (index starts from 0)

                    // Parsing date from table (assumed format: d F Y)
                    var tableDate = moment(date, 'D MMMM YYYY').format('YYYY-MM-DD');

                    var minDate = $('#min-date').val();
                    var maxDate = $('#max-date').val();
                    var namaFilter = $('#nama-filter').val()?.toLowerCase();

                    // Filter based on date and name
                    if (
                        (minDate === '' || tableDate >= minDate) &&
                        (maxDate === '' || tableDate <= maxDate) &&
                        (namaFilter === '' || nama.includes(namaFilter))
                    ) {
                        return true;
                    }
                    return false;
                    // Baris diterima jika tidak ada filter yang gagal
                });

                // Hitung jumlah kata berdasarkan nama yang difilter
                $('#nama-filter').on('change', function() {
                    var namaFilter = $(this).val().toLowerCase();
                    var totalKata = 0;

                    // Loop melalui baris untuk menghitung total jumlah kata berdasarkan filter nama
                    table.rows().every(function() {
                        var nama = this.data()[1].toLowerCase();
                        var jumlahKata = this.data()[3]; // Kolom jumlah kata (index dimulai dari 0)

                        if (nama.includes(namaFilter)) {
                            totalKata += parseInt(jumlahKata, 10);
                        }
                    });

                    // Update elemen yang menampilkan total jumlah kata
                    $('#totalKata').text(totalKata);
                });

                // Modal untuk konfirmasi penghapusan
                $('#confirmDeleteModal').on('show.bs.modal', function(e) {
                    var button = $(e.relatedTarget); // Tombol yang membuka modal
                    var id = button.data('id'); // Ambil ID data yang akan dihapus
                    var form = $('#deleteTimWebForm'); // Formulir penghapusan
                    form.attr('action', form.attr('action').replace(/tim_web\/\d+/, 'tim_web/' +
                        id)); // Sesuaikan URL form dengan ID yang benar
                });

                // Ketika tombol hapus pada modal diklik
                $('#confirmDeleteBtn').on('click', function() {
                    $('#deleteTimWebForm').submit(); // Kirimkan form penghapusan
                });
            });
        </script>


    @endsection
