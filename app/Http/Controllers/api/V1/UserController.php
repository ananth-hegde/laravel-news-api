<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Post(
 *     path="/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="auth_token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Validation error")
 * )
 *
 * @OA\Post(
 *     path="/login",
 *     summary="Log in to existing user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="auth_token", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Invalid credentials")
 * )
 *  * @OA\Post(
 *     path="/logout",
 *     summary="Log out the current user",
 *     tags={"Authentication"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 *
 * @OA\Post(
 *     path="/password/reset",
 *     summary="Reset user password",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset link sent",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Validation error")
 * )
 */
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
                'password' => 'required|string|min:8',
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
