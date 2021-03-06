<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */


//    protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo;

    public function redirectTo()
    {
        $id = Auth::user()->rola_id;

        if ($id == 1) {
            $this->redirectTo = RouteServiceProvider::HOME;
            return $this->redirectTo;
        }
        if ($id == 3) {
            $this->redirectTo = RouteServiceProvider::AdminHOME;
            return $this->redirectTo;
        }
        if ($id == 4) {
            $this->redirectTo = RouteServiceProvider::DispecerHOME;
            return $this->redirectTo;
        }
        if ($id == 5) {
            $this->redirectTo = RouteServiceProvider::ManazerHOME;
            return $this->redirectTo;
        }
    }

//    protected function authenticated(\Illuminate\Http\Request $request, $user)
//    {
//        if ($request->ajax()){
//            return response()->json([
//                'auth' => auth()->check(),
//                'user' => $user,
//                'redirectPath' => $this->redirectPath(),
//            ]);
//        }
//    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
//        $this->redirectTo = url()->previous();
    }


}
