<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->session()->get('sgpj_user');

        if (!$user) {
            $remembered = $request->cookie(Config::get('sgpj.remember_cookie'));
            $users = Config::get('sgpj.users', []);

            if ($remembered && isset($users[$remembered])) {
                $displayName = $users[$remembered]['name'] ?? ucfirst($remembered);
                $request->session()->put('sgpj_username', $remembered);
                $request->session()->put('sgpj_user', $displayName);
                $user = $displayName;
            } else {
                return redirect()->route('login');
            }
        }

        return view('dashboard.index', [
            'usuario' => $user,
        ]);
    }
}
