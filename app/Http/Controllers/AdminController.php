<?php

namespace App\Http\Controllers;

use App\admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function index()
    {
        $allAdmins = admin::all();
        return $allAdmins;
    }

    public function register(Request $request)
    {
        $filds = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string|unique:admin,email',
            'password'=> 'required|string|confirmed',
            'jop'=> 'required',
            'image'=> 'required'
        ]);

        $admin = admin::create([
            'name' => $filds['name'],
            'email' => $filds['email'],
            'password' => bcrypt($filds['password']),
            'jop' => $filds['jop'],
            'image' => $filds['image']
        ]);

        $token = $admin->createToken('mobile', ['role:admin'])->plainTextToken;

        $response = [
            "User" => $admin,
            "token" => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $filds = $request->validate([
            'email'=> 'required',
            'password'=> 'required',
        ]);

        $admin = admin::where("email",$filds['email'])->first();

        if(!$admin || !Hash::check($filds['password'], $admin->password)){
            return response([
                "message" => "bad login"
            ], 404);
        }

        $token = $admin->createToken('mobile', ['role:admin'])->plainTextToken;

        $response = [
            "User" => $admin,
            "token" => $token,
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return[
            "messege" => "logout Successfuly",
        ];
    }
}
