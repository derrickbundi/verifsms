<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\SendCode;
use Illuminate\Http\Request; 

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(Request $request){
        $this->validateLogin($request);
        if($this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        //--------------------------
        if($this->guard()->validate($this->credentials($request))){
            $user = $this->guard()->getLastAttempted();
            if($user->active && $this->attemptLogin($request)){
                return $this->sendLoginResponse($request);
            }else{
                $this->incrementLoginAttempts($request);
                $user->code = SendCode::sendCode($user->phone);
                if($user->save()){
                    return redirect('/verify?phone='.$user->phone);
                }
            }
        }
        //--------------------------
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
}
