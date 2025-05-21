@php
    $inputHelpers = [
        'emailErrors' => join(PHP_EOL, $errors->get('email')),
        'emailHelper' => 'Enter the email address you used when registering',
        'passwordErrors' => join(PHP_EOL, $errors->get('password')),
        'passwordHelper' => 'Enter your password',
    ];

@endphp

<x-layout>

    <x-slot:main>

        <x-flex-container class="sm:w-1/3 mt-2">
            <div class="text-gray-100 font-bold">Log In</div>

            <form :action="route('login.store')" method="post">
                @csrf

                <x-html.formcontrols.fieldset title='Email'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="email" type="email"
                            class="w-full" placeholder="example@email.com"
                            :state="$inputHelpers['emailErrors']
                                ? false
                                : true" :value="old('email')" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['emailHelper']"
                            :errorText="$inputHelpers['emailErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password" type="password"
                            class="w-full" placeholder="" :state="$inputHelpers['passwordErrors']
                                ? false
                                : true" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordHelper']"
                            :errorText="$inputHelpers['passwordErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <a class="text-sm underline text-gray-200"
                    href="{{ route('password.request') }}">Forgot password?</a>

                <div class="flex justify-around items-center mt-2">
                    <button type="reset"
                        class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                        Clear
                    </button>

                    <button type="submit"
                        class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                        Submit
                    </button>
                </div>
            </form>

            @if (session('message'))
                <script>
                    console.log('log');
                    Alpine.store('alerts').addAlert({{ session('message') }},
                        'success')
                </script>
            @endif

        </x-flex-container>
    </x-slot:main>

</x-layout>
