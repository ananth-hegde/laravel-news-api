<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //

    public function createUser(Request $request)
    {
        try{
            //Validation
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            if($validateUser->fails()){
                return response()->json(['error' => $validateUser->errors()], 401);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User created successfully', 
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 200);
        }
        catch(\Throwable $th)
        {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function loginUser(Request $request) {
        try{
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            if($validateUser->fails()){
                return response()->json(['status'=>false,'message'=>'Validation Error','error' => $validateUser->errors()], 401);
            }
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json(['status'=>false,'message'=>'Invalid Credentials'], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status'=>true,
                'message'=>'Login Successful',
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 200);
        }
        catch(\Throwable $th)
        {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function logoutUser(Request $request) {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status'=>true,'message'=>'Logout Successful'], 200);
        }
        catch(\Throwable $th)
        {
            return response()->json(['status'=> false,'error' => $th->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }
}
