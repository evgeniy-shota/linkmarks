<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\Notification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('auth.registration');
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        // Mail::to($user)->send(new Notification());
        $request->session()->flash('message', 'You have successfully registered.');
        return redirect()->route('login');
        // return redirect()->route('login', ['message' => 'You have successfully registered.']);
    }
}
