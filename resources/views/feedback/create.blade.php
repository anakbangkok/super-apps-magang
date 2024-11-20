<div class="feedback-form p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
    <h2 class="mb-4">Berikan Umpan Balik Anda</h2>
    <form action="{{ route('feedback.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" readonly>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Umpan Balik</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Umpan Balik</button>
    </form>
</div>

<style>
    .feedback-form {
        border-radius: 15px; /* Membuat sudut kartu lebih bulat */
        border: 1px solid #e0e0e0; /* Menambahkan batas lembut */
        background-color: #f9f9f9; /* Latar belakang kartu */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan untuk efek kedalaman */
    }

    .form-label {
        font-weight: ; /* Membuat label lebih tebal */
        color: #566a7f; /* Warna label */
    }

    .form-control {
        border-radius: 8px; /* Membuat sudut input lebih bulat */
        border: 1px solid #ced4da; /* Batas input */
    }

    .btn-danger {
        background-color: #F44335; /* Mengatur warna latar belakang tombol */
        border: none; /* Menghilangkan batas tombol */
        transition: background-color 0.3s ease; /* Efek transisi */
    }

    .btn-danger:hover {
        background-color: #c62828; /* Warna tombol saat hover */
    }
</style>
