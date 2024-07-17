<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\BerkasAttachments;
use App\Models\Bidang;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PemberkasanController extends Controller
{
    public function pemberkasan()
    {
        $user = Auth::user();

        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        if ($user->role == 'admin' || $user->role == 'pengurus') {
            $berkas = Berkas::orderBy('created_at', 'ASC')->get();
            return view('auth.pemberkasan', compact([
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pemberkasan']);
        } else {
            $berkas = Berkas::where('visibility', 'visible')->get();
            return view('auth.anggota.pemberkasan', compact([
                'kelas',
                'unit',
                'bidang',
                'berkas',
                'pemilu'
            ]), ['type_menu' => 'pemberkasan']);
        }
    }

    public function berkasDetail($slug)
    {
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $berkass = Berkas::where('slug', $slug)->first();
        $attachments = BerkasAttachments::where('berkas_id', $berkass->id)->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();

        $user = Auth::user();
        if ($user->role == 'admin' || $user->role == 'pengurus') {
            return view('auth.berkas', compact([
                'kelas',
                'unit',
                'bidang',
                'berkass',
                'berkas',
                'attachments',
                'pemilu'
            ]), ['type_menu' => 'diklat-berkas']);
        } else {
            return view('auth.anggota.berkas', compact([
                'kelas',
                'unit',
                'bidang',
                'berkass',
                'berkas',
                'attachments',
                'pemilu'
            ]), ['type_menu' => 'diklat-berkas']);
        }
    }

    public function addPemberkasan(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'visibility' => 'required',
        ]);

        if (!$fields) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
            ]);
        }

        $toLower = strtolower($request->name);
        $slug = str_replace(' ', '-', $toLower);

        $berkas = new Berkas();
        $berkas->name = $fields['name'];
        $berkas->slug = $slug;
        $berkas->visibility = $fields['visibility'];
        $berkas->save();

        return redirect()->back()->with('status', 'Berkas berhasil ditambahkan');
    }

    public function editPemberkasan(Request $request)
    {
        $slug = $request->query('slug');
        $berkas = Berkas::where('slug', $slug)->first();

        if (!$berkas) {
            abort(404);
        }

        $fields = $request->validate([
            'name' => 'required',
            'visibility' => 'required',
        ]);

        $toLower = strtolower($request->name);
        $slug = str_replace(' ', '-', $toLower);

        $berkas->name = $fields['name'];
        $berkas->slug = $slug;
        $berkas->visibility = $fields['visibility'];
        $berkas->save();

        return redirect()->back()->with('status', 'Berkas berhasil diubah');
    }

    public function deletePemberkasan($slug)
    {
        $berkas = Berkas::where('slug', $slug)->first();

        if (!$berkas) {
            abort(404);
        }

        $berkas->delete();

        return redirect()->back()->with('status', 'Berkas berhasil dihapus');
    }

    public function addBerkasAttachment(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'attachment' => 'required'
        ]);

        if (!$fields) {
            return redirect()->back()->with('status', 'fields error');
        }

        $slug = $request->query('slug');
        $berkas = Berkas::where('slug', $slug)->first();

        $attachments = new BerkasAttachments();
        $attachments->name = $fields['name'];
        $attachments->type = $fields['type'];
        $attachments->berkas_id = $berkas->id;
        if ($fields['type'] == 'file') {
            if ($request->hasFile('attachment')) {
                $attachments->data_path = $request->file('attachment')->store('/berkas/' . $berkas->slug);
            }
        } else if ($fields['type'] == 'link') {
            $attachments->data_path = $fields['attachment'];
        }
        $attachments->save();

        return redirect()->back()->with('status', 'Lampiran berhasil di tambahkan');
    }

    public function deleteBerkasAttachment(Request $request)
    {
        $id = $request->query('attachment_id');
        $attachments = BerkasAttachments::find($id);
        $filePath = $attachments->data_path;
        if ($attachments->type == 'link') {
            $attachments->delete();

            return redirect()->back()->with('status', 'Lampiran berhasil dihapus');
        } else {
            if (!Storage::exists($filePath)) {
                abort(404);
            } else {
                Storage::delete($filePath);
                $attachments->delete();

                return redirect()->back()->with('status', 'Lampiran berhasil dihapus');
            }
        }
    }
}
