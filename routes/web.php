<?php

use App\Http\Controllers\DaftarAnggotaController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Anggota\DokumentasiController;
use App\Http\Controllers\Anggota\ProfileController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\KepengurusanController;
use App\Http\Controllers\PemberkasanController;
use App\Http\Controllers\PemiluController;
use App\Http\Controllers\PengelolaanController;
use App\Http\Controllers\Pengurus\ProfileController as PengurusProfileController;
use App\Models\Berkas;
use App\Models\Pemilu;
use App\Models\Proker;
use App\Models\UangKas;
use App\Models\Unit;
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


Route::get('/login', [LoginController::class, 'index'])->name('indexLogin');
Route::get('/register', [RegisterController::class, 'index'])->name('indexRegister');

Route::post('/login', [LoginController::class, 'authLogin'])->name('login');
Route::post('/register', [RegisterController::class, 'authRegister'])->name('register');
Route::post('/{username}/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password');
Route::prefix('/auth')->group(function () {
    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role.admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/profile/{username}/me', [AdminProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/profile/{username}/me', [AdminProfileController::class, 'edit'])->name('admin.edit.profile');

    Route::get('/detail/{username}/anggota', [DaftarAnggotaController::class, 'detailIndex'])->name('admin.daftar-anggota.detail');
    Route::get('/edit/{username}/anggota', [DaftarAnggotaController::class, 'editIndex'])->name('admin.daftar-anggota.edit');
    Route::post('/edit/{username}/anggota', [DaftarAnggotaController::class, 'edit'])->name('admin.edit');
    Route::get('/delete/{username}/anggota', [DaftarAnggotaController::class, 'deleteIndex'])->name('admin.delete.anggota');
    Route::post('/up/{username}/anggota', [DaftarAnggotaController::class, 'upgradeStatus'])->name('admin.anggota.upStatus');
    Route::post('/up/{username}/pengurus/balok1', [DaftarAnggotaController::class, 'upgradeStatusBalok1'])->name('admin.anggota.upStatusBalok1');

    Route::post('/up/{username}/pengurus/balok2', [KepengurusanController::class, 'upgradeStatusBalok2'])->name('pengurus.upStatusBalok2');
    Route::get('/detail/{username}/pengurus', [KepengurusanController::class, 'detailPengurus'])->name('admin.daftar-pengurus.detail');
    Route::get('/edit/{username}/pengurus', [KepengurusanController::class, 'editPengurus'])->name('admin.daftar-pengurus.edit');
    Route::post('/edit/{username}/pengurus', [KepengurusanController::class, 'edit'])->name('admin.pengurus.edit');

    Route::get('/dashboard/kepengurusan', [KepengurusanController::class, 'kepengurusan'])->name('admin.kepengurusan');
    Route::post('/dashboard/kepengurusan/proker/add', [KepengurusanController::class, 'addProker'])->name('admin.addProker');
    Route::post('/dashboard/kepengurusan/proker/start/{id}', [KepengurusanController::class, 'startProker'])->name('admin.startProker');
    Route::post('/dashboard/kepengurusan/proker/finish', [KepengurusanController::class, 'finishProker'])->name('admin.finishProker');
    Route::get('/dashboard/kepengurusan/proker/delete/{id}', [KepengurusanController::class, 'deleteProker'])->name('admin.deleteProker');

    Route::get('/dashboard/kelola', [PengelolaanController::class, 'indexPengelolaan'])->name('admin.pengelolaan');
    Route::post('/dashboard/kelola/add', [PengelolaanController::class, 'addKeUnBi'])->name('admin.pengelolaan.tambah');
    Route::post('/dashboard/kelola/edit', [PengelolaanController::class, 'editKeUnBi'])->name('admin.pengelolaan.edit');
    Route::get('/dashboard/kelola/delete/{slug}', [PengelolaanController::class, 'deleteKeUnBi'])->name('admin.pengelolaan.delete');


    Route::post('/dashboard/kelola/uang-kas/add', [PengelolaanController::class, 'addUangKas'])->name('admin.pengelolaan.uang-kas.tambah');
    Route::get('/dashboard/kelola/uang-kas/{id}/delete', [PengelolaanController::class, 'deleteUangKas'])->name('admin.pengelolaan.uang-kas.delete');

    Route::post('/dashboard/kelola/dokumentasi/add', [PengelolaanController::class, 'addDokumentasi'])->name('admin.pengelolaan.dokumentasi.add');
    Route::post('/dashboard/kelola/dokumentasi/edit', [PengelolaanController::class, 'editDokumentasi'])->name('admin.pengelolaan.dokumentasi.edit');
    Route::get('/dashboard/kelola/dokumentasi/{slug}/delete', [PengelolaanController::class, 'deleteDokumentasi'])->name('admin.pengelolaan.dokumentasi.delete');
    Route::get('/dashboard/gallery/dokumentasi/{slug}', [GalleryController::class, 'index'])->name('admin.gallery.dokumentasi');
    Route::post('/dashboard/gallery/dokumentasi/{slug}/upload', [GalleryController::class, 'uploadDokumentasi'])->name('admin.gallery.dokumentasi.upload');
    Route::get('/dashboard/gallery/dokumentasi/{slug}/delete', [GalleryController::class, 'deleteImage'])->name('admin.gallery.dokumentasi.delete');

    Route::get('/dashboard/kelola/anggota', [DaftarAnggotaController::class, 'kelolaAnggota'])->name('admin.kelola.anggota');
    Route::get('/dashboard/daftar/anggota', [DaftarAnggotaController::class, 'daftarAnggota'])->name('admin.dashboard.daftar.anggota');
    
    Route::get('/dashboard/pemberkasan', [PemberkasanController::class, 'pemberkasan'])->name('admin.pemberkasan');
    Route::post('/dashboard/pemberkasan/add', [PemberkasanController::class, 'addPemberkasan'])->name('admin.pemberkasan.add');
    Route::post('/dashboard/pemberkasan/edit', [PemberkasanController::class, 'editPemberkasan'])->name('admin.pemberkasan.edit');
    Route::get('/dashboard/pemberkasan/{slug}/delete', [PemberkasanController::class, 'deletePemberkasan'])->name('admin.pemberkasan.delete');
    Route::get('/dashboard/berkas/{slug}', [PemberkasanController::class, 'berkasDetail'])->name('admin.berkas.detail');

    Route::post('/dashboard/pemberkasan/attachments/add', [PemberkasanController::class, 'addBerkasAttachment'])->name('admin.pemberkasan.attachment.add');
    Route::get('/dahsboard/pemberkasana/attachments/delete', [PemberkasanController::class, 'deleteBerkasAttachment'])->name('admin.pemberkasana.attachments.delete');

    Route::get('/dashboard/pemilu', [PemiluController::class, 'pemilu'])->name('admin.dashboard.pemilu');
    Route::post('/dashboard/pemilu/add', [PemiluController::class, 'addPemilu'])->name('admin.dashboard.pemilu.add');
    Route::post('/dashboard/pemilu/{slug}/edit', [PemiluController::class, 'editPemilu'])->name('admin.dashboard.pemilu.edit');
    Route::get('/dashboard/pemilu/{slug}/delete', [PemiluController::class, 'deletePemilu'])->name('admin.dashboard.pemilu.delete');
    Route::get('/dashboard/pemilu/{slug}', [PemiluController::class, 'pemilu'])->name('admin.dashboard.pemilu.slug');
    Route::post('/dashboard/pemilu/{slug}/kandidat/add', [PemiluController::class, 'addKandidat'])->name('admin.dashboard.pemilu.kandidat.add');
    Route::post('/dashboard/pemilu/{slug}/kandidat/edit', [PemiluController::class, 'editKandidat'])->name('admin.dashboard.pemilu.kandidat.edit');
    Route::get('/dashboard/pemilu/{slug}/kandidat/delete', [PemiluController::class, 'deleteKandidat'])->name('admin.dashboard.pemilu.kandidat.delete');

    Route::get('/dashboard/pemilu', [PemiluController::class, 'pemilu'])->name('admin.dashboard.pemilu');
    Route::get('/dashboard/pemilu/{slug}/join', [PemiluController::class, 'pemilihan'])->name('admin.dashboard.pemilu.pemilihan');
    Route::post('/dashboard/pemilu/{slug}/vote', [PemiluController::class, 'voteKandidat'])->name('admin.dashboard.pemilu.pemilihan.vote');

    Route::get('/dashboard/settings', [SettingController::class, 'setting'])->name('admin.dashboard.setting');
    Route::post('/dashboard/settings', [SettingController::class, 'resetSetting'])->name('admin.dashboard.setting');
    Route::post('/dashboard/settings/{id}/edit', [SettingController::class, 'editSetting'])->name('admin.dashboard.setting.edit');
});

