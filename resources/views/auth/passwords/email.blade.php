@extends('auth.app_auth_sneat')

@section('content')
    <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
        <!-- Forgot Password -->
        <div class="card">
            <div class="card-body">
            <!-- Logo -->
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="app-brand justify-content-center">
                <a href="" class="app-brand-link gap-2">
                    <img src="{{ \Storage::url(settings()->get('app_logo')) }}" alt="" width="70">
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Lupa Password? 🔒</h4>
            <p class="mb-4">Masukkan email lalu kami akan mengirim langkah-langkah untuk mengubah password melalui email</p>
            <div class="alert alert-info" role="alert">
                Jika kesulitan dalam mengubah password, silahkan hubungi Nomor Whatsapp berikut:
                <a href="https://wa.me/{{ settings()->get('no_wa_operator') }}" target="_blank">
                    <span>Link Whatsapp</span>
                </a> 
            </div>
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control" @error('email')
                        is-invalid
                    @enderror
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required autocomplete="email"
                    autofocus
                />
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-3">Kirim Link Reset</button>
            </form>
            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                Kembali ke halaman Login
                </a>
            </div>
            </div>
        </div>
        <!-- /Forgot Password -->
        </div>
    </div>
    </div>
@endsection

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
