@php
    $deleteAccount =
        route('deleteAccount.delete', ['token' => $token]) .
        "?email=$user->email";
    // $changePassUrl = url('/change-password/' . "$token?email=$email");
@endphp
<x-emails.mail-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/2 mt-2">

            <div>
                <div class="mb-2">
                    Hello,
                    <span class="font-bold">{{ $user->name ?? 'name' }}</span>
                    !
                </div>

                <div class="mb-1">
                    You are receiving this email because we have received a
                    request
                    to <span class="font-semibold">delete</span>
                    your account. Clicking the link below will delete your
                    account.
                </div>

                <x-captions.danger class="mb-2">
                    All your data will be deleted without the possibility of
                    recovery.
                </x-captions.danger>

                <div class="flex justify-center mb-2 py-2">
                    <a href="{{ $deleteAccount }}"
                        class="bg-gray-500 py-2 px-4 font-semibold rounded border-1 border-rose-500 shadow-lg cursor-pointer">
                        Delete account
                    </a>
                </div>

                <div class="mb-2">
                    This delete link will expire in <span
                        class="font-semibold">20 minutes</span>.
                </div>

                <x-captions.warning>
                    If you did not request account deletion, no further action
                    is required.
                </x-captions.warning>

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
