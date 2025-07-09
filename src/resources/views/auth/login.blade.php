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

        <x-flex-container x-data class="sm:w-1/5 mt-2">
            <div class="flex justify-center items-center">
                <div class="text-gray-100 font-bold">Log In</div>
            </div>

            <form action="{{ route('login.store') }}" method="post">
                @csrf

                <x-html.formcontrols.fieldset title='Email'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="email" required
                            type="email" class="w-full" :state="$inputHelpers['emailErrors']
                                ? false
                                : true"
                            :value="old('email')" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['emailHelper']"
                            :errorText="$inputHelpers['emailErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password" required
                            type="password" class="w-full" placeholder=""
                            :state="$inputHelpers['passwordErrors']
                                ? false
                                : true" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordHelper']"
                            :errorText="$inputHelpers['passwordErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <div class="mb-2">
                    <a class="text-sm underline text-gray-200 "
                        href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                </div>

                <x-html.formcontrols.checkbox id="rememberUser">
                    <x-slot:label>
                        Remember me
                    </x-slot:label>
                </x-html.formcontrols.checkbox>

                <div class="flex justify-center gap-2 items-center mt-2">
                    <x-html.button-out-gray type="reset">
                        Clear
                    </x-html.button-out-gray>

                    <x-html.button-out-blue type="submit">
                        Submit
                    </x-html.button-out-blue>
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
