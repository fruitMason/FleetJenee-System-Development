<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function signIn(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Log::info(Auth::user()->type);
            if (\auth()->user()->type == 'DRIVER') {
                return redirect()->route('driver.dashboard');
            }
            if (\auth()->user()->type == 'MECHANIC') {
                return redirect()->route('mechanic.dashboard');
            }
            if (\auth()->user()->type == 'ACCOUNT') {
                return redirect()->route('account.dashboard');
            }
            return redirect()->route('dashboard');
        } else {
            return back()->withErrors(['msg' => 'Invalid login credentials']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }
}
