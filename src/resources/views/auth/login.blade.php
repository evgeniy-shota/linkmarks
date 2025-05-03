<x-layout>

    <x-slot:main>

        @if (isset($message))
            <div role="alert" class="alert alert-success font-bold mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $message }}</span>
            </div>
        @endif

        <div class="flex justify-center items-center">
            <div class="rounded-md bg-gray-600 w-98/100 px-4 py-3">
                <div class="text-gray-100">Log In</div>

                <form action="" method="post">
                    @csrf
                    <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Email</legend>
                        <input type="email" class="input bg-gray-700" placeholder="example@email.com" />
                        <p class="label">Enter the email address you used when registering</p>
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
                            Submit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </x-slot:main>

</x-layout>
