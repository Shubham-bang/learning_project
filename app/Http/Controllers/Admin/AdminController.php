<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        return view('admin.admin_dashboard');
    }

    public function adminLogin(Request $request)
    {
        return view('admin.admin_login');
    }

    public function adminMakeAuth(Request $request)
    {
        $validator = Validator::make($request->all(), [
              'email' => 'required|email|max:255',
              'password' => 'required',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $user = User::where('email','=',$request->email)->first();
        if($user){
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_active == 0) {

                    return redirect()->back()->withErrors(['errors' => ['Sorry, your account is not activated by admin']]);
                }
                if ($user->is_admin == 1) {

                    Auth::login($user);
                    return redirect()->route('admin.dashboard');

                }else {
                    return redirect()->back()->withErrors(['errors' => ['Something Went Wrong']]);
                }
                
            }
            else {
                return redirect()->back()->withErrors(['errors' => ['Your Password is Incorrect.']]);
            }
        }
        else {
            return redirect()->back()->withErrors(['errors' => ['The provided Email do not match our records.']]);
        }
    }
}
