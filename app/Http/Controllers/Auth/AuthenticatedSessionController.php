<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function login_store(Request $req) 
    {
        $credentials = [
            'user_login' => $req->user_login, 
            'password'   => $req->password,
            'status'     => '0' // ล็อกอินได้เฉพาะพนักงานที่ยังทำงานอยู่ (Active)
        ];
        
       $role= DB::connection('central_staff_db')->table('tbluser_permissions as tp')
       ->leftjoin('tblsystems as ts', 'ts.sys_id', '=', 'tp.sys_id')
       ->leftjoin('tblemployee as te', 'te.id', '=', 'tp.emp_id')
       ->leftjoin('tblroles as tr', 'tr.role_id', '=', 'tp.role_id')
        ->where('te.user_login', $req->user_login)
        ->where('ts.sys_code', 'ELE')
        ->select('tp.*','tr.role_name')
        ->first();

        if ($role) {
            $req->session()->put('role_name', $role->role_name);
        }else {
            $req->session()->put('role_name', 'User');
        }

        if (Auth::attempt($credentials, $credentials)) {
            $req->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login_error' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง หรือบัญชีของคุณถูกระงับ',
        ])->withInput($req->only('username'));

    }
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect(RouteServiceProvider::HOME);

        // return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
