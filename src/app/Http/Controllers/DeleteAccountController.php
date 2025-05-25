<?php

namespace App\Http\Controllers;

use App\Mail\DeleteAccount;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DeleteAccountController extends Controller
{
    public function destroy(Request $request, string $token)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        // check time
        $validToken = DB::table('delete_account_tokens')
            ->where('email', $validated['email'])->where('token', $token)
            ->first();


        if ($validToken) {
            $timeDiff = date_diff(new DateTime($validToken->created_at), new DateTime());

            if (
                $timeDiff->y > 0 || $timeDiff->m > 0 || $timeDiff->d > 0
                || $timeDiff->h > 0 || $timeDiff->i > 2
            ) {
                return view('errors.403', ['message' => 'The link has expired']);
            }

            $user = User::where('email', $validated['email'])->first();
            Auth::logout();
            // ??? logoutOtherDevices
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $result = $user->delete();

            if ($result) {
                return view('auth.deleted-account');
            }
        }

        //check token and email -> delete
        return redirect()->route('profile')->withErrors(['' => '']);
    }

    public function sendLink(Request $request)
    {
        $user = Auth::user();

        if (!$user->email_verified_at) {
            return back()->withErrors([
                'deleteAccount' => 'Your email not verified!'
            ]);
        }

        $token = Str::random(60);

        $deleteAccountToken = DB::table('delete_account_tokens')
            ->updateOrInsert(
                ['email' => $user->email,],
                [
                    'token' => $token,
                    'created_at' => new DateTime(),
                ]
            );

        if ($deleteAccountToken) {
            Mail::to($user->email)->queue(new DeleteAccount($token, $user));

            return back()->with([
                'deleteAccount' =>
                'We have sent a link to delete your account. Check your email.'
            ]);
        }

        return back()->withErrors([
            'deleteAccount' => 'Something went wrong... We are already working on a solution to this problem. Please try again later or contact support.'
        ]);
    }
}
