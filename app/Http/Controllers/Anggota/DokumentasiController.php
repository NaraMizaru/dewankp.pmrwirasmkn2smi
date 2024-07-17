<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Dokumentasi;
use App\Models\DokumentasiAttachment;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    public function dokumentasi()
    {
        $unit = Unit::all();
        $kelas = Kelas::all();
        $bidang = Bidang::all();
        $dokumentasi = Dokumentasi::where('status', 'public')->get();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        
        return view('auth.anggota.dokumentasi', compact([
            'unit',
            'kelas',
            'dokumentasi',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => 'dokumentasi']);
    }

    public function galleryDokumentasi($slug)
    {
        $unit = Unit::all();
        $kelas = Kelas::all();
        $bidang = Bidang::all();
        $dokumentasi = Dokumentasi::where('slug', $slug)->first();
        $attachment = DokumentasiAttachment::where('dokumentasi_id', $dokumentasi->id)->get();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        return view('auth.gallery', compact([
            'unit',
            'kelas',
            'dokumentasi',
            'attachment',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => '']);
    }
}
