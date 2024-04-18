<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    protected $redirectTo = 'projects';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:manager')->except('logout');
    }
    public function showAdminLoginForm()
    {
        return view('auth.login', ['url' => 'admin']);
    }
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/admin'); //not implemented
        }
        return back()->withInput($request->only('email', 'remember'));
    }
    public function showManagerLoginForm()
    {
        return view('auth.login', ['url' => 'manager']);
    }
    public function managerLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/manager'); //not implemented
        }
        return back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('projects.index');
    }
}
