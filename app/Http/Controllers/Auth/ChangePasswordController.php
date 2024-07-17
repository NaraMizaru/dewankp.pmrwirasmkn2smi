<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request, $username) 
    {
        $fields = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirmation_password' => 'required'
        ]);

        if (!$fields) {
            return redirect()->back()->with('status', 'error');
        }

        $user = User::where('username', $username)->first();

        if ($user->password != $fields['old_password']) {
            return redirect()->back()->with('status', 'Password lama tidak sama');
        }

        if ($user->password == $fields['old_password'] && $fields['new_password'] != $fields['confirmation_password']) {
            return redirect()->back()->with('status', 'Password baru dan password konfirmasi tidak sama');
        }

        if ($user->password == $fields['old_password'] && $fields['old_password'] == $fields['new_password']) {
            return redirect()->back()->with('status', 'Tidak dapat mengganti password, karena password lama dan baru sama');
        }

        if ($user->password == $fields['old_password'] && $fields['new_password'] == $fields['confirmation_password']) {
            $user->password = $fields['new_password'];
            $user->save();
            return redirect()->back()->with('status','success');
        }
    }
}