Route::group(['prefix' => 'pengurus', 'middleware' => ['auth', 'role.pengurus']], function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('pengurus.dashboard');

    Route::get('/profile/{username}/me', [PengurusProfileController::class, 'profile'])->name('pengurus.profile');
    Route::post('/profile/{username}/me', [PengurusProfileController::class, 'edit'])->name('pengurus.edit.profile');

    Route::get('/dashboard/kelola/anggota', [DaftarAnggotaController::class, 'kelolaAnggota'])->name('pengurus.kelola.anggota');
    Route::get('/dashboard/daftar/anggota', [DaftarAnggotaController::class, 'daftarAnggota'])->name('pengurus.dashboard.daftar.anggota');
    Route::get('/detail/{username}/anggota', [DaftarAnggotaController::class, 'detailIndex'])->name('pengurus.daftar-anggota.detail');
    Route::get('/edit/{username}/anggota', [DaftarAnggotaController::class, 'editIndex'])->name('pengurus.daftar-anggota.edit');
    Route::post('/edit/{username}/anggota', [DaftarAnggotaController::class, 'edit'])->name('pengurus.edit');
    Route::get('/delete/{username}/anggota', [DaftarAnggotaController::class, 'deleteIndex'])->name('pengurus.delete.anggota');
    Route::post('/up/{username}/anggota', [DaftarAnggotaController::class, 'upgradeStatus'])->name('pengurus.anggota.upStatus');
    Route::post('/up/{username}/pengurus/balok1', [DaftarAnggotaController::class, 'upgradeStatusBalok1'])->name('pengurus.anggota.upStatusBalok1');

    Route::get('/dashboard/kepengurusan', [KepengurusanController::class, 'kepengurusan'])->name('pengurus.kepengurusan');
    Route::post('/dashboard/kepengurusan/proker/add', [KepengurusanController::class, 'addProker'])->name('pengurus.addProker');
    Route::post('/dashboard/kepengurusan/proker/start/{id}', [KepengurusanController::class, 'startProker'])->name('pengurus.startProker');
    Route::post('/dashboard/kepengurusan/proker/finish', [KepengurusanController::class, 'finishProker'])->name('pengurus.finishProker');
    Route::get('/dashboard/kepengurusan/proker/delete/{id}', [KepengurusanController::class, 'deleteProker'])->name('pengurus.deleteProker');

    Route::get('/dashboard/kelola', [PengelolaanController::class, 'indexPengelolaan'])->name('pengurus.pengelolaan');
    Route::post('/dashboard/kelola/uang-kas/add', [PengelolaanController::class, 'addUangKas'])->name('pengurus.pengelolaan.uang-kas.tambah');
    Route::get('/dashboard/kelola/uang-kas/{id}/delete', [PengelolaanController::class, 'deleteUangKas'])->name('pengurus.pengelolaan.uang-kas.delete');

    Route::post('/dashboard/kelola/dokumentasi/add', [PengelolaanController::class, 'addDokumentasi'])->name('pengurus.pengelolaan.dokumentasi.add');
    Route::post('/dashboard/kelola/dokumentasi/edit', [PengelolaanController::class, 'editDokumentasi'])->name('pengurus.pengelolaan.dokumentasi.edit');
    Route::get('/dashboard/kelola/dokumentasi/{slug}/delete', [PengelolaanController::class, 'deleteDokumentasi'])->name('pengurus.pengelolaan.dokumentasi.delete');
    Route::get('/dashboard/gallery/dokumentasi/{slug}', [GalleryController::class, 'index'])->name('pengurus.gallery.dokumentasi');
    Route::post('/dashboard/gallery/dokumentasi/{slug}/upload', [GalleryController::class, 'uploadDokumentasi'])->name('pengurus.gallery.dokumentasi.upload');
    Route::get('/dashboard/gallery/dokumentasi/{slug}/delete', [GalleryController::class, 'deleteImage'])->name('pengurus.gallery.dokumentasi.delete');

    Route::get('/dashboard/pemberkasan', [PemberkasanController::class, 'pemberkasan'])->name('pengurus.pemberkasan');
    Route::post('/dashboard/pemberkasan/add', [PemberkasanController::class, 'addPemberkasan'])->name('pengurus.pemberkasan.add');
    Route::post('/dashboard/pemberkasan/edit', [PemberkasanController::class, 'editPemberkasan'])->name('pengurus.pemberkasan.edit');
    Route::get('/dashboard/pemberkasan/{slug}/delete', [PemberkasanController::class, 'deletePemberkasan'])->name('pengurus.pemberkasan.delete');
    Route::get('/dashboard/berkas/{slug}', [PemberkasanController::class, 'berkasDetail'])->name('pengurus.berkas.detail');

    Route::post('/dashboard/pemberkasan/attachments/add', [PemberkasanController::class, 'addBerkasAttachment'])->name('pengurus.pemberkasan.attachment.add');
    Route::get('/dahsboard/pemberkasana/attachments/delete', [PemberkasanController::class, 'deleteBerkasAttachment'])->name('pengurus.pemberkasana.attachments.delete');

    Route::get('/dashboard/pemilu', [PemiluController::class, 'pemilu'])->name('pengurus.dashboard.pemilu');
    Route::post('/dashboard/pemilu/add', [PemiluController::class, 'addPemilu'])->name('pengurus.dashboard.pemilu.add');
    Route::post('/dashboard/pemilu/{slug}/edit', [PemiluController::class, 'editPemilu'])->name('pengurus.dashboard.pemilu.edit');
    Route::get('/dashboard/pemilu/{slug}/delete', [PemiluController::class, 'deletePemilu'])->name('pengurus.dashboard.pemilu.delete');
    Route::get('/dashboard/pemilu/{slug}', [PemiluController::class, 'pemilu'])->name('pengurus.dashboard.pemilu.slug');
    Route::post('/dashboard/pemilu/{slug}/kandidat/add', [PemiluController::class, 'addKandidat'])->name('pengurus.dashboard.pemilu.kandidat.add');
    Route::post('/dashboard/pemilu/{slug}/kandidat/edit', [PemiluController::class, 'editKandidat'])->name('pengurus.dashboard.pemilu.kandidat.edit');
    Route::get('/dashboard/pemilu/{slug}/kandidat/delete', [PemiluController::class, 'deleteKandidat'])->name('pengurus.dashboard.pemilu.kandidat.delete');
    
    Route::get('/dashboard/pemilu', [PemiluController::class, 'pemilu'])->name('pengurus.dashboard.pemilu');
    Route::get('/dashboard/pemilu/{slug}/join', [PemiluController::class, 'pemilihan'])->name('pengurus.dashboard.pemilu.pemilihan');
    Route::post('/dashboard/pemilu/{slug}/vote', [PemiluController::class, 'voteKandidat'])->name('pengurus.dashboard.pemilu.pemilihan.vote');

});


