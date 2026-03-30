<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h1 class="auth-title">{{ __('messages.welcome_back') }}</h1>
        <p class="auth-subtitle">{{ __('messages.login_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" class="auth-label" :value="__('messages.email')" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" class="auth-label" :value="__('messages.password')" />

            <x-text-input id="password" class="auth-input"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="auth-checkbox" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('messages.remember_me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('messages.forgot_password') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="auth-primary">
                {{ __('messages.login_btn') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
