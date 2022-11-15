<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Constants\UserConstant;
use App\Constants\Constant;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * @return View
     */
    public function index() : View
    {
        if (Auth::guard('user')->user()) return redirect()->back();
        
        return view('auth.login');
    }

    /**
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function login(Request $request) : RedirectResponse
    {
        try {
            $request->validate([
                UserConstant::COLUMN['email'] => 'required|string|email',
                UserConstant::COLUMN['password'] => 'required|string',
            ]);
            $credentials = $request->only(UserConstant::COLUMN['email'], UserConstant::COLUMN['password']);
            $token       = Auth::guard('user')->attempt($credentials);

            if (!$token || null == $token)  return view('auth.login')->with('msg', 'Sai tài khoản hoặc mật khẩu!!!');

            $user = Auth::guard('user')->user();
    
            switch ($user->role) {
                case UserConstant::ROLE['admin'] :
                case UserConstant::ROLE['manager'] :
                case UserConstant::ROLE['waiter'] :
                    return redirect()->route('dashboard');
                    break;
                case UserConstant::ROLE['guest'] :
                    return redirect()->route('home');
                    break;
                default:
                    return redirect()->route('home');
                    break;
            }
        } catch (\Throwable $th) {
            return redirect()->route('404');
        }
    }

    /**
     * @return RedirectResponse
     */
    public function logout() : RedirectResponse
    {
        Auth::guard('user')->logout();
        session()->forget('cart');

        return redirect()->route('login');
    }
}
