<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /** POST /cliente/login  { username, password }  (username = email ou telemóvel) */
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $key = 'cliente-login:'.strtolower($data['username']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'error' => "Demasiadas tentativas. Tente novamente em {$seconds} segundos.",
            ], 429);
        }

        $field = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (! Auth::attempt([$field => $data['username'], 'password' => $data['password']], true)) {
            RateLimiter::hit($key, 60);

            return response()->json(['error' => 'Credenciais inválidas.'], 422);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return response()->json(['success' => true]);
    }

    /** POST /cliente/registo  { nome, email, telemovel, password } */
    public function register(Request $request)
    {
        $key = 'cliente-register:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Demasiados registos. Tente mais tarde.'], 429);
        }

        try {
            $data = $request->validate([
                'nome' => ['required', 'string', 'min:2', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'telemovel' => ['nullable', 'string', 'max:32'],
                'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        RateLimiter::hit($key, 3600);

        // is_admin n'est jamais accepté depuis la requête (hors $fillable).
        $user = User::create([
            'name' => $data['nome'],
            'email' => $data['email'],
            'phone' => $data['telemovel'] ?? null,
            'password' => $data['password'],
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json(['success' => 'Conta criada com sucesso!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
