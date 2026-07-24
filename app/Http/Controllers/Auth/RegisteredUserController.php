<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

   /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nim'      => ['required', 'string', 'max:50', 'unique:'.User::class.',email'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,   
            'email'    => $request->nim,    
            'role'     => 'mahasiswa',      
            'password' => Hash::make($request->password),
        ]);

        // 🟢 TEMPEL DI SINI (Menggantikan baris Auth::login lama)
        event(new Registered($user));

        // Auth::login($user); // 🟢 Ini sudah dimatikan/dikomentari agar tidak otomatis login

        // Alihkan ke halaman login sambil membawa pesan sukses alert
        return redirect()->route('login')->with('success', 'Aktivasi akun berhasil! Silakan login menggunakan NIM dan Password kamu.');
    }
}
