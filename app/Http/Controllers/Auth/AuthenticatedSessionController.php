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

        $user = $request->user();

        // KITA PAKSA ARAHKAN LANGSUNG (Tanpa ->intended)
        switch ($user->role) {
            case 'super_admin':
            case 'admin':
                return redirect()->route('admin.dashboard'); // Berubah di sini
                
            case 'cashier':
                return redirect()->route('cashier.transactions'); // Berubah di sini
                
            case 'kitchen':
                return redirect()->route('kitchen.board'); // Berubah di sini
                
            case 'courier':
                return redirect()->route('courier.offers'); // Berubah di sini
                
            case 'customer':
            default:
                return redirect()->route('home'); // Berubah di sini
        }
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
