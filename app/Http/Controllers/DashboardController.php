<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Dokumentasi;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use App\Models\Pengurus;
use App\Models\Proker;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        if ($user->role == 'admin') {
            $anggota = Anggota::orderBy('kelas_id', 'asc')->get();
            $pengurus = Pengurus::all();
            $pengurusCount = $pengurus->count();
            $anggotaCount = $anggota->count();
            $tidakPengkacuanCount = Anggota::where('status', 'tidak pengkacuan')->count();
            $pengkacuanCount = Anggota::where('status', 'pengkacuan')->count();

            return view('auth.admin.dashboard', compact([
                'user',
                'anggota',
                'pengurusCount',
                'anggotaCount',
                'pengkacuanCount',
                'tidakPengkacuanCount',
                'unit',
                'kelas',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'dashboard']);
        } else if ($user->role == 'pengurus') {
            $pengurus = Pengurus::where('user_id', $user->id)->first();
            $anggota = Anggota::orderBy('kelas_id', 'asc')->where('unit_id', $pengurus->unit_id)->get();
            $proker = Proker::orderBy('name', 'asc')->where('unit_id', $pengurus->unit_id)->get();
            $dokumentasi = Dokumentasi::all();
            $anggotaCount = $anggota->count();

            return view('auth.pengurus.dashboard', compact([
                'pengurus',
                'proker',
                'anggota',
                'dokumentasi',
                'anggotaCount',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'dashboard']);
        } else if ($user->role == 'anggota') {
            $anggota = Anggota::where('user_id', $user->id)->first();

            return view('auth.anggota.dashboard', compact([
                'anggota',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'dashboard']);
        }
    }
}
