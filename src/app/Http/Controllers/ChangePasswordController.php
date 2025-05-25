<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Jobs\LogoutOtherDevices;
use App\Mail\ChangePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        $user = User::find(Auth::id());

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password is invalid']);
        }

        $result = $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        // $result = User::where('id', Auth::id())->update([
        //     'password' => Hash::make($validated['password']),
        // ]);

        if ($result) {
            // Auth::logoutOtherDevices($validated['current_password']);
            $request->session()->regenerate();
            LogoutOtherDevices::dispatch($validated['current_password']);
            return back()->with(['status' => 'Password changed succcesfuly!']);
        }

        return back()->withErrors(['changePassword' => 'Password change fail...']);
    }
}
