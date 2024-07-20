<div class="row justify-content-center">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link {{ \Route::is('setting.create') ? 'active' : '' }}"
                    href="{{ route('setting.create') }}">
                    <i class="fa-solid fa-cog me-1"></i><span class="btn-sm">Pengaturan Aplikasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Route::is('settingsekolah.create') ? 'active' : '' }}"
                    href="{{ route('settingsekolah.create') }}">
                    <i class="fa-solid fa-school me-1"></i><span class="btn-sm">Pengaturan Sekolah</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Route::is('settingbendahara.create') ? 'active' : '' }}"
                    href="{{ route('settingbendahara.create') }}">
                    <i class="fa-solid fa-user-tie me-1"></i><span class="btn-sm">Pengaturan Bendahara</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Route::is('settingwhacenter.create') ? 'active' : '' }}"
                    href="{{ route('settingwhacenter.create') }}">
                    <i class="fa-solid fa-comments me-1"></i><span class="btn-sm">Pengaturan Whatsapp</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ \Route::is('settingtoken.index') ? 'active' : '' }}"
                    href="{{ route('settingtoken.index') }}">
                    <i class="fa-solid fa-file me-1"></i><span class="btn-sm">Token</span>
                </a>
            </li>
        </ul>
    </div>
</div>
