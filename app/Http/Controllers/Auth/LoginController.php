<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginUrl(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $user = Auth::loginUsingId($request->user_id);
        return redirect($request->url); 
    }

    public function showLoginForm()
    {
        return view('auth.login_sneat');
    }
    public function showLoginFormWali()
    {
        return view('auth.login_sneat_wali');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->akses == 'operator' || $user->akses == 'admin') {
            return redirect()->route('operator.beranda');
        } elseif ($user->akses == 'wali') {
            return redirect()->route('wali.beranda');
        } elseif ($user->akses == 'kepala_sekolah') {
            return redirect()->route('kepala_sekolah.beranda');
        } else {
            Auth::logout();
            Session::flash('error', 'Anda tidak memiliki hak akses');
            return redirect()->route('login');
        }
    }

    protected function loginApi(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            $user = array_merge(Auth::user()->toArray(), ['token' => $token]);
            return $this->okResponse("Login Berhasil", $user);
        }
        return $this->unauthenticatedResponse('Login Gagal');
    }
}
