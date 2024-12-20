<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'L\'indirizzo email o la password non sono corrette.'
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->is_admin) {
            Auth::logout();

            return response([
                'message' => 'L\'utente non è un amministratore.'
            ], 403);
        }
        if (!$user->email_verified_at) {
            Auth::logout();
            return response([
                'message' => 'L\'indirizzo email non è stato verificato'
            ], 403);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }

    public function getUser(Request $request) {
        return new UserResource($request->user());
    }
}
