<?php

namespace App\Http\Controllers;

use App\users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    
    
    public function index()
    {
        $allUseres = users::all();
        return $allUseres;
    }


    public function register(Request $request)
    {
        $filds = $request->validate([
            'name'=> 'required|string',
            'age'=> 'required',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|string|confirmed',
            'address'=> 'required',
            'phone'=> 'required|unique:users,phone'
        ]);

        $user = users::create([
            'name' => $filds['name'],
            'age' => $filds['age'],
            'email' => $filds['email'],
            'password' => bcrypt($filds['password']),
            'address' => $filds['address'],
            'phone' => $filds['phone']
        ]);

        $token = $user->createToken('mobile', ['role:user'])->plainTextToken;

        $response = [
            "User" => $user,
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

        $user = users::where("email",$filds['email'])->first();

        if(!$user || !Hash::check($filds['password'], $user->password)){
            return response([
                "messege" => "bad login"
            ], 404);
        }

        $token = $user->createToken('mobile', ['role:user'])->plainTextToken;

        $response = [
            "User" => $user,
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

    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'=> 'required',
    //         'age'=> 'required',
    //         'email'=> 'required',
    //         'password'=> 'required',
    //         'address'=> 'required',
    //         'phone'=> 'required'
    //     ]);

    //     return users::creat($request->all());
    // }

    
    public function show($id)
    {
        return users::find($id);
    }

   
    public function update(Request $request, $id)
    {
        $users = users::find($id);

        $request->validate([
            'name'=> 'required',
            'age'=> 'required',
            'email'=> 'required',
            'password'=> 'required',
            'address'=> 'required',
            'phone'=> 'required'
        ]);

        $users->update($request->all());

        return $users;
    }

    
    public function destroy($id)
    {
        return users::destroy($id);
    }
}
