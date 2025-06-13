<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{

    public function index(Request $request)
    {
        return view('auth.login');
    }

    public function store(StoreSessionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $rememberUser = $validated['rememberUser'] ?? false;
        unset($validated['rememberUser']);

        if (Auth::attempt($validated, $rememberUser)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withInput()->withErrors([
            'email' => 'Incorrect email address or password',
            'password' => 'Incorrect email address or password',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
