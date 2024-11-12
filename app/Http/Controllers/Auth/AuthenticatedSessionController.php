<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Logika untuk mengarahkan pengguna berdasarkan peran
        $user = Auth::user();

        if ($user->role === 'user') {
            return redirect()->route('dashboard.user');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard.staf');
        } elseif ($user->role === 'superadmin') {
            return redirect()->route('dashboard.direktur');
        }

        // Default redirect jika peran tidak dikenali
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}