<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(){
        return view('auth.orders');
    }
    public function create(Request $request){
        $user=new User();
        $user->role_id=2;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->avatar='users/default.png';
        $user->password = Hash::make($request->password);
        $user->entreprise=$request->entreprise;
        $user->reduction=$request->reduction;
        $user->save();
        return redirect()->back()->with("success","le client a été ajouté avec success");
    }
}
