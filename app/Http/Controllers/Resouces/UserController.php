<?php

namespace App\Http\Controllers\Resouces;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['password.confirm']);
    }

    public function settings()
    {
        return view('user.settings',['user' => Auth::user()]);
    }
    public function settingslanguage(Request $request, User $user){
        $user->language = $request->input('lang', $user->language);
        $user->update();
        return redirect()->back()->withErrors(['success' => 'Language updated successfully']);
    }
    public function settingspassword(Request $request, User $user){
        if(strlen($request->input('password'))<8){
            return redirect()->back()->withErrors(['error' => 'Password must be at least 8 characters long']);
        }
        if($request->input('password') !== $request->input('password_confirmation')) {
            return redirect()->back()->withErrors(['error' => 'Passwords do not match']);
        }
        $user->password = bcrypt($request->input('password'));
        $user->update();
        return redirect()->back()->withErrors(['success' => 'Password updated successfully']);
    }
}
