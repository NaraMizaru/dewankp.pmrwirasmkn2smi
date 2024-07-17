<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Dokumentasi;
use App\Models\DokumentasiAttachment;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{

    public function index($slug)
    {
        $dokumentasi = Dokumentasi::where('slug', $slug)->first();

        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();

        if (!$dokumentasi) {
            return abort(404);
        }

        $attachments = DokumentasiAttachment::where('dokumentasi_id', $dokumentasi->id)->get();

        return view('auth.gallery', compact([
            'dokumentasi',
            'attachments',
            'kelas',
            'bidang',
            'unit',
            'berkas',
            'pemilu'
        ]), ['type_menu' => 'dokumentasi']);
    }

    public function uploadDokumentasi(Request $request, $slug)
    {
        $dokumentasi = Dokumentasi::where('slug', $slug)->first();
        $toLower = strtolower($dokumentasi->name);
        $folderName = str_replace(' ', '-', $toLower);


        $fields = $request->validate([
            'attachments.*' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request['attachments'] as $attachments) {
                $attachment = new DokumentasiAttachment([
                    'image_path' => $attachments->store('dokumentasi/' . $folderName),
                    'dokumentasi_id' => $dokumentasi->id
                ]);

                $attachment->save();
            }
        }

        return redirect()->back()->with('status', 'Dokumentasi berhasil ditambahkan');
    }

    public function downloadImage(Request $request)
    {
        $id = $request->query('img-id');
        $attachment = DokumentasiAttachment::find($id);
        $filePath = $attachment->image_path;
        $fileName = basename($filePath);

        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('status', 'File not found');
        }

        return Storage::download($filePath, $fileName);
    }

    public function deleteImage(Request $request)
    {
        $id = $request->query('img-id');
        $attachment = DokumentasiAttachment::find($id);
        $filePath = $attachment->image_path;

        if (!Storage::exists($filePath)) {
            return redirect()->back()->with('status', 'File not found');
        } else {
            Storage::delete($filePath);
            $attachment->delete();
            return redirect()->back()->with('status', 'File berhasil dihapus');
        }
    }
}
