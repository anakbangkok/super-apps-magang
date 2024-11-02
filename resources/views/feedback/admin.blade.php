@extends('admin.layouts.app')

@section('content')
<div class="container py-3">
    <h1 class="text-right mb-3">Manajemen Umpan Balik Admin</h1>

    @if (session('success'))
        <div class="alert alert-success text-right">
            {{ session('success') }}
        </div>
    @endif

    @if ($feedbacks->isEmpty())
        <div class="alert alert-info text-right">Tidak ada umpan balik yang tersedia.</div>
    @else
        <div class="row g-2 mt-3">
            @foreach ($feedbacks as $feedback)
                <div class="col-12 col-sm-6 col-md-4"> 
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $feedback->user->profile_photo ? asset('storage/' . $feedback->user->profile_photo) : asset('assets/img/avatars/default.jpg') }}" alt="Foto Profil" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <h5 class="card-title mb-0">{{ $feedback->name }}</h5>
                                        <h6 class="card-subtitle text-muted mt-1">{{ $feedback->email }}</h6>
                                    </div>
                                </div>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" title="Hapus">
                                        <i class="bx bx-trash" style="font-size: 1.5rem;"></i> <!-- Gunakan ikon dari boxicons -->
                                    </button>
                                </form>
                            </div>
                            <p class="card-text mt-2 mb-1">{{ $feedback->message }}</p>
                            <p class="card-text mb-0">
                                <small class="text-muted">Dikirim {{ $feedback->created_at->format('d M Y H:i') }}</small>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <form action="{{ route('feedback.reply', $feedback->id) }}" method="POST" class="mt-2 mb-0">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="reply" class="form-control" placeholder="Balas umpan balik..." aria-label="Balas umpan balik...">
                                    <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                                </div>
                            </form>
                            @if($feedback->reply)
                                <div class="mt-2">
                                    <strong>Balasan:</strong> {{ $feedback->reply }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $feedbacks->links('vendor.pagination.bootstrap-4') }}
    @endif
</div>
@endsection

<style>
/* Tampilan Kartu */
.card {
    border: none;
    border-radius: 0.5rem; 
    transition: transform 0.15s, box-shadow 0.15s;
    margin-bottom: 0.5rem; 
}

.card:hover {
    transform: scale(1.03); 
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); 
}

/* Header Kartu */
.card-title {
    font-size: 1.15rem; 
    font-weight: 600;
}

.card-subtitle {
    font-size: 0.85rem;
}

/* Konten Kartu */
.card-text {
    color: #495057;
    margin: 0; /* Hapus margin untuk mengurangi ruang kosong */
}
</style>
