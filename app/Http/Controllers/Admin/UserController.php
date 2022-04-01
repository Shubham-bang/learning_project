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
    public function getAllusers(Request $request)
    {
        $users = Customer::orderBy('id','DESC')->get();
        foreach ($users as $key => $user_details) {
            $user_details->user = User::where('id', $user_details->user_id)->first();
        }
        return view('admin.users.customer_list', compact('users'));
    }
}
