<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        // 1. Define the validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'designation' => ['required', 'string', 'in:admin,user'], // Validating the hidden field
            'password' => ['required', Rules\Password::defaults()],
            "password_confirmation" => ['required', 'same:password'],
        ];

        // 2. Create the validator instance
        $validator = Validator::make($request->all(), $rules);

        // 3. Check if validation fails
        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'designation' => $request->designation,
        ]);
        event(new Registered($user));
        Auth::login($user);
        return redirect(route('adminDashboard', absolute: false));
    }
}