Route::group(['prefix' => 'anggota', 'middleware' => ['auth', 'role.anggota']], function () {
    Route::get('/dashboard/me', [DashboardController::class, 'dashboard'])->name('anggota.dashboard');

    Route::post('/edit/{username}/me', [ProfileController::class, 'edit'])->name('anggota.edit');

    Route::get('/dashboard/program-kerja', function () {
        $unit = Unit::all();

        $prokerUnit1 = Proker::where('unit_id', 1)->get();
        $prokerUnit2 = Proker::where('unit_id', 2)->get();
        $prokerUnit3 = Proker::where('unit_id', 3)->get();
        $prokerUnit4 = Proker::where('unit_id', 4)->get();

        $prokerTerlaksanaCount = Proker::where('status', 'selesai')->count();
        $prokerOngoingCount = Proker::where('status', 'ongoing')->count();
        $prokerTidakTerlaksanaCount = Proker::where('status', 'tidak selesai')->count();

        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        return view('auth.anggota.program-kerja', compact([
            'unit',
            'prokerUnit1',
            'prokerUnit2',
            'prokerUnit3',
            'prokerUnit4',
            'prokerTerlaksanaCount',
            'prokerOngoingCount',
            'prokerTidakTerlaksanaCount',
            'berkas',
            'pemilu'
        ]), ['type_menu' => 'proker']);
    })->name('anggota.proker');

    Route::get('/dashboard/uang-kas', function () {
        $newSaldo = UangKas::orderBy('created_at', 'DESC')->first();
        $newSaldo ? $saldo = $newSaldo->saldo : $saldo = 0;
        $uangKas = UangKas::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        return view('auth.anggota.uang-kas', compact([
            'uangKas',
            'saldo',
            'berkas',
            'pemilu'
        ]), ['type_menu' => 'uang-kas']);
    })->name('anggota.uang-kas');

    Route::get('/dashboard/dokumentasi/', [DokumentasiController::class, 'dokumentasi'])->name('anggota.dokumentasi');
    Route::get('/dashboard/gallery/dokumentasi/{slug}', [GalleryController::class, 'index'])->name('anggota.gallery.dokumentasi');

    Route::get('/dashboard/pemberkasan/', [PemberkasanController::class, 'pemberkasan'])->name('anggota.pemberkasan');
    Route::get('/dashboard/pemberkasan/{slug}', [PemberkasanController::class, 'berkasDetail'])->name('anggota.pemberkasan.detail');

    Route::get('/dashboard/pemilu', [PemiluController::class, 'pemilu'])->name('anggota.dashboard.pemilu');
    Route::get('/dashboard/pemilu/{slug}/join', [PemiluController::class, 'pemilihan'])->name('anggota.dashboard.pemilu.pemilihan');
    Route::post('/dashboard/pemilu/{slug}/vote', [PemiluController::class, 'voteKandidat'])->name('anggota.dashboard.pemilu.pemilihan.vote');

});


