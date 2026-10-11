<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function landing()
    {
        
        if (Auth::check()) {
            return redirect()->route('free-online-dating');
        }
        return view('landing');
    }

    public function signup(Request $req)
    {
        $data = $req->validate([
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = \App\Models\User::create([
            'name'     => strtok($data['email'], '@') ?: 'User',
            'email'    => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            // kalau tabel punya kolom sosial, biarkan null
            'provider'    => $user->provider ?? null,
            'provider_id' => $user->provider_id ?? null,
            'avatar'      => $user->avatar ?? null,
        ]);

        \Illuminate\Support\Facades\Auth::login($user, true);
        $req->session()->regenerate();

        return redirect()->route('free-online-dating')
            ->with('auth_success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function signin(Request $req)
    {
        $credentials = $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $req->boolean('remember');

        if (!\Illuminate\Support\Facades\Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($req->only('email'));
        }

        $req->session()->regenerate();
        return redirect()->intended(route('free-online-dating'))
            ->with('auth_success', 'Berhasil masuk.');
    }

    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing')->with('auth_success', 'Berhasil keluar.');
    }

    // ----- OAuth -----
     protected function validateProvider(string $provider)
    {
        abort_unless(in_array($provider, ['google','facebook']), 404);
        return $provider;
    }

    public function oauthRedirect(string $provider)
    {
        $provider = $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function oauthCallback(string $provider)
    {
        $provider = $this->validateProvider($provider);
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            // kalau email sudah ada, sambungkan akun
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name'     => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
                    'password' => Hash::make(str()->random(32)),
                ]
            );

            $user->forceFill([
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar(),
            ])->save();
        }

        Auth::login($user, true);
        session()->regenerate();

        return redirect()->route('free-online-dating')
            ->with('auth_success', 'Berhasil masuk dengan '.ucfirst($provider).'.');
    }
}
