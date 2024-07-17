<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Dokumentasi;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Pengurus;
use App\Models\UangKas;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengelolaanController extends Controller
{
    public function indexPengelolaan(Request $request)
    {
        $query = $request->query('pengelolaan');
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        if ($query == 'kelas') {
            return view('auth.pengelolaan.kelola-kelas', compact([
                'kelas',
                'bidang',
                'unit',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pengelolaan']);
        } else if ($query == 'unit') {
            return view('auth.pengelolaan.kelola-unit', compact([
                'kelas',
                'bidang',
                'unit',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pengelolaan']);
        } else if ($query == 'bidang') {
            return view('auth.pengelolaan.kelola-bidang', compact([
                'kelas',
                'bidang',
                'unit',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pengelolaan']);
        } else if ($request->query('pengelolaan') == 'uang-kas') {
            $newSaldo = UangKas::orderBy('created_at', 'DESC')->first();
            $newSaldo ? $saldo = $newSaldo->saldo : $saldo = 0;
            $uangKas = UangKas::all();
            // dd($newSaldo);
            return view('auth.pengelolaan.kelola-uang-kas', compact([
                'kelas',
                'bidang',
                'unit',
                'uangKas',
                'saldo',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pengelolaan']);
            // return view('pdf.uang-kas');
        } else if ($request->query('pengelolaan') == 'dokumentasi') {
            $dokumentasi = Dokumentasi::all();
            $berkas = Berkas::orderBy('created_at', 'ASC')->get();
            return view('auth.pengelolaan.kelola-dokumentasi', compact([
                'dokumentasi',
                'kelas',
                'bidang',
                'unit',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pengelolaan']);
        }
    }

    public function addKeUnBi(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
        ]);

        $name = $fields['name'];
        if ($request->query('pengelolaan') == 'kelas') {
            $toLower = strtolower($name);

            $parts = explode(' ', $name, 2);
            $firstPart = $parts[0];
            $remainingPart = $parts[1] ?? '';

            if (is_numeric($firstPart)) {
                $romanNumeral = $this->toRoman($firstPart);
                $toLower = strtolower($romanNumeral . ' ' . $remainingPart);
            } else {
                $toLower = strtolower($name);
            }

            $slug = str_replace(' ', '-', $toLower);

            $kelas = new Kelas([
                'name' => $fields['name'],
                'slug' => $slug
            ]);

            $kelas->save();
            return redirect()->back()->with('status', 'Kelas berhasil ditambahkan');
        } else if ($request->query('pengelolaan') == 'unit') {
            $unitParts = explode('|', $name);
            $unitName = trim($unitParts[0]);

            $toLower = strtolower($unitName);
            $slug = str_replace(' ', '-', $toLower);
            $unit = new Unit([
                'name' => $fields['name'],
                'slug' => $slug
            ]);

            $unit->save();
            return redirect()->back()->with('status', 'Unit berhasil ditambahkan');
        } else if ($request->query('pengelolaan') == 'bidang') {
            $toLower = strtolower($name);
            $slug = str_replace(' ', '-', $toLower);

            $bidang = new Bidang([
                'name' => $fields['name'],
                'slug' => $slug
            ]);

            $bidang->save();
            return redirect()->back()->with('status', 'Bidang berhasil ditambahkan');
        }
    }

    public function editKeUnBi(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
        ]);

        $slug = $request->query('slug');
        $name = $fields['name'];
        if ($request->query('pengelolaan') == 'kelas') {
            $kelas = Kelas::where('slug', $slug)->first();

            $toLower = strtolower($name);

            $parts = explode(' ', $name, 2);
            $firstPart = $parts[0];
            $remainingPart = $parts[1] ?? '';

            if (is_numeric($firstPart)) {
                $romanNumeral = $this->toRoman($firstPart);
                $toLower = strtolower($romanNumeral . ' ' . $remainingPart);
            } else {
                $toLower = strtolower($name);
            }

            $newSlug = str_replace(' ', '-', $toLower);

            $kelas->name = $fields['name'];
            $kelas->slug = $newSlug;
            $kelas->save();

            return redirect()->back()->with('status', 'Kelas berhasil diubah');
        } else if ($request->query('pengelolaan') == 'unit') {
            $unit = Unit::where('slug', $slug)->first();

            $unitParts = explode('|', $name);
            $unitName = trim($unitParts[0]);

            $toLower = strtolower($unitName);
            $newSlug = str_replace(' ', '-', $toLower);
            
            $unit->name = $fields['name'];
            $unit->slug = $newSlug;
            $unit->save();

            return redirect()->back()->with('status', 'Unit berhasil diubah');
        } else if ($request->query('pengelolaan') == 'bidang') {
            $bidang = Bidang::where('slug', $slug)->first();

            $toLower = strtolower($name);
            $newSlug = str_replace(' ', '-', $toLower);

            $bidang->name = $fields['name'];
            $bidang->slug = $newSlug;
            $bidang->save();

            return redirect()->back()->with('status', 'Bidang berhasil diubah');
        }
    }

    public function deleteKeUnBi(Request $request, $slug)
    {
        if ($request->query('pengelolaan') == 'kelas') {
            $kelas = Kelas::where('slug', $slug)->first();
            $kelas->delete();

            return redirect()->back()->with('status', 'Kelas berhasil dihapus');
        } else if ($request->query('pengelolaan') == 'unit') {
            $unit = Unit::where('slug', $slug)->first();
            $unit->delete();

            return redirect()->back()->with('status', 'Unit berhasil dihapus');
        } else if ($request->query('pengelolaan') == 'bidang') {
            $bidang = Bidang::where('slug', $slug)->first();
            $bidang->delete();

            return redirect()->back()->with('status', 'Bidang berhasil dihapus');
        }
    }

    public function addUangKas(Request $request)
    {
        $fields = $request->validate([
            'bulan' => 'required',
            'pemasukan' => 'required',
            'pengeluaran' => 'required'
        ]);

        $newSaldo = UangKas::orderBy('created_at', 'DESC')->first();
        $newSaldo ? $saldo = $newSaldo->saldo : $saldo = 0;
        $uangKas = new UangKas([
            'bulan' => $fields['bulan'],
            'pemasukan' => $fields['pemasukan'],
            'pengeluaran' => $fields['pengeluaran'],
            'saldo' => $saldo + $fields['pemasukan'] - $fields['pengeluaran']
        ]);

        $uangKas->save();
        return redirect()->back()->with('status', 'Uang kas berhasil ditambahkan');
    }

    public function deleteUangKas($id)
    {
        // $id = $request->query('id');

        $uangKas = UangKas::find($id);
        $uangKas->delete();
        return redirect()->back()->with('status', 'Uang kas berhasil dihapus');
    }

    public function addDokumentasi(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $toLower = strtolower($fields['name']);
        $slug = str_replace(' ', '-', $toLower);

        $dokumentasi = new Dokumentasi([
            'name' => $fields['name'],
            'slug' => $slug,
            'status' => $fields['status'],
        ]);
        $dokumentasi->save();

        return redirect()->back()->with('status', 'Dokumentasi berhasil dibuat');
    }

    public function editDokumentasi(Request $request)
    {
        $slug = $request->query('slug');
        $dokumentasi = Dokumentasi::where('slug', $slug)->first();
        
        $fields = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required'
        ]);

        $toLower = strtolower($fields['name']);
        $newSlug = str_replace(' ', '-', $toLower);

        $dokumentasi->name = $fields['name'];
        $dokumentasi->slug = $newSlug;
        $dokumentasi->status = $fields['status'];
        $dokumentasi->save();

        return redirect()->back()->with('status', 'Dokumentasi berhasil diubah');
    }

    public function deleteDokumentasi($slug)
    {
        $dokumentasi = Dokumentasi::where('slug', $slug)->first();
        $dokumentasi->delete();

        return redirect()->back()->with('status', 'Dokumentasi berhasil dihapus');
    }

    public function downloadPdf(Request $request)
    {
        if ($request->query('pengelolaan')) {
            $uangKas = UangKas::orderBy('created_at', 'ASC')->get();
            // return view('pdf.uang-kas', compact('uangKas'));
            $pdf = Pdf::loadView('pdf.uang-kas', ['uangKas' => $uangKas]);
            return $pdf->download('Laporan Uang Kas.pdf');
        }
    }
}
