<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <main class="relative mt-0">
        <div class="flex items-start min-h-screen bg-cover bg-center"
            style="
                background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');
            ">
            <span class="absolute inset-0 bg-gray-800 opacity-60"></span>

            <div class="container mx-auto my-auto px-4 py-12">
                <div class="flex justify-center">
                    <div class="w-full lg:w-4/12 md:w-8/12 px-4">
                        <div
                            class="relative flex flex-col min-w-0 break-words bg-white rounded-3xl shadow-xl animate-fade-in-down">

                            <div class="relative p-0 -mt-6 mx-3 z-20">
                                <div class="bg-gray-900 shadow-xl rounded-xl py-3 px-1">
                                    <h4 class="text-white font-bold text-center mt-2 mb-0 text-2xl">Sign in</h4>
                                    <div class="flex justify-center mt-3">
                                        <a class="p-3 mr-auto ml-2" href="javascript:;">
                                            <i class="fa fa-facebook text-white text-lg"></i>
                                        </a>
                                        <a class="p-3 px-1" href="javascript:;">
                                            <i class="fa fa-github text-white text-lg"></i>
                                        </a>
                                        <a class="p-3 ml-auto mr-2" href="javascript:;">
                                            <i class="fa fa-google text-white text-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    @method('POST')
                                    <!-- Email Address -->
                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email"
                                            name="email" :value="old('email')" required autofocus
                                            autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <!-- Password -->
                                    <div class="mt-4">
                                        <x-input-label for="password" :value="__('Password')" />

                                        <x-text-input id="password" class="block mt-1 w-full" type="password"
                                            name="password" required autocomplete="current-password" />

                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    {{-- 🛑 ADD THIS HIDDEN FIELD TO THE ADMIN LOGIN FORM ONLY 🛑 --}}
                                    <input type="hidden" name="is_admin_login" value="1">

                                    <!-- Remember Me -->
                                    <div class="block mt-4">
                                        <label for="remember_me" class="inline-flex items-center">
                                            <input id="remember_me" type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                name="remember">
                                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        @if (Route::has('password.request'))
                                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif

                                        <x-primary-button class="ms-3">
                                            {{ __('Log in') }}
                                        </x-primary-button>
                                    </div>
                                </form>

                                <div class="mt-4 text-center">
                                    @if (Route::has('adminRegister'))
                                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            href="{{ route('adminRegister') }}">
                                            {{ __("Don't have an account? Register") }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
