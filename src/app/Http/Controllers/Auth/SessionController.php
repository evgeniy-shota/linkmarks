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
        if (Auth::user()) {
            return back();
        }
        
        return view('auth.login');
    }

    public function store(StoreSessionRequest $request): RedirectResponse
    {

        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        return back()->withErrors([
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