Route::prefix('error')->group(function () {
    Route::get('/forbidden-access', function () {
        return view('errors.error-403');
    })->name('forbidden');
});

Route::prefix('download')->group(function () {
    Route::get('/anggota/pdf', [DaftarAnggotaController::class, 'downloadPdf'])->name('download.anggota.pdf');
    Route::get('/daftar/anggota/pdf', [DaftarAnggotaController::class, 'downloadPdfBy'])->name('download.daftar-anggota-by.pdf');
    Route::get('/dashboard/kelola', [PengelolaanController::class, 'downloadPdf'])->name('download.pdf.uang-kas');
    Route::get('/proker/pdf', [KepengurusanController::class, 'downloadPdf'])->name('download.proker.pdf');
    Route::get('/dokumentasi/{slug}', [GalleryController::class, 'downloadImage'])->name('download.dokumentasi');
    Route::get('/pemilu/statisik/{slug}', [PemiluController::class, 'downloadStatistikPdf'])->name('download.pemilu.statisik');
    Route::get('/pemilu/vote-logs/{slug}', [PemiluController::class, 'downloadLogPdf'])->name('download.pemilu.vote-logs');
});



Route::redirect('/', '/login');

// Dashboard
Route::get('/dashboard-general-dashboard', function () {
    return view('pages.dashboard-general-dashboard', ['type_menu' => 'dashboard']);
});
Route::get('/dashboard-ecommerce-dashboard', function () {
    return view('pages.dashboard-ecommerce-dashboard', ['type_menu' => 'dashboard']);
});


