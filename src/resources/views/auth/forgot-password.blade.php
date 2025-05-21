<x-layout>
    <x-slot:main>
        <x-flex-container class="sm:w-1/2 mt-5">
            <div class="text-lg font-bold">Reset password</div>
            <div class="text-md mb-1">
                Enter the email you used when registering and click send. A
                password reset link will be sent to your email.
            </div>
            <form action="{{ route('passwword.email') }}" method="post">
                @csrf
                {{-- <div class="font-bold text-md">Email</div> --}}
                <div class="flex justify-start items-center gap-2">
                    <x-html.formcontrols.input required id="email"
                        type="email" placeholder="Enter your email here"
                        :state="true" />
                    <x-html.button-out-blue type="submit">
                        Send
                    </x-html.button-out-blue>
                </div>
            </form>

            @if (session('status'))
                <div class="text-green-300 mt-1">
                    {{ session('status') }}
                </div>
            @endif

            @error('email')
                <div class="text-rose-300">
                    {{ $message }}
                </div>
            @enderror

        </x-flex-container>
    </x-slot:main>
</x-layout>
