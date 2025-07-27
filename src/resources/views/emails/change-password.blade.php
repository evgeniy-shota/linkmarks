@php
    $changePassUrl =
        route('changePassword.change', ['token' => $token]) . "?email=$email";
    // $changePassUrl = url('/change-password/' . "$token?email=$email");
@endphp
<x-emails.mail-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/2 mt-2">
            <div class="flex justify-center items-center">
                <div>
                    <x-logo width="40" />
                </div>
                <div class="text-xl font-bold">linkmarks</div>
            </div>
            <div>
                <div class="mb-2">
                    Hello,
                    <span class="font-bold">{{ $name ?? 'name' }}</span>
                    !
                </div>

                <div class="mb-2">
                    You are receiving this email because we have received a
                    request
                    to <span class="font-semibold">change the password</span> for
                    your account. Clicking the link
                    below
                    will take you to the password change page.
                </div>

                <div class="flex justify-center mb-2 py-2">
                    <a href="{{ $changePassUrl }}"
                        class="bg-gray-500 py-2 px-4 font-semibold rounded border-1 border-amber-400 shadow-lg cursor-pointer">
                        Change Password
                    </a>
                </div>

                <div class="mb-2">
                    This password change link will expire in <span
                        class="font-semibold">20 minutes</span>.
                </div>

                <div
                    class="mb-2 font-semibold border-1 border-amber-400 py-1 px-2 rounded">
                    If you did not request a password change, no further action
                    is
                    required.
                </div>

                <div class="mt-5">
                    Respectfully,
                    <a href="{{ route('home') }}"
                        class="font-bold border-1 rounded border-gray-300 py-1 px-2 cursor-pointer">
                        linkmarks
                    </a>
                </div>
            </div>
        </x-flex-container>
    </x-slot:main>
</x-emails.mail-layout>
