<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    private $customMessages =
    [
        'email.required' => 'البريد الألكتروني مطلوب',
        'email.email' => 'يجب ان يكون بصيغة بريد الكتروني',
        'password.required' => 'كلمة السر مطلوبة',
        'name.required' => 'الاسم مطلوب',
    ];

    public function register(Request $request)
    {
        //Validate
        $feilds =   $request->validate([
            //'avatar' => ['file', 'nullable', 'max:300'],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ], $this->customMessages);

        // if ($request->hasFile('avatar')) {
        //     $feilds['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        // }


        //Register
        $user = User::create($feilds);

        //Login
        Auth::login($user);

        //Redirect
        return redirect()->route('Home')->with('message', 'مرحبا بك');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $this->customMessages);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'لايوجد مستخدم بهذا البريد الالكتروني.!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('Home');
    }
}
