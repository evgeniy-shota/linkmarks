<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\Notification;
use App\Models\Context;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            return back();
        }

        return view('auth.registration');
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        try {
            $newUser = DB::transaction(function () use ($request) {
                $user = User::create($request->validated());
                Profile::create([
                    'user_id' => $user->id,
                ]);
                Context::create([
                    'user_id' => $user->id,
                    'name' => 'Root',
                    'is_root' => true,
                    'parent_context_id' => null,
                    'is_enabled' => true,
                    'order' => null,
                ]);

                return $user;
            }, 3);
        } catch (Exception $e) {
            Log::error($e);
            return back()->withErrors(['registrationError' => 'Something is wrong. We are already working on a solution. Try again later.']);
        }

        Auth::loginUsingId($newUser->id);
        event(new Registered($newUser));

        $request->session()->flash('message', 'You have successfully registered. We will send a verification link to your email address.');
        return redirect()->route('home');


        // Mail::to($user)->send(new Notification());
        // return redirect()->route('login', ['message' => 'You have successfully registered.']);
    }
}
