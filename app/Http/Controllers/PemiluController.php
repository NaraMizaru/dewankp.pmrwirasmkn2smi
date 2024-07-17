<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Kandidat;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use App\Models\User;
use App\Models\Voting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PemiluController extends Controller
{
    public function pemilu(Request $request)
    {
        $user = Auth::user();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        if ($request->query('type') == 'dashboard') {
            if ($user->role == 'anggota') {
                abort(403);
            }

            $pemilu = Pemilu::where('status', 'aktif')->first();
            if ($pemilu) {
                $totalUser = User::count();
                $voted = Voting::where('pemilu_id', $pemilu->id)->distinct('user_id')->count('user_id');
                $notVoted = $totalUser - $voted;
                return view('auth.pemilu.dashboard', compact([
                    'user',
                    'kelas',
                    'unit',
                    'bidang',
                    'berkas',
                    'pemilu',
                    'voted',
                    'notVoted',
                ]), ['type_menu' => 'pemilu-dashboard']);
            }
            return view('auth.pemilu.dashboard', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
            ]), ['type_menu' => 'pemilu-dashboard']);
        } else if ($request->query('type') == 'event') {
            if ($user->role == 'anggota') {
                abort(403);
            }

            $pemilu = Pemilu::all();
            if (!$pemilu) {
                abort(404);
            }

            return view('auth.pemilu.event', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pemilu-event']);
        } else if ($request->query('type') == 'kandidat') {
            if ($user->role == 'anggota') {
                abort(403);
            }

            $slug = $request->slug;
            $pemilu = Pemilu::where('slug', $slug)->first();
            if (!$pemilu) {
                abort(404);
            }
            $kandidat = Kandidat::orderBy('created_at', 'ASC')->where('pemilu_id', $pemilu->id)->get();

            return view('auth.pemilu.kandidat', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
                'kandidat',
            ]), ['type_menu' => '']);
        } else if ($request->query('type') == 'pemilihan') {
            $pemilu = Pemilu::where('status', 'aktif')->first();
            if (!$pemilu) {
                return back()->with('status', 'Tidak ada pemilu yang sedang aktif');
            }

            return view('auth.pemilu.pemilihan', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
            ]), ['type_menu' => 'pemilihan']);
        } else if ($request->query('type') == 'result') {
            $slug = $request->slug;
            $pemilu = Pemilu::where('slug', $slug)->first();
            $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->get();
            $totalUser = User::count();
            $voted = Voting::where('pemilu_id', $pemilu->id)->distinct('user_id')->count('user_id');
            $notVoted = $totalUser - $voted;
            

            return view('auth.pemilu.result', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
                'voted',
                'notVoted',
                'totalUser',
                'kandidat',
            ]), ['type_menu' => '']);
        } else if ($request->query('type') == 'vote-logs') {
            $slug = $request->slug;
            $pemilu = Pemilu::where('slug', $slug)->first();
            $voting = Voting::where('pemilu_id', $pemilu->id)->get();
            return view('auth.pemilu.vote-logs', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
                'voting'

            ]), ['type_menu' => '']);
        }
    }

    public function addPemilu(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if (!$fields) {
            return redirect()->back()->with('status', 'error');
        }

        $pemiluExist = Pemilu::all();
        if ($fields['status'] == 'aktif' && $pemiluExist->where('status', 'aktif')->count() >= 1) {
            return redirect()->back()->with('status', 'Masih ada pemilu yang aktif, silahkan nonaktifkan terlebih dahulu');
        }

        $toLower = strtolower($fields['name']);
        $slug = str_replace([' ', '/'], '-', $toLower);

        $pemilu = new Pemilu();
        $pemilu->name = $fields['name'];
        $pemilu->slug = $slug;
        $pemilu->description = $fields['description'];
        $pemilu->status = $fields['status'];
        $pemilu->save();

        return redirect()->back()->with('status', 'Pemilu berhasil di tambahkan');
    }

    public function editPemilu(Request $request, $slug)
    {

        $fields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if (!$fields) {
            return redirect()->back()->with('status', 'error');
        }

        $pemiluExist = Pemilu::all();
        if ($fields['status'] == 'aktif' && $pemiluExist->where('status', 'aktif')->count() >= 1) {
            return redirect()->back()->with('status', 'Masih ada pemilu yang aktif, silahkan nonaktifkan terlebih dahulu');
        }

        $pemilu = Pemilu::where('slug', $slug)->first();
        $pemilu->name = $fields['name'];

        $toLower = strtolower($fields['name']);
        $slug = str_replace([' ', '/'], '-', $toLower);

        $pemilu->slug = $slug;
        $pemilu->description = $fields['description'];
        $pemilu->status = $fields['status'];
        $pemilu->save();

        return redirect()->back()->with('status', 'Pemilu berhasil di ubah');
    }

    public function deletePemilu($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        if (!$pemilu) {
            abort(404);
        }

        $pemilu->delete();
        return redirect()->back()->with('status', 'Pemilu berhasil di hapus');
    }

    public function addKandidat(Request $request, $slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        $fields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'gambar' => 'required',
            'visi-misi' => 'required'
        ]);

        if (!$fields) {
            return redirect()->back()->with('status', 'error');
        }

        $kandidat = new Kandidat();
        $kandidat->name = $fields['name'];
        $kandidat->description = $fields['description'];
        if ($request->hasFile('gambar')) {
            $kandidat->gambar = $request->file('gambar')->store('pemilu/kandidat/' . $pemilu->slug);
        }
        $kandidat->visi_misi = $fields['visi-misi'];
        $kandidat->pemilu_id = $pemilu->id;
        $kandidat->save();

        return redirect()->back()->with('status', 'Kandidat berhasil di tambahkan');
    }

    public function editKandidat(Request $request, $slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();

        $id = $request->query('kandidat_id');
        $kandidat = Kandidat::find($id);

        // dd($kandidat);
        $fields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'gambar' => 'nullable',
            'visi-misi' => 'required'
        ]);

        if (!$kandidat || !$pemilu) {
            abort(404);
        }

        $kandidat->name = $fields['name'];
        $kandidat->description = $fields['description'];
        if ($request->has('gambar')) {
            $filePath = $kandidat->gambar;
            if (!Storage::exists($filePath)) {
                $kandidat->gambar = $request->file('gambar')->store('/pemilu/kandidat/' . $pemilu->slug);
            } else {
                Storage::delete($filePath);
                $kandidat->gambar = $request->file('gambar')->store('/pemilu/kandidat/' . $pemilu->slug);
            }
        } else {
            unset($fields['gambar']);
        }
        $kandidat->visi_misi = $fields['visi-misi'];
        $kandidat->save();

        return redirect()->back()->with('status', 'Kandidat berhasil di ubah');
    }

    public function deleteKandidat(Request $request)
    {
        $id = $request->query('kandidat_id');
        $kandidat = Kandidat::find($id);

        if (!$kandidat) {
            abort(404);
        }

        $filePath = $kandidat->gambar;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            $kandidat->delete();
        }
        return redirect()->back()->with('status', 'Kandidat berhasil di hapus');
    }

    public function pemilihan(Request $request, $slug)
    {
        $user = Auth::user();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        if ($request->query('type') == 'pemilihan') {
            $pemilu = Pemilu::where('slug', $slug)->first();
            $kandidat = Kandidat::orderBy('created_at', 'ASC')->where('pemilu_id', $pemilu->id)->get();

            $userVoted = Voting::where('pemilu_id', $pemilu->id)->where('user_id', $user->id)->first();
            if ($userVoted) {
                return redirect()->back()->with('status', 'Anda sudah melakukan voting, terima kasih atas hak suaranya!!');
            }

            return view('auth.pemilu.pemilihan-join', compact([
                'user',
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu',
                'kandidat',
            ]), ['type_menu' => 'pemilihan']);
        }
    }

    public function voteKandidat(Request $request, $slug)
    {
        $user = Auth::user();

        $id = $request->query('kandidat_id');
        $pemilu = Pemilu::where('slug', $slug)->first();
        $kandidat = Kandidat::where('id', $id)->first();

        $voting = new Voting();
        $voting->pemilu_id = $pemilu->id;
        $voting->kandidat_id = $kandidat->id;
        $voting->user_id = $user->id;
        $voting->save();

        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard.pemilu', ['type' => 'pemilihan'])->with('status', 'Anda telah berhasil memilih, terima kasih telah menggunakan hak suaranya!!');
        } else if ($user->role == 'pengurus') {
            return redirect()->route('pengurus.dashboard.pemilu', ['type' => 'pemilihan'])->with('status', 'Anda telah berhasil memilih, terima kasih telah menggunakan hak suaranya!!');
        } else if ($user->role == 'anggota') {
            return redirect()->route('anggota.dashboard.pemilu', ['type' => 'pemilihan'])->with('status', 'Anda telah berhasil memilih, terima kasih telah menggunakan hak suaranya!!');
        }
    }

    public function downloadStatistikPdf($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        $kandidat = Kandidat::where('pemilu_id', $pemilu->id)->get();
        $voted = Voting::where('pemilu_id', $pemilu->id)->distinct('user_id')->count('user_id');

        $pdf = Pdf::loadView('pdf.statistik', [
            'pemilu' => $pemilu,
            'kandidat' => $kandidat,
            'voted' => $voted,
        ]);

        return $pdf->download('Statistik '. $pemilu->name . '.pdf');
    }

    public function downloadLogPdf($slug)
    {
        $pemilu = Pemilu::where('slug', $slug)->first();
        $votings = Voting::where('pemilu_id', $pemilu->id)->get();

        $pdf = Pdf::loadView('pdf.vote-logs', [
            'pemilu' => $pemilu,
            'votings' => $votings
        ]);

        return $pdf->download('Vote Logs ' . $pemilu->name . '.pdf');
    }
}