// Layout
Route::get('/layout-default-layout', function () {
    return view('pages.layout-default-layout', ['type_menu' => 'layout']);
});

// Blank Page
Route::get('/blank-page', function () {
    return view('pages.blank-page', ['type_menu' => '']);
});

// Bootstrap
Route::get('/bootstrap-alert', function () {
    return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-badge', function () {
    return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-breadcrumb', function () {
    return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-buttons', function () {
    return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-card', function () {
    return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-carousel', function () {
    return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-collapse', function () {
    return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-dropdown', function () {
    return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-form', function () {
    return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-list-group', function () {
    return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-media-object', function () {
    return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-modal', function () {
    return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-nav', function () {
    return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-navbar', function () {
    return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-pagination', function () {
    return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-popover', function () {
    return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-progress', function () {
    return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-table', function () {
    return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-tooltip', function () {
    return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-typography', function () {
    return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
});


// components
Route::get('/components-article', function () {
    return view('pages.components-article', ['type_menu' => 'components']);
});
Route::get('/components-avatar', function () {
    return view('pages.components-avatar', ['type_menu' => 'components']);
});
Route::get('/components-chat-box', function () {
    return view('pages.components-chat-box', ['type_menu' => 'components']);
});
Route::get('/components-empty-state', function () {
    return view('pages.components-empty-state', ['type_menu' => 'components']);
});
Route::get('/components-gallery', function () {
    return view('pages.components-gallery', ['type_menu' => 'components']);
});
Route::get('/components-hero', function () {
    return view('pages.components-hero', ['type_menu' => 'components']);
});
Route::get('/components-multiple-upload', function () {
    return view('pages.components-multiple-upload', ['type_menu' => 'components']);
});
Route::get('/components-pricing', function () {
    return view('pages.components-pricing', ['type_menu' => 'components']);
});
Route::get('/components-statistic', function () {
    return view('pages.components-statistic', ['type_menu' => 'components']);
});
Route::get('/components-tab', function () {
    return view('pages.components-tab', ['type_menu' => 'components']);
});
Route::get('/components-table', function () {
    return view('pages.components-table', ['type_menu' => 'components']);
});
Route::get('/components-user', function () {
    return view('pages.components-user', ['type_menu' => 'components']);
});
Route::get('/components-wizard', function () {
    return view('pages.components-wizard', ['type_menu' => 'components']);
});

