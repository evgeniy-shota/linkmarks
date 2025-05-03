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
        <div class="flex justify-center items-center">
            <div class="rounded-md bg-gray-600 px-4 py-3 w-98/100">
                <div class="text-gray-100">Registration</div>

                <form :action="route('registration')" method="post">
                    @csrf

                    <x-html.formcontrols.fieldset title='Email'>
                        <x-slot:field>
                            <x-html.formcontrols.input id="email" type="email" placeholder="example@email.com"
                                :state="$inputHelpers['emailErrors'] ? false : true" :value="old('email')" />
                        </x-slot:field>

                        <x-slot:legend>
                            <x-html.formcontrols.fieldset-legend :text="$inputHelpers['emailHelper']" :errorText="$inputHelpers['emailErrors']" />
                        </x-slot:legend>
                    </x-html.formcontrols.fieldset>

                    {{-- <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Email</legend>
                        <input id="email" type="email" class="input bg-gray-700" placeholder="example@email.com" />
                        <p class="label">{{ $inputHelpers['emailErrors'] ?? $inputHelpers['emailHelper'] }}</p>
                    </fieldset> --}}

                    <x-html.formcontrols.fieldset title='Username'>
                        <x-slot:field>
                            <x-html.formcontrols.input id="name" type="text" placeholder="Tony" :state="$inputHelpers['nameErrors'] ? false : true"
                                :value="old('name')" />
                        </x-slot:field>

                        <x-slot:legend>
                            <x-html.formcontrols.fieldset-legend :text="$inputHelpers['nameHelper']" :errorText="$inputHelpers['nameErrors']" />
                        </x-slot:legend>
                    </x-html.formcontrols.fieldset>

                    {{-- <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Username</legend>
                        <input id="name" type="text" class="input bg-gray-700" minlength="3"
                            placeholder="Tony" />
                        <p class="label">{{ $inputHelpers['nameErrors'] ?? $inputHelpers['nameHelper'] }}</p>
                    </fieldset> --}}

                    <x-html.formcontrols.fieldset title='Password'>
                        <x-slot:field>
                            <x-html.formcontrols.input id="password" type="password" placeholder=""
                                :state="$inputHelpers['passwordErrors'] ? false : true" />
                        </x-slot:field>

                        <x-slot:legend>
                            <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordHelper']" :errorText="$inputHelpers['passwordErrors']" />
                        </x-slot:legend>
                    </x-html.formcontrols.fieldset>

                    {{-- <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Password</legend>
                        <input id="password" type="password" class="input bg-gray-700" minlength="8" maxlength="32"
                            placeholder="My awesome page" />
                        <p class="label">{{ $inputHelpers['passwordErrors'] ?? $inputHelpers['passwordHelper'] }}</p>
                    </fieldset> --}}

                    <x-html.formcontrols.fieldset title='Confirm Password'>
                        <x-slot:field>
                            <x-html.formcontrols.input id="password_confirmation" type="password" placeholder=""
                                :state="true" />
                        </x-slot:field>

                        <x-slot:legend>
                            <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordConfirmHelper']" />
                        </x-slot:legend>
                    </x-html.formcontrols.fieldset>

                    {{-- <fieldset class="fieldset text-gray-100">
                        <legend class="fieldset-legend text-gray-100">Confirm Password</legend>
                        <input id="password_confirmation" type="password" class="input bg-gray-700" minlength="8"
                            maxlength="32" placeholder="My awesome page" />
                        <p class="label">Enter the same password again</p>
                    </fieldset> --}}

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

            </div>
        </div>
    </x-slot:main>

</x-layout>
