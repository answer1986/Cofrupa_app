<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Google2FA;

class Google2FAController extends Controller
{
    public function index()
    {
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user = auth()->user();
        $google2fa = app(Google2FA::class);

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            $request->session()->put('2fa_verified', true);
            return redirect()->intended('/');
        }

        return back()->withErrors(['one_time_password' => 'Invalid 2FA code.']);
    }

    public function enable(Request $request)
    {
        $user = auth()->user();
        $google2fa = app(Google2FA::class);

        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.enable-2fa', compact('qrCodeUrl'));
    }

    public function confirmEnable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string',
        ]);

        $user = auth()->user();
        $google2fa = app(Google2FA::class);

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_enable = true;
            $user->save();
            return redirect('/')->with('success', '2FA enabled successfully.');
        }

        return back()->withErrors(['one_time_password' => 'Invalid 2FA code.']);
    }

    public function disable(Request $request)
    {
        $user = auth()->user();
        $user->google2fa_enable = false;
        $user->google2fa_secret = null;
        $user->save();

        return redirect('/')->with('success', '2FA disabled successfully.');
    }
}
