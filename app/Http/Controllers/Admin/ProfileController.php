<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Bidang;
use App\Models\Kelas;
use App\Models\Pemilu;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile($username)
    {
        $user = User::where('username', $username)->first();
        $kelas = Kelas::all();
        $unit = Unit::all();
        $bidang = Bidang::all();
        $berkas = Berkas::orderBy('created_at', 'ASC')->get();
        $pemilu = Pemilu::where('status', 'aktif')->first();
        return view('auth.admin.profile', compact([
            'user',
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

        $fields = $request->validate([
            'fullname' => 'required',
            'username' => 'required',
            'email' => 'email',
            'no_telp' => 'required',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $user->fullname = $fields['fullname'];
        $user->username = $fields['username'];
        $user->email = $fields['email'];
        $user->no_telp = $fields['no_telp'];

        $toLower = strtolower($user->fullname);
        $folderName = str_replace(' ', '-', $toLower);

        if ($request->has('profile_image')) 
        {
            $imagePath = $request->file('profile_image')->store('profile-image/' . $folderName);
            $user->profile_image = $imagePath;
        } else {
            unset($fields['profile_image']);
        }

        $user->save();

        return redirect()->back()->with('status', 'Profile berhasil di ubah');
    }
}
