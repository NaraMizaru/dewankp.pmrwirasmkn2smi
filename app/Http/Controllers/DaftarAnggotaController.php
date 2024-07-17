<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Pengurus;
use App\Models\Unit;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarAnggotaController extends Controller
{
    public function kelolaAnggota()
    {
        $anggota = Anggota::orderBy('unit_id', 'asc');
        $anggotas = $anggota->get();
        $anggotaCount = $anggota->count();
        $pengkacuanCount = $anggota->where('status', 'pengkacuan')->count();
        $tidakPengkacuanCount = Anggota::where('status', 'tidak pengkacuan')->count();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        return view('auth.daftar-anggota.kelola-anggota', compact([
            'anggotas',
            'anggotaCount',
            'pengkacuanCount',
            'tidakPengkacuanCount',
            'kelas',
            'unit',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => 'kelola_anggota']);
    }

    public function detailIndex($username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        return view('auth.anggota.detail', compact([
            'user',
            'anggota',
            'kelas',
            'unit',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => '']);
    }


    public function editIndex($username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        return view('auth.anggota.edit', compact([
            'user',
            'anggota',
            'kelas',
            'unit',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => '']);
    }

    public function edit(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();

        $fields = $request->validate([
            'fullname' => 'required',
            'nis' => 'nullable',
            'username' => 'required',
            'password' => 'required',
            'email' => 'email',
            'no_telp' => 'required',
            'kelas_id' => 'required',
            'bidang_id' => 'required',
            'unit_id' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if (!$fields) {
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.kelola.anggota')->with('status', 'Terjadi kesalahan');
            } else if (Auth::user()->role == 'pengurus') {
                return redirect()->route('pengurus.dashboard')->with('status', 'Terjadi kesalahan');
            }
        }

        $user->fullname = $fields['fullname'];
        $user->username = $fields['username'];
        $user->password = $fields['password'];
        $user->email = $fields['email'];
        $user->no_telp = $fields['no_telp'];

        $toLower = strtolower($user->fullname);
        $folderName = str_replace(' ', '-', $toLower);

        if ($request->has('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_image/' . $folderName);
            $user->profile_image = $imagePath;
        } else {
            unset($fields['profile_image']);
        }

        $user->save();
        $anggota->kelas_id = $fields['kelas_id'];
        $anggota->nis = $fields['nis'];
        $anggota->bidang_id = $fields['bidang_id'];
        $anggota->unit_id = $fields['unit_id'];
        $anggota->save();

        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.kelola.anggota')->with('status', 'Data anggota berhasil di ubah');
        } else if (Auth::user()->role == 'pengurus') {
            return redirect()->route('pengurus.dashboard')->with('status', 'Data anggota berhasil di ubah');
        }
    }

    public function deleteIndex($username)
    {
        $user = User::where('username', $username)->first();
        $user->delete();

        return redirect()->back()->with('status', 'Anggota berhasil di hapus');
    }

    public function upgradeStatus($username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();

        $anggota->status = 'pengkacuan';
        $anggota->save();

        return redirect()->back()->with('status', 'Anggota berhasil di naikan');
    }

    public function upgradeStatusBalok1($username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();

        $user->role = 'pengurus';

        if ($user->save()) {
            $pengurus = new Pengurus();
            $pengurus->user_id = $user->id;
            $pengurus->nis = $anggota->nis;
            $pengurus->status = 'balok 1';
            $pengurus->type = 'inti 14';
            $pengurus->kelas_id = $anggota->kelas_id;
            $pengurus->unit_id = $anggota->unit_id;
            $pengurus->bidang_id = $anggota->bidang_id;

            if ($pengurus->save()) {
                $anggota->delete();
                return redirect()->back()->with('status', 'Anggota berhasil dinaikan');
            }
        }
    }


    public function daftarAnggota(Request $request)
    {
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        if ($request->query('kelas')) {
            $thisKelas = Kelas::where('slug', $request->query('kelas'))->first();
            if (!$thisKelas) {
                return redirect()->back()->with('status', 'Kelas tidak di temukan');
            }

            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.kelas_id', $thisKelas->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();
            $anggotaKelasCount = Anggota::where('kelas_id', $thisKelas->id)->count();
            return view('auth.daftar-anggota.kelas', compact([
                'thisKelas',
                'anggota',
                'anggotaKelasCount',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'kelas']);
        } else if ($request->query('unit')) {
            $thisUnit = Unit::where('slug', $request->query('unit'))->first();
            if (!$thisUnit) {
                return redirect()->back()->with('status', 'Unit tidak di temukan');
            }

            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.unit_id', $thisUnit->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();
            $anggotaUnitCount = Anggota::where('unit_id', $thisUnit->id)->count();
            return view('auth.daftar-anggota.unit', compact([
                'thisUnit',
                'anggota',
                'anggotaUnitCount',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'unit']);
        } else if ($request->query('bidang')) {
            $thisBidang = Bidang::where('slug', $request->query('bidang'))->first();
            if (!$thisBidang) {
                return redirect()->back()->with('status', 'Bidang tidak di temukan');
            }

            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.bidang_id', $thisBidang->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();
            $anggotaBidangCount = Anggota::where('bidang_id', $thisBidang->id)->count();
            return view('auth.daftar-anggota.bidang', compact([
                'thisBidang',
                'anggota',
                'anggotaBidangCount',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'bidang']);
        }
    }

    public function downloadPdf()
    {
        $anggota = Anggota::orderBy('unit_id', 'asc')->get();
        // return view('pdf.anggota', compact('anggota'));
        $pdf = Pdf::loadView('pdf.anggota', ['anggota' => $anggota]);
        return $pdf->download('Daftar Anggota PMR.pdf');
    }

    public function downloadPdfBy(Request $request)
    {
        if ($request->query('kelas')) {
            $slug = $request->query('kelas');
            $thiss = Kelas::where('slug', $slug)->first();
            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.kelas_id', $thiss->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();

            $pdf = Pdf::loadView('pdf.daftar-anggota-by', [
                'anggota' => $anggota,
                'thiss' => $thiss,
                'by' => 'kelas',
            ]);
            return $pdf->download('Daftar Anggota PMR Kelas ' . $thiss->name . '.pdf');
        } else if ($request->query('unit')) {
            $slug = $request->query('unit');
            $thiss = Unit::where('slug', $slug)->first();
            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.unit_id', $thiss->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();

            $pdf = Pdf::loadView('pdf.daftar-anggota-by', [
                'anggota' => $anggota,
                'thiss' => $thiss,
                'by' => 'unit',
            ]);
            return $pdf->download('Daftar Anggota PMR Unit ' . $thiss->name . '.pdf');
        } else if ($request->query('bidang')) {
            $slug = $request->query('bidang');
            $thiss = bidang::where('slug', $slug)->first();
            $anggota = Anggota::join('users', 'anggotas.user_id', '=', 'users.id')
                ->where('anggotas.bidang_id', $thiss->id)
                ->orderBy('users.fullname', 'asc')
                ->select('anggotas.*', 'users.fullname')
                ->get();

            $pdf = Pdf::loadView('pdf.daftar-anggota-by', [
                'anggota' => $anggota,
                'thiss' => $thiss,
                'by' => 'bidang',
            ]);
            return $pdf->download('Daftar Anggota PMR Bidang ' . $thiss->name . '.pdf');
        }
    }
}
