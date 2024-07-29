<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // create token

        $token = $user->createToken("personal access token")->plainTextToken;
        $user->token = $token;
        return response()->json(['user' => $user]);
    }

    public function login(Request $request)
    {
        // $loginUserData = $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required'
        // ]);


        // $credentials = $request->only('email', 'password');
        // if (Auth::attempt($credentials)) {
        //     $user = User::where("email", $request->email)->first();
        //     $token = $user->createToken("personal access token")->plainTextToken;
        //     $user->token = $token;
        //     return response()->json(["user" => $user]);
        // }
        // return response()->json(["user" => "These credentials do not match our records."]);
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return response()->json(["user" => $user,'token'=>$token]);

    }


    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()->delete()) {
            return response()->json(['msg' => "You have been successfully logged out!"]);
        }
        return response()->json(['msg' => "some thing went wrong"]);
    }

    // info user
    public function user(){
         // Get the authenticated user
         $user = Auth::user();

         // Return the user information
         return response()->json([
             'user' => $user
         ]); 
    }

   
}
