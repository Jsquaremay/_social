<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Register user
    public function register(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|integer|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        //create user
        $user = User::create([
            'name' =>$attrs['name'],
            'email' =>$attrs['email'],
            'phone' =>$attrs['phone'],
            'password' =>bcrypt($attrs['password'])
        ]);

        //return user & token in response

        return response([
            'user' =>$user,
            'token' =>$user->createToken('secret')->plainTextToken
        ], 200);
    }

    //login user
    public function login(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        //attemp login
        if(!Auth::attempt($attrs)){
            return response([
                'message'=>'Invalid credentials!'
            ], 403);
        }

        //return user & token in response
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    //logout  user

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout success!'
        ], 200);
    }

    //get user details
    public function user(){
        return response([
            'user' => auth()->user()
        ], 200);
    } 

}
