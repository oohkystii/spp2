@extends('layouts.app_sneat_kepala_sekolah', ['title' => 'Beranda'])

@section('content')
<div class="row">
    <!-- Bagian 1 -->
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->name }}</h5>
                        <p class="mb-4">
                            Klik tombol di bawah untuk melihat informasi Laporan Pembayaran SPP
                        </p>
                        <a href="{{ route('kepala_sekolah.laporanform.create') }}" class="btn btn-sm btn-outline-primary">Lihat Data Laporan</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img
                            src="{{ asset('sneat') }}/assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian 2 -->
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat') }}/assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{ route('kepala_sekolah.siswa.index') }}">Lihat Data Siswa</a>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Siswa</span>
                        <h3 class="card-title mb-2">{{ $totalSiswa }}</h3>
                        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i>Data Siswa</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('sneat') }}/assets/img/icons/unicons/wallet-info.png" alt="Credit Card" class="rounded" />
                            </div>
                        </div>
                        <span>Total Sudah Bayar</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $totalSiswaSudahBayar }} Siswa</h3>
                        <small class="text-success fw-semibold">{{ formatRupiah($totalPembayaran) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Bagian 3 -->
<div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
    <div class="row">
        <div class="col-6 mb-4">
            <div class="card">
                <!-- Konten bagian 3 di sini -->
            </div>
        </div>
    </div>
</div>
</div>
<div class="row mb-3">
<div class="col-md-6">
    <div class="card h-100">
        <div class="card-body">
            {!! $tagihanStatusChart->container() !!}

        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card h-100">
        <div class="card-body">
            {!! $jenisTagihanChart->container() !!}
        </div>
    </div>
</div>
</div>
<script src="{{ $tagihanStatusChart->cdn() }}"></script>
<script src="{{ $jenisTagihanChart->script() }}"></script>
{!! $tagihanStatusChart->script() !!}
{!! $jenisTagihanChart->script() !!}

@endsection