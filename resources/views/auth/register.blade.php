<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <main class="relative mt-0">
        <section>
            <div class="min-h-screen">
                <div class="container mx-auto">
                    <div class="flex flex-wrap -mx-3">

                        <div
                            class="
                                hidden lg:flex lg:w-6/12 
                                h-full my-auto pe-0 absolute 
                                top-0 start-0 text-center justify-center items-center  ps-auto flex-col
                                px-3
                            ">
                            <div
                                class="relative bg-[url('https://i.pinimg.com/736x/6b/71/7a/6b717a3b30cfb1fc4f83da1ce4c3a3bb.jpg')] h-full m-3 p-7 rounded-xl flex  justify-end w-3/4  bg-cover bg-center  
    ">
                            </div>
                        </div>

                        <div
                            class="
                                w-full lg:w-5/12 xl:w-5/12 md:w-7/12 
                                flex flex-col 
                                ml-auto mr-auto lg:ml-auto lg:mr-10 
                                px-3
                            ">
                            <div class="bg-white rounded-xl shadow-xl p-6 mt-16 lg:mt-24">

                                <div class="mb-6">
                                    <h4 class="font-bold text-2xl text-gray-800">Sign Up</h4>
                                    <p class="mb-0 text-gray-600">Enter your details to register</p>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-gray-700 block mb-1" />
                                        <x-text-input id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-gray-900" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700 block mb-1" />
                                        <x-text-input id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-gray-900" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                                    </div>

                                    <div class="mb-4">
                                        <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700 block mb-1" />
                                        <x-text-input id="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                                    </div>

                                    <div class="mb-6">
                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700 block mb-1" />
                                        <x-text-input id="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                                    </div>

                                    <div class="flex flex-col items-center justify-between mt-4">
                                        <button type="submit" class="w-full px-4 py-3 text-white font-bold bg-gray-900 rounded-lg hover:bg-gray-800 transition duration-300">
                                            {{ __('Register') }}
                                        </button>

                                        <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4" href="{{ route('login') }}">
                                            {{ __('Already registered? Login Now') }}
                                        </a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>