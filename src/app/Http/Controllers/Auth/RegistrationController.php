<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Mail\Notification;
use App\Models\Context;
use App\Models\Profile;
use App\Models\User;
use App\Services\ContextService;
use App\Services\UserServices;
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
    public function __construct(
        protected UserServices $userServices,
        protected ContextService $contextService
    ) {}

    public function index()
    {
        if (Auth::user()) {
            return back();
        }

        return view('auth.registration');
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $newUser = $this->userServices->create($request->validated());

        if (isset($newUser)) {
            Auth::loginUsingId($newUser->id);
            event(new Registered($newUser));
            $request->session()->flash('message', 'You have successfully registered. We will send a verification link to your email address.');
            return redirect()->route('home');
        }

        return back()->withErrors(
            ['registrationError' => 'Something is wrong. We are already working on a solution. Try again later.']
        );
    }
}
