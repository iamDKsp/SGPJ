<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('sgpj_user')) {
            return redirect()->route('dashboard');
        }

        $remembered = $request->cookie(Config::get('sgpj.remember_cookie'));
        $users = Config::get('sgpj.users', []);

        if ($remembered && isset($users[$remembered])) {
            $this->storeUserInSession($request, $remembered, $users[$remembered]['name'] ?? $remembered);

            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle the incoming login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->except('password'));
        }

        $username = strtolower($validator->validated()['username']);
        $password = $validator->validated()['password'];

        $users = Config::get('sgpj.users', []);
        $userData = $users[$username] ?? null;

        if (!$userData || !hash_equals($userData['password'] ?? '', $password)) {
            return back()
                ->withErrors(['username' => 'Usuário ou senha inválidos.'])
                ->withInput($request->except('password'));
        }

        $displayName = $userData['name'] ?? ucfirst($username);
        $this->storeUserInSession($request, $username, $displayName);

        $response = redirect()->route('dashboard');

        if ($request->boolean('remember')) {
            $response->withCookie(cookie()->forever(Config::get('sgpj.remember_cookie'), $username));
        } else {
            $response->withoutCookie(Config::get('sgpj.remember_cookie'));
        }

        return $response;
    }

    /**
     * Destroy the authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['sgpj_user', 'sgpj_username']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withoutCookie(Config::get('sgpj.remember_cookie'));
    }

    private function storeUserInSession(Request $request, string $username, string $displayName): void
    {
        $request->session()->put('sgpj_username', $username);
        $request->session()->put('sgpj_user', $displayName);
    }
}
