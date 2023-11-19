<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    
    public function index(){
        $user = User::whereId(auth()->id())->first();
        return Inertia::render('Auth/ResetPassword', [
            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request) {
        if($request->password === $request->password_confirmation){
            auth()->user()->update(['password' => Hash::make($request->password)]);
            return back()->with('success', 'Password updated successfully');
        }else{
            return back()->with('error', 'Passwords are not the same');
      }
     }

}
