<x-layout>

    <x-slot:main>
        <div class="flex justify-center items-center">
            <div class="rounded-md bg-gray-600 w-1/1 md:w-1/2 px-4 py-3">
                <div class="text-gray-100">Profile</div>

                <fieldset class="fieldset text-gray-100">
                    <legend class="fieldset-legend text-gray-100">Email</legend>
                    {{-- <input type="email" readonly class="input bg-gray-700" placeholder="example@email.com" /> --}}
                    <div class="input bg-gray-700"> {{ $email ?? 'not found' }} </div>
                </fieldset>

                <fieldset class="fieldset text-gray-100">
                    <legend class="fieldset-legend text-gray-100">Username</legend>
                    {{-- <input type="email" readonly class="input bg-gray-700" placeholder="example@email.com" /> --}}
                    <div class="input bg-gray-700"> {{ $username ?? 'not found' }} </div>
                </fieldset>

                <fieldset class="fieldset text-gray-100">
                    <legend class="fieldset-legend text-gray-100">Date of registration</legend>
                    {{-- <input type="email" readonly class="input bg-gray-700" placeholder="example@email.com" /> --}}
                    <div class="input bg-gray-700"> {{ $dateOfRegistration ?? 'not found' }} </div>
                </fieldset>

                <div class="divider divider-start text-gray-100">Settings</div>

                {{-- <div class='text-gray-100'>Settings</div> --}}

                <form action="" method="post">
                    @csrf
                    <div class="border-1 rounded-md border-gray-700 p-2">

                        <fieldset class="fieldset text-gray-100">
                            <legend class="fieldset-legend text-gray-100 text-base">Bookmarks view</legend>
                            <div class="flex justify-left items-center gap-1">
                                <input id='radioHorizontalBookmarksView' type="radio" name="radio-1" class="radio radio-xs bg-gray-100 border-gray-700 checked:text-gray-700 checked:border-gray-700 checked:bg-gray-100" checked="checked" />
                                <label for='radioHorizontalBookmarksView' class="cursor-pointer text-base">Horizontal view</label>
                            </div>

                            <div class="flex justify-left items-center gap-1">
                                <input id='radioSquareBookmarksView' type="radio" name="radio-1" class="radio radio-xs bg-gray-100 border-gray-700 checked:text-gray-700 checked:border-gray-700 checked:bg-gray-100" />
                                <label for='radioSquareBookmarksView' class="cursor-pointer text-base">Square view</label>

                            </div>
                        </fieldset>

                        <fieldset class="fieldset text-gray-100">
                            <legend class="fieldset-legend text-gray-100">Password</legend>
                            <input type="password" class="input bg-gray-700" minlength="8" maxlength="32"
                                placeholder="My awesome page" />
                            <p class="label">Enter your password</p>
                        </fieldset>

                        <div class="flex justify-around items-center">
                            <button class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                                Clear
                            </button>

                            <button type="submit"
                                class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                                Save
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </x-slot:main>

</x-layout>