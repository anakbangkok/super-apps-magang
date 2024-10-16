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
                                    <h4 class="text-white font-weight-bold text-center mt-2">Reset Password</h4>
                                </div>
                            </div>
                            <div class="card-body px-5">
                                <div class="mb-4 text-sm text-gray-600">
                                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                </div>

                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div class="form-floating mb-4">
                                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
                                        <label for="email">Email</label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div class="text-center">
                                        <x-primary-button class="btn bg-danger w-100 py-2 mb-3" style="border-radius: 10px; font-weight: 500;">
                                            {{ __('Email Password Reset Link') }}
                                        </x-primary-button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <a href="{{ route('login') }}" class="text-danger font-weight-bold">Back to User Login</a> |
                                    <a href="{{ route('admin.login') }}" class="text-danger font-weight-bold">Back to Admin login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
