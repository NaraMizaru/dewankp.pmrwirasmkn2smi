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

Route::redirect('/', '/login');

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

Route::prefix('download')->group(function () {
    Route::get('/anggota/pdf', [DaftarAnggotaController::class, 'downloadPdf'])->name('download.anggota.pdf');
    Route::get('/daftar/anggota/pdf', [DaftarAnggotaController::class, 'downloadPdfBy'])->name('download.daftar-anggota-by.pdf');
    Route::get('/dashboard/kelola', [PengelolaanController::class, 'downloadPdf'])->name('download.pdf.uang-kas');
    Route::get('/proker/pdf', [KepengurusanController::class, 'downloadPdf'])->name('download.proker.pdf');
    Route::get('/dokumentasi/{slug}', [GalleryController::class, 'downloadImage'])->name('download.dokumentasi');
    Route::get('/pemilu/statisik/{slug}', [PemiluController::class, 'downloadStatistikPdf'])->name('download.pemilu.statisik');
    Route::get('/pemilu/vote-logs/{slug}', [PemiluController::class, 'downloadLogPdf'])->name('download.pemilu.vote-logs');
});
