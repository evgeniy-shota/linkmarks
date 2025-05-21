<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{

    public function showEmailForm(Request $request)
    {
        return view('auth.forgot-password');
    }

    public function showPasswordForm(Request $request, string $token)
    {
        $email = $request->only('email')['email'];
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($validated);

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request) {}


    /**
     * Handle the incoming request.
     */
    // public function __invoke(Request $request)
    // {

    // }
}
