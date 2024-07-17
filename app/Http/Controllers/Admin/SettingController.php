<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting()
    {
        // $user = User::where('username', $username)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        $setting = Setting::all();
        return view('auth.admin.setting', compact([
            // 'user',
            'kelas',
            'unit',
            'bidang',
            'berkas',
            'pemilu',
            'setting'
        ]), ['type_menu' => '']);
    }

    public function editSetting(Request $request, $id)
    {
        $fields = $request->validate([
            'value' => 'required'
        ]);

        $setting = Setting::find($id);

        if (!$setting) {
            return redirect()->back()->with('status', 'Setting tidak ditemukan');
        }

        if ($request->hasFile('value')) {
            $filePath = $request->file('value')->store('setting/background-login/');
            $setting->value = $filePath;
        } else {
            $setting->value = $fields['value'];
        }

        $setting->save();

        return redirect()->back()->with('status', 'Setting berhasil di ubah');
    }

    public function resetSetting(Request $request)
    {
        $type = $request->query('reset');
        if ($type == 'anggota') {
            $anggotaList = Anggota::all();
            foreach ($anggotaList as $anggota) {
                $anggota->user()->delete();
                $anggota->delete();
            }

            return redirect()->back()->with('status', 'Data anggota berhasil di reset');
        }
    }
}
