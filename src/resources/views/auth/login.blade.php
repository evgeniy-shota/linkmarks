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

        @if (session('message'))
            <div role="alert" class="alert alert-success font-bold mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex justify-center items-center">
            <div class="rounded-md bg-gray-600 w-98/100 px-4 py-3">
                <div class="text-gray-100">Log In</div>

                <form :action="route('login.store')" method="post">
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

                    <x-html.formcontrols.fieldset title='Password'>
                        <x-slot:field>
                            <x-html.formcontrols.input id="password" type="password" placeholder=""
                                :state="$inputHelpers['passwordErrors'] ? false : true" />
                        </x-slot:field>

                        <x-slot:legend>
                            <x-html.formcontrols.fieldset-legend :text="$inputHelpers['passwordHelper']" :errorText="$inputHelpers['passwordErrors']" />
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

            </div>
        </div>
    </x-slot:main>

</x-layout>
