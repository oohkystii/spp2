@extends('auth.app_auth_sneat')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="index.html" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ \Storage::url(settings()->get('app_logo')) }}" alt="" width="70">
                            </span>
                            <span class="app-brand-text demo text-body fw-bolder"></span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2 text-center">MA YPI Baiturrahman Leles</h4>

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukkan Email disini" autofocus />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                {{-- <a href="{{ route('password.request') }}">
                                    <small>Lupa Password?</small>
                                </a> --}}
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="Password" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer toggle-password">
                                    <i class="bx bx-hide"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>
                    <!-- /Form -->

                    <!-- Error Message -->
                    @if ($errors->has('email') || $errors->has('password'))
                    <div class="alert alert-danger mb-3">
                        <strong>Email atau password salah</strong>
                    </div>
                    @endif

                    <!-- Register Link -->
                    <p class="text-center">
                        <span>Jika belum memiliki akun, Silahkan hubungi bendahara sekolah.</span>
                        <br />
                        <a href="https://wa.me/{{ settings()->get('no_wa_operator') }}" target="_blank">
                            <span>Klik Link Whatsapp ini</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to toggle password visibility -->
<script>
    const togglePasswordButton = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');

    togglePasswordButton.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePasswordButton.querySelector('i').classList.toggle('bx-hide');
    });
</script>
@endsection
