<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        $anggota = Anggota::where('user_id', $user->id)->first();

        $fields = $request->validate([
            'fullname' => 'required',
            'nis' => 'nullable',
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

        if ($request->has('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile-image/' . $folderName);
            $user->profile_image = $imagePath;
        } else {
            unset($fields['profile_image']);
        }

        $user->save();
        $anggota->nis = $fields['nis'];
        $anggota->save();

        return redirect()->back()->with('status', 'Profile berhasil di ubah');
    }
}
