<div class="bs-stepper-header">
    <div class="step {{ $activeStep1 ?? '' }}" data-target="#account-details">
        <a href="{{ route('tagihanlainstep.create', ['step' => 1]) }}" type="button" class="step-trigger" aria-selected="true">
            <span class="bs-stepper-circle">1</span>
            <span class="bs-stepper-label mt-1">
                <span class="bs-stepper-title">Pilih Tagihan</span>
                <span class="bs-stepper-subtitle">Pilih Tagihan Untuk</span>
            </span>
        </a>
    </div>
    <div class="line">
        <i class="bx bx-chevron-right"></i>
    </div>
    <div class="step {{ $activeStep2 ?? ''}}" data-target="#personal-info">
        <a href="{{ route('tagihanlainstep.create', ['step' => 2]) }}" type="button" class="step-trigger" aria-selected="false">
            <span class="bs-stepper-circle">2</span>
            <span class="bs-stepper-label mt-1">
                <span class="bs-stepper-title">Pilih Siswa</span>
                <span class="bs-stepper-subtitle">Pilih Data Siswa</span>
            </span>
        </a>
    </div>
    <div class="line">
        <i class="bx bx-chevron-right"></i>
    </div>
    <div class="step {{ $activeStep3 ?? ''}}" data-target="#social-links">
        <a href="{{ route('tagihanlainstep.create', ['step' => 3]) }}" type="button" class="step-trigger" aria-selected="false">
            <button type="button" class="step-trigger" aria-selected="false">
                <span class="bs-stepper-circle">3</span>
                <span class="bs-stepper-label mt-1">
                    <span class="bs-stepper-title">Pilih Biaya</span>
                    <span class="bs-stepper-subtitle">Pilih Biaya Tagihan</span>
                </span>
            </button>
        </a>
    </div>
    <div class="line">
        <i class="bx bx-chevron-right"></i>
    </div>
    <div class="step {{ $activeStep4 ?? ''}}" data-target="#social-links">
        <button type="button" class="step-trigger" aria-selected="false">
            <span class="bs-stepper-circle">4</span>
            <span class="bs-stepper-label mt-1">
                <span class="bs-stepper-title">Selesai</span>
                <span class="bs-stepper-subtitle">Simpan Data</span>
            </span>
        </button>
    </div>
</div>