@props(['changePassError' => null])

<form action="{{ route('changePassword.update') }}" method="post">
    @csrf

    <x-html.formcontrols.fieldset title="Current Password">
        <x-slot:field>
            <x-html.formcontrols.input required id="current_password"
                type="password" class="w-full" :state="!$errors->has('current_password')" />
        </x-slot:field>
        <x-slot:legend>
            @error('current_password')
                <div class="text-rose-300">
                    {{ $message }}
                </div>
            @enderror
            <div>
                Enter a your current password
            </div>
        </x-slot:legend>
    </x-html.formcontrols.fieldset>

    <x-html.formcontrols.fieldset title="New Password">
        <x-slot:field>
            <x-html.formcontrols.input required id="password" type="password"
                class="w-full" :state="!$errors->has('password')" />
        </x-slot:field>
        <x-slot:legend>
            @error('password')
                <div class="text-rose-300">
                    {{ $message }}
                </div>
            @enderror
            <div>
                Enter a new password, it must contain at least 8
                characters, including at least one number, letter
                and symbol
            </div>
        </x-slot:legend>
    </x-html.formcontrols.fieldset>

    <x-html.formcontrols.fieldset title="Confirm Password">
        <x-slot:field>
            <x-html.formcontrols.input required id="password_confirmation"
                type="password" class="w-full" :state="!$errors->has('password_confirmation')" />
        </x-slot:field>
        <x-slot:legend>
            @error('password_confirmation')
                <div class="text-rose-300">
                    {{ $message }}
                </div>
            @enderror
            <div>
                Enter the same password
            </div>
        </x-slot:legend>
    </x-html.formcontrols.fieldset>

    <div class="flex justify-center gap-2">
        <x-html.button-out-gray type="reset" class="w-1/3">
            Clear
        </x-html.button-out-gray>
        <x-html.button-out-green type="submit" class="w-1/3">
            Save
        </x-html.button-out-green>
    </div>

    {{-- <x-html.formcontrols.input id="token" hidden readonly
        :value="$token" /> --}}

</form>
