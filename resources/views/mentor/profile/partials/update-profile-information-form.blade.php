<section class="container my-5 mx-auto" style="max-width: 600px;">
    <h1 class="mb-4 text-right" style="font-family: 'Arial', sans-serif;">Informasi Profil</h1>

    <div class="alert alert-info" style="background-color: #e9ecef; color: #0c5460;">
        <strong>Info:</strong> Perbarui detail profil dan email Anda untuk menjaga informasi akun tetap terbaru.
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-4 border rounded shadow-sm" style="background-color: #f9f9f9;">
        @csrf
        @method('patch')

        <div class="row">
            <!-- Name Field -->
            <div class="col-md-12 mb-3">
                <label for="name" class="form-label">Nama</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" 
                    required 
                    autofocus
                >
                @error('name') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" 
                    required
                >
                @error('email') 
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="mt-2 text-muted">
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" class="btn btn-link p-0">
                            Kirim ulang email verifikasi
                        </button>
                    </p>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3">Simpan</button>
    </form>
</section>
