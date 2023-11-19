<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials,$request->remember)) {
            if(!auth()->user()->is_active){
                return Inertia::render('ErrorPages/AccessDenied');
            }
            $request->session()->regenerate();
            if(auth()->user()->hasRole('company-admin')){
                return to_route('company_dashboard');
            }else{
                return to_route('slowtow_dashboard');
            }
            
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

/**
 * Decide dashboard if user is  authenticated
 */
    public function redirect_to_dash()
    {
        if(auth()->user()->hasRole('company-admin')){
            return to_route('company_dashboard');
        }else{
            return to_route('slowtow_dashboard');
        }
    }
    public function logout(Request $request){
        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}
