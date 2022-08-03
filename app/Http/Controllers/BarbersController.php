<?php

namespace App\Http\Controllers;

use App\barbers;
use App\category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class BarbersController extends Controller
{

    public function index()
    {
        $allBarbers = barbers::all();
        return $allBarbers;
    }


    public function register(Request $request)
    {
        $filds = $request->validate([
            'name'=>'required|string',
            'phone'=> 'required|unique:barbers,phone',
            'address'=> 'required',
            'password'=> 'required|string|confirmed',
            'category_id'=> 'required|numeric'
        ]);

        $barber = barbers::create([
            'name' => $filds['name'],
            'phone' => $filds['phone'],
            'address' => $filds['address'],
            'password' => bcrypt($filds['password']),
            'category_id' => $filds['category_id'],
        ]);

        $token = $barber->createToken('mobile', ['role:barber'])->plainTextToken;

        $response = [
            "User" => $barber,
            "token" => $token,
        ];

        return response($response, 201);
    }


    public function login(Request $request)
    {
        $filds = $request->validate([
            'phone'=> 'required',
            'password'=> 'required',
        ]);

        $barber = barbers::where("email",$filds['email'])->first();

        if(!$barber || !Hash::check($filds['password'], $barber->password)){
            return response([
                "messege" => "bad login"
            ], 404);
        }

        $token = $barber->createToken('mobile', ['role:barber'])->plainTextToken;

        $response = [
            "User" => $barber,
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
    //         'name'=>"required",
    //         'phone'=> 'required',
    //         'address'=> 'required',
    //         'password'=> 'required',
    //         'category_id'=> 'required'
    //     ]);

    //     return barbers::create($request->all());

    //     // $category = category::findOrFail($request->category_id);
    //     // $category->barbers->create([
    //     //     $request->all()
    //     // ]);
    // }


    public function show($id)
    {
        return barbers::find($id);
    }


    public function update(Request $request, $id)
    {
        $barber = barbers::find($id);

        $request->validate([
            'name'=>"required",
            'phone'=> 'required',
            'address'=> 'required',
            'password'=> 'required',
            'category_id'=> 'required'
        ]);

        return $barber;


    }


    public function destroy($id)
    {
        return barbers::destroy($id);
    }
}
