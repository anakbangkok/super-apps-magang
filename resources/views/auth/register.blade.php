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
                                <div class="bg-danger shadow-info border-radius-lg py-4">
                                    <h4 class="text-white font-weight-bold text-center mt-2">Page Register User</h4>
                                </div>
                            </div>
                            <div class="card-body px-5">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="row mb-4">
                                        <!-- Name Input -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                                                <label for="name">Name</label>
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Email Input -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required placeholder="name@example.com" />
                                                <label for="email">Email</label>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <!-- Alamat Input -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <x-text-input id="alamat" class="form-control" type="text" name="alamat" :value="old('alamat')" placeholder="Alamat" />
                                                <label for="alamat">Alamat</label>
                                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Asal Sekolah Input -->
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <x-text-input id="asal_sekolah" class="form-control" type="text" name="asal_sekolah" :value="old('asal_sekolah')" placeholder="Asal Sekolah" />
                                                <label for="asal_sekolah">Asal Sekolah</label>
                                                <x-input-error :messages="$errors->get('asal_sekolah')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <x-text-input id="password" class="form-control" type="password" name="password" required placeholder="Password" />
                                        <label for="password">Password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div class="form-floating mb-4">
                                        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required placeholder="Confirm Password" />
                                        <label for="password_confirmation">Confirm Password</label>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>

                                    <div class="text-center">
                                        <x-primary-button class="btn bg-danger w-100 py-2 mb-3" style="border-radius: 10px; font-weight: 500;">
                                            {{ __('Register') }}
                                        </x-primary-button>
                                    </div>

                                    <p class="mt-3 text-sm text-center">
                                        Already registered?
                                        <a href="{{ route('login') }}" class="text-danger font-weight-bold">Login</a>
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
