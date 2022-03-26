<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserNotification;
use Hash;

class CommonController extends Controller
{
    public function userAccountVerification(Request $request, $token , $ids)
    {
        $id = $ids;
            $user = User::find($id);
            if($user->is_active == 0){
                $user->is_active = 1;
                $user->save();
                 $data1['email']          = $user->email;
                 $data1['email_title']    = $user->name;
                 //mail goes to user
                 Mail::to($data1['email'])->send(new NewUserNotification($data1));
                 return view('email_verified');
                
            } else {
                return view('user_already_verified');
            }
        
    }
}
