<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Customer;
use App\Models\User;

class UserController extends Controller
{
    public function getAllMerchents(Request $request)
    {
        $merchents = Merchant::orderBy('id','DESC')->get();
        foreach ($merchents as $key => $merchent) {
            $merchent->user = User::where('id', $merchent->user_id)->first();
        }
        return view('admin.users.merchent_list', compact('merchents'));
    }

    public function viewMerchentsDetails(Request $request, $id)
    {
        $merchent = Merchant::where('id',$id)->first();
        return view('admin.users.view_merchent', compact('merchent'));
    }

    public function changeMerchentStatus(Request $request, $id)
    {
        // $id = $request->user_id;
        // $merchent = Merchant::where('id', $id)->first();
        $user = User::where('id', $id)->first();

       $merchent_status = $user->is_active;

         switch($merchent_status){
            case 0:   // inactive
              $user->is_active = 1; // active
              $user->save();
              break;
            case 1 :  // active 
              $user->is_active = 0; // inactive
              $user->save();
              break;
        }
        return redirect()->back()->with('message' , "Merchent Status Updated Successfully!!");
    }


    public function getAllusers(Request $request)
    {
        $users = Customer::orderBy('id','DESC')->get();
        foreach ($users as $key => $user_details) {
            $user_details->user = User::where('id', $user_details->user_id)->first();
        }
        return view('admin.users.customer_list', compact('users'));
    }
}
