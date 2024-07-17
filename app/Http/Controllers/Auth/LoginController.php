<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $isAdminLogin = $request->has('admin');
        $isAdmin = $isAdminLogin ? 'admin' : '';

        $loginBg = Setting::where('name', 'background')->first();
        $background = $loginBg->value;
        $check = Auth::check();
        if ($check) {
            if (Auth::user()->remember_token) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('auth.login', compact([
            'isAdmin',
            'loginBg',
        ]));
    }

    public function authLogin(Request $request): RedirectResponse
    {
        // dd($request->has('admin'));
        $fields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $auth = Auth::attempt($fields, $request->has('remember'));
        if ($fields['username'] == 'pmrwirasmkn2smi' && $fields['password'] == 'PMRWira.2SMI' && $request->has('admin')) {
            if ($auth) {
                return redirect()->route('admin.dashboard');
            }
        } else if ($fields['username'] == 'pmrwirasmkn2smi' && $fields['password'] == 'PMRWira.2SMI' && !$request->has('admin')) {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }

        if ($auth) {
            $user = Auth::user();
            if ($user->role == 'pengurus') {
                return redirect()->route('pengurus.dashboard');
            } else {
                return redirect()->route('anggota.dashboard');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }
    }
}
