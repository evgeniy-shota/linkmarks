<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('auth.registration');
    }

    public function store(StoreRegistrationRequest $request)
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        return view('auth.login', ['message' => 'You have successfully registered.']);
    }
}
