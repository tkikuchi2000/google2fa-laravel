<x-guest-layout>
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if($errors->any())
        <div class="w-full mb-4">
            <div class="alert alert-danger">
                <strong>{{$errors->first()}}</strong>
            </div>
        </div>
        @endif

        @php
            $inlineUrl = Google2FA::getQRCodeInline(
                config('app.name'),
                Auth::user()->email,
                Auth::user()->google2fa_secret,
            );
        @endphp
        <div>{!! $inlineUrl !!}</div>

        <form method="POST" action="{{ route('2fa') }}">
            @csrf
            <div class="my-4">
                <p class="text-sm mb-2">Enter 6-digits code from your athenticatior app.</p>
                <label for="one_time_password" value="OTP" />
                <input id="one_time_password"
                       type="text"
                       name="one_time_password"
                       class="block w-full rounded-md shadow-sm border-gray">
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="w-full mx-auto text-white bg-sky-500 border-0 py-2 px-8 focus:outline-none hover:bg-sky-600 rounded text-lg tracking-wider">Continue</button>
            </div>
        </form>

</x-guest-layout>