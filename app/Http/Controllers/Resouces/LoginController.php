<?php

namespace App\Http\Controllers\Resouces;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $result = MainController::sendAPIrequest('post', ['action' => 'login', 'username' => $request->input('username'), 'password' => $request->input('password')]);
        if(count($result)<1){
            return redirect()->back()->withErrors(['error' => 'Login fehlgeschlagen!'])->withInput();
        }
        if (($result['status'] ?? "") === "success") {
            if(User::where('username', $request->input('username'))->exists()){
                $user = User::where('username', $request->input('username'))->first();
            }else{
                $user = User::where('email', $request->input('username'))->first();
            }
            Auth::login($user, true);
            return redirect()->route('home');
        }
        return redirect()->back()->withErrors(['error' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

}
