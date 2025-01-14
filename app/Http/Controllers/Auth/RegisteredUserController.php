<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        //id 4 dan 5 tidak diizinkan
        if (auth()->user()->idRole == 4 || auth()->user()->idRole == 5) {
            throw new AuthorizationException('You are not authorized to access this page.');
        }
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        //id 4 dan 5 tidak diizinkan
        $request->validate([
            'npk' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'no' => ['string', 'numeric'],
            'role' => ['required', 'string', 'numeric'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);





        User::create([
            'npk' => $request->npk,
            'idRole' => $request->role,
            'name' => $request->name,
            'noHp' => $request->no ?? null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);



        return redirect()->route('Admin.dashboard');
    }
}