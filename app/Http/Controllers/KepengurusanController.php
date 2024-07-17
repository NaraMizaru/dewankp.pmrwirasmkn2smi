<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Dokumentasi;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Pengurus;
use App\Models\Proker;
use App\Models\Unit;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KepengurusanController extends Controller
{
    public function kepengurusan(Request $request)
    {
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        if ($request->query('type') == 'pengurus') {
            $pengurus = Pengurus::all();
            $pengurusCount = $pengurus->count();
            $balok1Count = Pengurus::where('status', 'balok 1')->count();
            $balok2Count = Pengurus::where('status', 'balok 2')->count();
            return view('auth.kepengurusan.kelola-pengurus', compact([
                'pengurus',
                'pengurusCount',
                'kelas',
                'unit',
                'bidang',
                'balok1Count',
                'balok2Count',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'kepengurusan']);
        } else if ($request->query('type') == 'program-kerja') {
            $dokumentasi = Dokumentasi::all();

            $prokerUnit1 = Proker::orderBy('name', 'asc')->where('unit_id', 1)->get();
            $prokerUnit2 = Proker::orderBy('name', 'asc')->where('unit_id', 2)->get();
            $prokerUnit3 = Proker::orderBy('name', 'asc')->where('unit_id', 3)->get();
            $prokerUnit4 = Proker::orderBy('name', 'asc')->where('unit_id', 4)->get();

            $prokerTerlaksanaCount = Proker::where('status', 'selesai')->count();
            $prokerOngoingCount = Proker::where('status', 'ongoing')->count();
            $prokerTidakTerlaksanaCount = Proker::where('status', 'tidak selesai')->count();
            
            return view('auth.kepengurusan.program-kerja', compact([
                // 'pengurus',
                'dokumentasi',
                'prokerUnit1',
                'prokerUnit2',
                'prokerUnit3',
                'prokerUnit4',
                'prokerTerlaksanaCount',
                'prokerOngoingCount',
                'prokerTidakTerlaksanaCount',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'kepengurusan']);
        }
    }

    public function detailPengurus($username)
    {
        $user = User::where('username', $username)->first();
        $pengurus = Pengurus::where('user_id', $user->id)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        return view('auth.pengurus.detail', compact([
            'user',
            'pengurus',
            'kelas',
            'unit',
            'bidang',
            'berkas',
            'pemilu'
        ]), ['type_menu' => '']);
    }

    public function editPengurus($username)
    {
        $user = User::where('username', $username)->first();
        $pengurus = Pengurus::where('user_id', $user->id)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        return view('auth.pengurus.edit', compact([
            'user',
            'pengurus',
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
        $pengurus = Pengurus::where('user_id', $user->id)->first();

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
            'inti' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        if (!$fields) {
            return redirect()->route('admin.daftar-pengurus.edit')->with('status', 'Terjadi kesalahan');
        }

        $user->fullname = $fields['fullname'];
        $user->username = $fields['username'];
        $user->password = $fields['password'];
        $user->email = $fields['email'];
        $user->no_telp = $fields['no_telp'];

        if ($request->has('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_image');
            $user->profile_image = $imagePath;
        } else {
            unset($fields['profile_image']);
        }

        $user->save();
        $pengurus->kelas_id = $fields['kelas_id'];
        $pengurus->nis = $fields['nis'];
        $pengurus->bidang_id = $fields['bidang_id'];
        $pengurus->unit_id = $fields['unit_id'];
        $pengurus->type = $fields['inti'];
        $pengurus->save();

        return redirect()->route('admin.kepengurusan', ['type' => 'pengurus'])->with('status', 'Data pengurus berhasil di ubah');
    }

    public function deletePengurus($username)
    {
        $user = User::where('username', $username)->first();
        $user->delete();

        return redirect()->back()->with('status', 'Pengurus berhasil di hapus');
    }

    public function upgradeStatusBalok2($username)
    {
        $user = User::where('username', $username)->first();
        $pengurus = Pengurus::where('user_id', $user->id)->first();

        $pengurus->status = 'balok 2';
        $pengurus->save();

        return redirect()->back()->with('status', 'Pengurus berhasil di naikan');
    }

    public function addProker(Request $request)
    {
        $fields = $request->validate([
            'unit_id' => 'required',
            'name' => 'required'
        ]);

        $proker = new Proker();
        $proker->unit_id = $fields['unit_id'];
        $proker->name = $fields['name'];
        $proker->tanggal = null;
        $proker->save();

        return redirect()->back()->with('status', 'Program kerja berhasil ditambahkan');
    }

    public function editProker(Request $request, $id)
    {
        $proker = Proker::find($id);
        $fields = $request->validate([
            'unit_id' => 'required',
            'name' => 'required',
            'tanggal' => 'required',
            'dokumentasi_id' => 'required',
        ]);

        $proker->unit_id = $fields['unit_id'];
        $proker->name = $fields['name'];
        $proker->tanggal = $fields['tanggal'];
        $proker->dokumentasi_id = $fields['dokumentasi_id'];
        $proker->save();

        return redirect()->back()->with('status', 'Program kerja berhasil di ubah');
    }

    public function startProker($id)
    {
        $proker = Proker::find($id);
        $proker->status = 'ongoing';
        $proker->save();

        return redirect()->back()->with('status', 'Program kerja berhasil dimulai');
    }

    public function finishProker(Request $request)
    {
        $id = $request->query('id');
        $proker = Proker::find($id);

        $fields = $request->validate([
            'tanggal' => 'required',
            'dokumentasi_id' => 'nullable',
        ]);
        
        $proker->status = 'selesai';
        $proker->tanggal = $fields['tanggal'];
        $proker->dokumentasi_id = $fields['dokumentasi_id'] == 'NULL' ? NULL : $fields['dokumentasi_id'];
        $proker->save();

        return redirect()->back()->with('status', 'Program kerja berhasil di selesai');
    }

    public function deleteProker($id)
    {
        $proker = Proker::find($id);
        $proker->delete();

        return redirect()->back()->with('status', 'Program kerja berhasil di hapus');
    }

    public function downloadPdf(Request $request)
    {
        if ($request->query('type') == 'all') {
            $unit = Unit::all();
            $prokerUnit1 = Proker::orderBy('name', 'asc')->where('unit_id', 1)->get();
            $prokerUnit2 = Proker::orderBy('name', 'asc')->where('unit_id', 2)->get();
            $prokerUnit3 = Proker::orderBy('name', 'asc')->where('unit_id', 3)->get();
            $prokerUnit4 = Proker::orderBy('name', 'asc')->where('unit_id', 4)->get();
            $pdf = Pdf::loadView('pdf.proker', [
                    'prokerUnit1' => $prokerUnit1,
                    'prokerUnit2' => $prokerUnit2,
                    'prokerUnit3' => $prokerUnit3,
                    'prokerUnit4' => $prokerUnit4,
                    'unit' => $unit,
            ]);
            return $pdf->download('Daftar List Program Kerja PMR.pdf');
        } else {
            $slug = $request->query('type');
            $unit = Unit::where('slug', $slug)->first();
            $proker = Proker::orderBy('name', 'asc')->where('unit_id', $unit->id)->get();
            $pdf = Pdf::loadView('pdf.proker', [
                    'proker' => $proker,
                    'unit' => $unit,
            ]);
            return $pdf->download('Daftar List Program Kerja '. $unit->name. '.pdf');
        }
    }
}
