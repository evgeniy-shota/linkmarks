@php
    $verified = $userData->email_verified_at ? true : false;
    $changePasswordModalName = 'changePasswordModal';
@endphp


<x-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/3 mt-2">
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

            </div>

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
                    <div x-data="deleteTimer" x-init=" {{ session('deleteAccount') != null ? 'startTimer()' : null }}">
                        <x-html.element-blocker :isBlocked="'timerId == null'"
                            :timerValue="'timerValue'">
                            <x-html.button-out-red type="submit"
                                ::disabled="{{ $verified ? '!(timerId == null)' : true }}">
                                <div>
                                    Send deletion link
                                </div>
                            </x-html.button-out-red>
                        </x-html.element-blocker>
                    </div>

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

            document.addEventListener('alpine:init', () => {
                Alpine.data('deleteTimer', () => ({
                    timerValue: 59,
                    timerId: null,

                    startTimer(timeout = 59000) {
                        this.timerId = setInterval(() => {
                            this.timerValue--;
                            console.log('interval');
                        }, 1000);

                        setTimeout(() => {
                            clearInterval(this.timerId);
                            this.timerId = null
                            this.timerValue = 59;
                        }, timeout);
                    },

                }))
            })

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