// forms
Route::get('/forms-advanced-form', function () {
    return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
});
Route::get('/forms-editor', function () {
    return view('pages.forms-editor', ['type_menu' => 'forms']);
});
Route::get('/forms-validation', function () {
    return view('pages.forms-validation', ['type_menu' => 'forms']);
});

// google maps
// belum tersedia

// modules
Route::get('/modules-calendar', function () {
    return view('pages.modules-calendar', ['type_menu' => 'modules']);
});
Route::get('/modules-chartjs', function () {
    return view('pages.modules-chartjs', ['type_menu' => 'modules']);
});
Route::get('/modules-datatables', function () {
    return view('pages.modules-datatables', ['type_menu' => 'modules']);
});
Route::get('/modules-flag', function () {
    return view('pages.modules-flag', ['type_menu' => 'modules']);
});
Route::get('/modules-font-awesome', function () {
    return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
});
Route::get('/modules-ion-icons', function () {
    return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
});
Route::get('/modules-owl-carousel', function () {
    return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
});
Route::get('/modules-sparkline', function () {
    return view('pages.modules-sparkline', ['type_menu' => 'modules']);
});
Route::get('/modules-sweet-alert', function () {
    return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
});
Route::get('/modules-toastr', function () {
    return view('pages.modules-toastr', ['type_menu' => 'modules']);
});
Route::get('/modules-vector-map', function () {
    return view('pages.modules-vector-map', ['type_menu' => 'modules']);
});
Route::get('/modules-weather-icon', function () {
    return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
});

// auth
Route::get('/auth-forgot-password', function () {
    return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
});
Route::get('/auth-login', function () {
    return view('pages.auth-login', ['type_menu' => 'auth']);
});
Route::get('/auth-login2', function () {
    return view('pages.auth-login2', ['type_menu' => 'auth']);
});
Route::get('/auth-register', function () {
    return view('pages.auth-register', ['type_menu' => 'auth']);
});
Route::get('/auth-reset-password', function () {
    return view('pages.auth-reset-password', ['type_menu' => 'auth']);
});

// error
Route::get('/error-403', function () {
    return view('pages.error-403', ['type_menu' => 'error']);
});
Route::get('/error-404', function () {
    return view('pages.error-404', ['type_menu' => 'error']);
});
Route::get('/error-500', function () {
    return view('pages.error-500', ['type_menu' => 'error']);
});
Route::get('/error-503', function () {
    return view('pages.error-503', ['type_menu' => 'error']);
});

// features
Route::get('/features-activities', function () {
    return view('pages.features-activities', ['type_menu' => 'features']);
});
Route::get('/features-post-create', function () {
    return view('pages.features-post-create', ['type_menu' => 'features']);
});
Route::get('/features-post', function () {
    return view('pages.features-post', ['type_menu' => 'features']);
});
Route::get('/features-profile', function () {
    return view('pages.features-profile', ['type_menu' => 'features']);
});
Route::get('/features-settings', function () {
    return view('pages.features-settings', ['type_menu' => 'features']);
});
Route::get('/features-setting-detail', function () {
    return view('pages.features-setting-detail', ['type_menu' => 'features']);
});
Route::get('/features-tickets', function () {
    return view('pages.features-tickets', ['type_menu' => 'features']);
});

// utilities
Route::get('/utilities-contact', function () {
    return view('pages.utilities-contact', ['type_menu' => 'utilities']);
});
Route::get('/utilities-invoice', function () {
    return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
});
Route::get('/utilities-subscribe', function () {
    return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
});

// credits
Route::get('/credits', function () {
    return view('pages.credits', ['type_menu' => '']);
});
