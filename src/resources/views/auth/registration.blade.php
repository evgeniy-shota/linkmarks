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
        <x-flex-container class="sm:w-3/5 md:w-1/2 lg:w-1/4 xl:w-1/5 mt-2">
            <div class="flex justify-center items-center">
                <div class="text-gray-100 font-bold center">Registration</div>
            </div>

            <form :action="route('registration')" method="post">
                @csrf

                <x-html.formcontrols.fieldset title='Email'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="email" type="email" class="w-full"
                            placeholder="example@example.com" :state="$inputHelpers['emailErrors'] ? false : true" :value="old('email')" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['emailHelper']" :errorText="$inputHelpers['emailErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Username'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="name" type="text" class="w-full" placeholder=""
                            :state="$inputHelpers['nameErrors'] ? false : true" :value="old('name')" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['nameHelper']" :errorText="$inputHelpers['nameErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password" type="password" class="w-full" placeholder=""
                            :state="$inputHelpers['passwordErrors'] ? false : true" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordHelper']" :errorText="$inputHelpers['passwordErrors']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-html.formcontrols.fieldset title='Confirm Password'>
                    <x-slot:field>
                        <x-html.formcontrols.input id="password_confirmation" type="password" class="w-full"
                            placeholder="" :state="true" />
                    </x-slot:field>

                    <x-slot:legend>
                        <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordConfirmHelper']" />
                    </x-slot:legend>
                </x-html.formcontrols.fieldset>

                <x-captions.info>
                    <div class="text-sky-200" x-data>
                        By registering, you agree to the <span x-on:click="userAgreementModal.show()"
                            class="text-sky-400 font-semibold underline cursor-pointer mb-1">user
                            agreement</span>.
                    </div>
                </x-captions.info>


                <div class="flex justify-center gap-2 items-center mt-2">
                    <x-html.button-out-gray type="reset">
                        Clear
                    </x-html.button-out-gray>

                    <x-html.button-out-blue type="submit">
                        Register
                    </x-html.button-out-blue>
                </div>
            </form>
            @error('registrationError')
                <script>
                    userAgreementModal.show()
                    document.addEventListener('alpine:init', () =>
                        Alpine.store('alerts').addAlert('{{ $message }}', 'warning'))
                </script>
            @enderror
        </x-flex-container>

        <x-modal-window class="lg:w-full h-3/4" id="userAgreementModal" title="User Agreement">
            <x-user-agreement />
        </x-modal-window>
    </x-slot:main>
</x-layout>
