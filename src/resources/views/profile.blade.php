@php
    $verified = $userData->email_verified_at ? true : false;
    $changePasswordModalName = 'changePasswordModal';
@endphp


<x-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/2 mt-2">
            <div class="text-gray-100 text-lg font-bold mb-2">Profile</div>

            @if (!$verified)
                <x-captions.warning class="mb-2">
                    Your email is not verified. Some functionality is not
                    available.
                </x-captions.warning>
            @endif

            <div class="flex flex-col justify-start items-start mb-2">
                <div class="font-bold mb-1">Name</div>
                <div class="input bg-gray-700">
                    {{ $userData->name ?? 'not found' }}
                </div>
            </div>

            <div class="flex flex-col justify-start items-start mb-2">
                <div class="font-bold flex justify-start items-center mb-1">
                    <div class="me-2">Email</div>
                    <x-html.tooltip>
                        <x-slot:tip>
                            {{ $verified ? 'email verified' : 'email not verified' }}
                        </x-slot:tip>
                        @if ($verified)
                            <x-html.icons.check-square />
                        @else
                            <x-html.icons.exclamation-square />
                        @endif
                    </x-html.tooltip>

                </div>

                <div class="input bg-gray-700 mb-1">
                    {{ $userData->email ?? 'not found' }}
                </div>

                @if (session('verificationStatus'))
                    <x-captions.success class="mb-1 w-full">
                        {{ session('verificationStatus') }}
                    </x-captions.success>
                @endif

                @if (!$verified)
                    <div class="flex-none">
                        <form action="{{ route('verification.send') }}"
                            method="get">
                            <x-html.button-out-yellow type="submit"
                                href="{{ route('verification.send') }}">
                                Send verification link
                            </x-html.button-out-yellow>
                        </form>
                    </div>
                @endif
            </div>

            <div class="flex flex-col justify-start items-start mb-1">
                <div class="font-bold mb-1">Date of registration</div>
                <div class="input bg-gray-700">
                    {{ $userData->created_at->format('d.m.Y') ?? 'not found' }}
                </div>
            </div>

            {{-- change password --}}
            <div x-data class="flex flex-col justify-start items-start mb-1">
                <div class="font-bold mb-1">
                    Change password
                    @if (!$verified)
                        <x-html.badges.warning>
                            not available
                        </x-html.badges.warning>
                    @endif
                </div>

                <x-html.button-out-gray
                    action="{{ $changePasswordModalName }}.showModal()"
                    :disabled="!$verified">
                    Change password
                </x-html.button-out-gray>


                {{-- <div class="mb-1">
                    A password change link will be sent to your email address.
                </div>

                @error('changePassword')
                    <div class="text-amber-300 mb-1">{{ $message }}</div>
                @enderror

                <form action="{{ route('changePassword.request') }}"
                    method="post">
                    @csrf
                    <x-html.formcontrols.input id="email" hidden readonly
                        type="email" :value="$userData->email" />
                    <x-html.button-out-gray :disabled="!$verified" type="submit">
                        <span>Send link</span>
                    </x-html.button-out-gray>
                </form> --}}
            </div>

            {{-- settings --}}
            {{-- <div class="flex flex-col justify-center items-start">
                <div class="divider divider-start font-bold text-gray-100">
                    Settings
                </div>

                <x-captions.info class="w-full">
                    In development ...
                </x-captions.info>
            </div> --}}


            {{-- <form action="" method="post">
                @csrf
                <div class="border-1 rounded-md border-gray-700 p-2">

                    <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100 text-base">
                            Bookmarks view</legend>
                        <div class="flex justify-left items-center gap-1">
                            <input id='radioHorizontalBookmarksView'
                                type="radio" name="radio-1"
                                class="radio radio-xs bg-gray-100 border-gray-700 checked:text-gray-700 checked:border-gray-700 checked:bg-gray-100"
                                checked="checked" />
                            <label for='radioHorizontalBookmarksView'
                                class="cursor-pointer text-base">Horizontal
                                view</label>
                        </div>

                        <div class="flex justify-left items-center gap-1">
                            <input id='radioSquareBookmarksView' type="radio"
                                name="radio-1"
                                class="radio radio-xs bg-gray-100 border-gray-700 checked:text-gray-700 checked:border-gray-700 checked:bg-gray-100" />
                            <label for='radioSquareBookmarksView'
                                class="cursor-pointer text-base">Square
                                view</label>

                        </div>
                    </fieldset>

                    <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Password
                        </legend>
                        <input type="password" class="input bg-gray-700"
                            minlength="8" maxlength="32"
                            placeholder="My awesome page" />
                        <p class="label">Enter your password</p>
                    </fieldset>

                    <div class="flex justify-around items-center">
                        <button
                            class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                            Clear
                        </button>

                        <button type="submit"
                            class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                            Save
                        </button>
                    </div>
                </div>
            </form> --}}


            <div class="flex flex-col justify-start items-start mb-1">
                <div
                    class="divider divider-error divider-start font-bold mb-1 text-rose-400">
                    Delete account
                    @if (!$verified)
                        <x-html.badges.warning>
                            not available
                        </x-html.badges.warning>
                    @endif
                </div>
                <div>
                    To delete your account, click the "Send deletion link"
                    button. After that, a letter will be sent to your email,
                    confirm the deletion by clicking on the link in the letter.
                </div>

                <x-captions.warning class="w-full my-2">
                    All your data will be deleted without the possibility of
                    recovery.
                </x-captions.warning>

                @if (session('deleteAccount'))
                    <x-captions.success class="w-full mb-2">
                        {{ session('deleteAccount') }}
                    </x-captions.success>
                @endif

                @error('deleteAccount')
                    <x-captions.warning class="mb-1">
                        {{ $message }}
                    </x-captions.warning>
                @enderror

                <form action="{{ route('deleteAccount.email') }}"
                    method="post">
                    @csrf
                    <x-html.button-out-red type="submit" :disabled="!$verified">
                        Send deletion link
                    </x-html.button-out-red>
                </form>
            </div>
        </x-flex-container>

        <x-modal-window :id="$changePasswordModalName" title="Change password">
            <x-captions.warning>
                All sessions on other devices will be closed.
            </x-captions.warning>

            <x-forms.change-password />
        </x-modal-window>

        @if ($errors->has('password') || $errors->has('current_password'))
            <script>
                {{ $changePasswordModalName }}.showModal()
            </script>
        @endif

        <script>
            document.addEventListener('alpine:init', () => addAlerts());

            function addAlerts() {
                @if (session('status'))
                    Alpine.store('alerts')
                        .addAlert("{{ session('status') }}", 'success');
                @endif

                @if (session('verificationStatus'))
                    Alpine.store('alerts')
                        .addAlert("{{ session('verificationStatus') }}", 'success');
                @endif

                @if (session('deleteAccount'))
                    Alpine.store('alerts')
                        .addAlert("{{ session('deleteAccount') }}", 'success');
                @endif
            }
        </script>
    </x-slot:main>
</x-layout>
