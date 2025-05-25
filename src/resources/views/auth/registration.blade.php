@php
    $inputHelpers = [
        'emailErrors' => join(PHP_EOL, $errors->get('email')),
        'emailHelper' => 'Enter your email',
        'nameErrors' => join(PHP_EOL, $errors->get('name')),
        'nameHelper' => 'Enter your name',
        'passwordErrors' => join(PHP_EOL, $errors->get('password')),
        'passwordHelper' =>
            'Please enter a password that is at least 8 characters long and contains at least one number, letter, and symbol.',
        'passwordConfirmErrors' => join(PHP_EOL, $errors->get('password')),
        'passwordConfirmHelper' => 'Enter the same password again',
    ];

@endphp

<x-layout>

    <x-slot:main>
        <x-flex-container class="sm:w-1/3 mt-2">
            <div class="text-gray-100 font-bold">Registration</div>

            <form :action="route('registration')" method="post">
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

                <x-html.formcontrols.fieldset title='Username'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="name" type="text"
                            class="w-full" placeholder="Tony" :state="$inputHelpers['nameErrors']
                                ? false
                                : true"
                            :value="old('name')" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['nameHelper']"
                            :errorText="$inputHelpers['nameErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password"
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

                <x-html.formcontrols.fieldset title='Confirm Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password_confirmation"
                            type="password" class="w-full" placeholder=""
                            :state="true" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend
                            :text="$inputHelpers['passwordConfirmHelper']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <div class="flex justify-around items-center">
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
            @error('registrationError')
                <script>
                    document.addEventListener('alpine:init', () =>
                        Alpine.store('alerts').addAlert('{{ $message }}', 'warning'))
                </script>
            @enderror
        </x-flex-container>

    </x-slot:main>

</x-layout>
