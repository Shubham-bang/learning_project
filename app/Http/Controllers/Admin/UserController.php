<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merchant;
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
}
