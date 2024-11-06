<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request){

        $validate = $request->validated();

        $user = User::create($validate);

        if(User::count() == 1){
            $user->assignRole('admin');
        } else {
            $user->assignRole('user');
        }

        return response()->json([
            'message'   =>  'registered successful',
            'data'      =>  $user
        ]);
    }

    public function login(LoginRequest $request){

        $validate = $request->validated();

        $user = User::where('email', $validate['email'])->first();

        if(!$user || !Hash::check($validate['password'], $user->password)){
            return response()->json([
                'status'    =>  'invalid credentials!'
            ], 401);
        } else {
            $token = $user->createToken($user->name)->plainTextToken;
            
            $user->roles;

            return response()->json([
                'status'    =>  'success',
                'message'   =>  'login successful',
                'token'     =>  $token,
                'data'      =>  $user
            ], 200);
        }
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message'   =>  'token deleted'
        ], 200);
    }
}
