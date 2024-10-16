<x-layout bodyClass="bg-gray-100">
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100"
            style="background-image: url('https://vacuumsealer.id/wp-content/uploads/2023/10/kunjungan-industri-di-jogja-1.jpg'); background-size: cover; background-position: center;">
            <span class="mask bg-gradient-light opacity-8"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 shadow-lg border-0 fadeIn3 fadeInBottom" style="border-radius: 15px;">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-danger shadow-info border-radius-lg py-4"> <!-- Ubah warna latar belakang menjadi merah -->
                                    <h4 class="text-white font-weight-bold text-center mt-2">Page Login User</h4>
                                </div>
                            </div>
                            <div class="card-body px-5">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email Input dengan Floating Label -->
                                    <div class="form-floating mb-4">
                                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
                                        <label for="email">Email</label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <!-- Password Input dengan Floating Label -->
                                    <div class="form-floating mb-4">
                                        <x-text-input id="password" class="form-control" type="password" name="password" required placeholder="Password" />
                                        <label for="password">Password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="form-check mb-4">
                                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                        <label for="remember_me" class="form-check-label text-danger">{{ __('Remember me') }}</label> <!-- Ubah warna teks menjadi merah -->
                                    </div>

                                    <div class="text-center">
                                        <x-primary-button class="btn bg-danger w-100 py-2 mb-3" style="border-radius: 10px; font-weight: 500;"> <!-- Ubah tombol menjadi merah -->
                                            {{ __('Log in') }}
                                        </x-primary-button>
                                    </div>

                                    <p class="mt-3 text-sm text-center">
                                        @if (Route::has('password.request'))
                                            <a class="text-danger font-weight-bold" href="{{ route('password.request') }}"> <!-- Ubah warna tautan menjadi merah -->
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </p>

                                    <p class="mt-3 text-sm text-center">
                                        Don't have an account?
                                        <a href="{{ route('register') }}" class="text-danger font-weight-bold">Sign up</a> <!-- Ubah warna tautan menjadi merah -->
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>