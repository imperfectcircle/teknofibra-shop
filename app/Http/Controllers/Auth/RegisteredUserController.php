<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Http\Helpers\CartHelper as Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use PhpParser\Node\Stmt\TryCatch;

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            $customer = new Customer();
            $names = explode(' ', $user->name);
            $customer->user_id = $user->id;
            $customer->first_name = $names[0];
            $customer->last_name = Arr::last($names);
            $customer->save();

            Auth::login($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Unable to register right now.');
        }

        DB::commit();

        Cart::moveCartItemsIntoDb();

        return redirect(route('home', absolute: false));
    }
}
