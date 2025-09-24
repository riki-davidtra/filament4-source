<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    use ValidatesRequests;

    public function redirect(string $provider)
    {
        $this->validateProvider($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        $this->validateProvider($provider);
        $response = Socialite::driver($provider)->user();

        $user = User::firstWhere(['email' => $response->getEmail()]);

        if (!$user) {
            $user = User::create([
                'name' => $response->getName(),
                'email' => $response->getEmail(),
                'google_id' => $response->getId(),
                'password' => bcrypt(Str::random(16)),
            ]);
            $user->assignRole('user');

            session(['pending_user_id' => $user->id]);
            return redirect()->route('auth.create-password');
        }

        $user->update([$provider . '_id' => $response->getId()]);
        Auth::login($user);

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    protected function validateProvider(string $provider): array
    {
        return $this->getValidationFactory()->make(
            ['provider' => $provider],
            ['provider' => 'in:google']
        )->validate();
    }

    public function create_password()
    {
        if (!session()->has('pending_user_id')) {
            return redirect()->route('login');
        }

        $user = User::findOrFail(session('pending_user_id'));
        $siteName = App::make('settingItems')['site_name']->value ?? 'Site Name';
        $info = "Penggantian kata sandi ini hanya berlaku untuk aplikasi <strong>{$siteName}</strong> dan tidak akan mempengaruhi kata sandi default e-mail resmi Anda (<strong>{$user->email}</strong>).";
        return view('auth.create-password', ['email' => $user->email, 'info' => $info]);
    }

    public function create_password_update(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(session('pending_user_id'));
        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('pending_user_id');

        Auth::login($user);
        return redirect()->route('filament.admin.pages.dashboard');
    }

    public function create_password_skip()
    {
        $user = User::findOrFail(session('pending_user_id'));

        session()->forget('pending_user_id');

        Auth::login($user);
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
