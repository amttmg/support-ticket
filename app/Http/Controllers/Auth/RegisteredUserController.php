<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {

        return Inertia::render('Auth/Register', [
            'branches' => Branch::all(),
            'auto_detect_ip' => config('app.auto_detect_ip'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                new AllowedEmailDomain(), // Add the custom rule here
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'branch_id' => config('app.auto_detect_ip') ? 'nullable' : 'required|integer|exists:branches,id',
            'mobile_number' => ['required', 'digits:10', 'unique:' . User::class],
            'emp_no' => 'required|digits:5|unique:' . User::class
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'mobile_number' => $request->mobile_number,
            'emp_no' => $request->emp_no
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
