<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BerandaWaliController;
use App\Http\Controllers\BerandaKepalaSekolahController;
use App\Http\Controllers\BerandaOperatorController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\KartuSppController;
use App\Http\Controllers\KepalaSekolahLaporanFormController;
use App\Http\Controllers\KepalaSekolahLaporanPembayaranController;
use App\Http\Controllers\KepalaSekolahLaporanRekapPembayaranController;
use App\Http\Controllers\KepalaSekolahLaporanTagihanController;
use App\Http\Controllers\KepalaSekolahProfilController;
use App\Http\Controllers\LaporanTagihanController;
use App\Http\Controllers\KepalaSekolahSiswaController;
use App\Http\Controllers\KirimPesanController;
use App\Http\Controllers\KwitansiPembayaranController;
use App\Http\Controllers\LaporanFormController;
use App\Http\Controllers\LaporanPembayaranController;
use App\Http\Controllers\LaporanRekapPembayaran;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\PanduanPembayaranController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembayaranMidtransController;
use App\Http\Controllers\SettingBendaharaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingSekolahController;
use App\Http\Controllers\SettingTokenController;
use App\Http\Controllers\SettingWhacenterController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaImportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagihanBiayaLainController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TagihanLainStep2Controller;
use App\Http\Controllers\TagihanLainStep4Controller;
use App\Http\Controllers\TagihanLainStepController;
use App\Http\Controllers\TagihanUpdateLunas;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaliController;
use App\Http\Controllers\WaliImportController;
use App\Http\Controllers\WaliMuridPembayaranController;
use App\Http\Controllers\WaliMuridProfilController;
use App\Http\Controllers\WaliMuridSiswaController;
use App\Http\Controllers\WaliMuridTagihanController;
use App\Http\Controllers\WaliNotifikasiController;
use App\Http\Controllers\WaliSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('tes', function () {
//     echo $url = URL::temporarySignedRoute(
//         'login.url',
//         now()->addDays(10), 
//         [
//             'pembayaran_id' => 1,
//             'user_id' => 1,
//             'url' => route('pembayaran.show', 1),
//         ]
//     );
// });

Route::get('login/login-url', [LoginController::class, 'loginUrl'])->name('login.url');

Route::get('/', function () {
    return view('landing_page');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('operator')->middleware(['auth', 'auth.operator'])->group(function () {
    //ini route khusus untuk operator
    Route::get('beranda', [BerandaOperatorController::class, 'index'])->name('operator.beranda');
    Route::resource('user', UserController::class);
    Route::resource('wali', WaliController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('walisiswa', WaliSiswaController::class);
    Route::resource('biaya', BiayaController::class);
    Route::resource('tagihan', TagihanController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('setting', SettingController::class);
    Route::resource('settingwhacenter', SettingWhacenterController::class);
    Route::get('settingtoken', [SettingTokenController::class, 'index'])->name('settingtoken.index');
    Route::post('settingtoken', [SettingTokenController::class, 'store'])->name('settingtoken.store');
    Route::get('disconnect', [SettingWhacenterController::class, 'disconnect'])->name('disconnect');
    Route::resource('settingbendahara', SettingBendaharaController::class);
    Route::resource('settingsekolah', SettingSekolahController::class);
    Route::get('delete-biaya-item/{id}', [BiayaController::class, 'deleteItem'])->name('delete-biaya.item');
    Route::get('status/update', [StatusController::class, 'update'])->name('status.update');
    // Laporan
    Route::get('laporanform/create', [LaporanFormController::class, 'create'])->name('laporanform.create');
    Route::get('laporantagihan', [LaporanTagihanController::class, 'index'])->name('laporantagihan.index');
    Route::get('laporanpembayaran', [LaporanPembayaranController::class, 'index'])->name('laporanpembayaran.index');
    Route::get('laporanrekappembayaran', [LaporanRekapPembayaran::class, 'index'])->name('laporanrekappembayaran.index');

    // invoke
    Route::post('tagihanupdatelunas', TagihanUpdateLunas::class)->name('tagihanupdate.lunas');
    Route::resource('jobstatus', JobStatusController::class);
    Route::post('/siswaimport/store', [SiswaImportController::class, 'store'])->name('siswaimport.store');
    Route::post('/waliimport/store', [WaliImportController::class, 'store'])->name('waliimport.store');
    Route::resource('kirimpesan', KirimPesanController::class);
    Route::resource('tagihanlainstep', TagihanLainStepController::class);
    Route::post('tagihanlainstep2', TagihanLainStep2Controller::class)->name('tagihanlainstep2.store');
    Route::get('tagihanlainstep2', TagihanLainStep2Controller::class)->name('tagihanlainstep2.delete');
    Route::post('tagihanlainstep4', TagihanLainStep4Controller::class)->name('tagihanlainstep4.store');
});

// \Imtigger\LaravelJobStatus\ProgressController::routes();

Route::get('login-wali', [LoginController::class, 'showLoginFormWali'])->name('login.wali');
Route::prefix('walimurid')->middleware(['auth', 'auth.wali', 'LogVisits'])->name('wali.')->group(function () {
    //ini route khusus untuk wali-murid
    Route::get('beranda', [BerandaWaliController::class, 'index'])->name('beranda');
    Route::resource('siswa', WaliMuridSiswaController::class);
    Route::resource('pembayaranmidtrans', PembayaranMidtransController::class);
    Route::resource('tagihan', WaliMuridTagihanController::class);
    Route::resource('pembayaran', WaliMuridPembayaranController::class);
    Route::resource('profil', WaliMuridProfilController::class);
    Route::resource('notifikasi', WaliNotifikasiController::class);
});

Route::prefix('kepala-sekolah')->middleware(['auth', 'auth.kepala_sekolah'])->name('kepala_sekolah.')->group(function () {
    // Ini route khusus untuk kepala sekolah
    Route::get('beranda', [BerandaKepalaSekolahController::class, 'index'])->name('beranda');
    Route::resource('siswa', KepalaSekolahSiswaController::class);
    Route::resource('profil', KepalaSekolahProfilController::class);

    // Laporan
    Route::get('laporanform/create', [KepalaSekolahLaporanFormController::class, 'create'])->name('laporanform.create');
    Route::get('laporantagihan', [KepalaSekolahLaporanTagihanController::class, 'index'])->name('laporantagihan.index');
    Route::get('laporanpembayaran', [KepalaSekolahLaporanPembayaranController::class, 'index'])->name('laporanpembayaran.index');
    Route::get('laporanrekappembayaran', [KepalaSekolahLaporanRekapPembayaranController::class, 'index'])->name('laporanrekappembayaran.index');
});



Route::resource('invoice', InvoiceController::class)->middleware('auth');
Route::get('kwitansi-pembayaran/{id}', [KwitansiPembayaranController::class, 'show'])->name('kwitansipembayaran.show')->middleware('auth');
Route::get('kartuspp', [KartuSppController::class, 'index'])->name('kartuspp.index')->middleware('auth');
Route::get('export/excel', [LaporanTagihanController::class, 'exportToExcel'])->name('export.excel')->middleware('auth');
Route::get('export/pembayaran', [LaporanPembayaranController::class, 'exportToExcel'])->name('export.pembayaran')->middleware('auth');
Route::get('export/rekap_pembayaran', [LaporanRekapPembayaran::class, 'exportToExcel'])->name('export.rekap_pembayaran')->middleware('auth');
// Route::get('export/rekap_pembayaran/pdf', [LaporanRekapPembayaran::class, 'exportRekapPembayaranPDF'])->name('export.rekap_pembayaran.pdf');


Route::resource('payment', PaymentController::class);

Route::get('logout', function () {
    Auth::logout();
    return redirect('login');
})->name('logout');
