<x-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/2 mt-2">
            <div class="text-gray-100 text-lg font-bold mb-2">Profile</div>

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
                            {{ $userData->email_verified_at ? 'email verified' : 'email not verified' }}
                        </x-slot:tip>
                        @if ($userData->email_verified_at)
                            <x-html.icons.check-square />
                        @else
                            <x-html.icons.exclamation-square />
                        @endif
                    </x-html.tooltip>
                </div>
                <div class="input bg-gray-700">
                    {{ $userData->email ?? 'not found' }}
                </div>
            </div>

            <div class="flex flex-col justify-start items-start mb-1">
                <div class="font-bold mb-1">Date of registration</div>
                <div class="input bg-gray-700">
                    {{ $userData->created_at->format('d.m.Y') ?? 'not found' }}
                </div>
            </div>

            <div class="flex flex-col justify-start items-start mb-1">
                <div class="font-bold mb-1">Reset password</div>
                <div class="mb-1">
                    A password reset link will be sent to your email address.
                </div>
                <x-html.button-out-gray>
                    Send link
                </x-html.button-out-gray>
            </div>


            <div class="divider divider-start font-bold text-gray-100">Settings</div>

            <div class="italic text-sky-200">In development ...</div>

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
                <div class="divider divider-error divider-start font-bold mb-1 text-rose-400">
                    Delete accouut
                </div>
                <div>
                    To delete your account, click the "Send deletion link"
                    button. After that, a letter will be sent to your email,
                    confirm the deletion by clicking on the link in the letter.
                </div>
                <div class="mb-1 text-rose-300">
                    All your data will be deleted without the possibility of
                    recovery.
                </div>
                <x-html.button-out-red>
                    Send deletion link
                </x-html.button-out-red>
            </div>


        </x-flex-container>

    </x-slot:main>

</x-layout>
