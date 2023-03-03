<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        //Validate inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'user_type' => 'required',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            if (DB::table('users')->where('email', '=', $request->email)->first()) {
                return response([
                    'message' => 'Email already exists',
                    'status' => 'failed',
                ], 400);
            }
            // $user = DB::table('users')->insert([]);
            $user = User::create([
                'email' => $request->email,
                'user_type' => $request->user_type,
                'password' => Hash::make($request->password),
            ]);
            $id = DB::getPdo()->lastInsertId();

            $profile = new Profile;
            $profile->created_by = $id;
            $profile->save();
            if ($user) {
                return response()->json(['data' => $user, 'message' => 'User registered Successfully'], 200);
            } else {
                return response()->json([$user, 'status' => ' Signup failed'], 424);
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (! $token) {
            return response()->json(['result' => 'wrong email or password.'], 401);
        }
        $user = Auth::user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => 'd Successfully',
            'data' => $user,
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    public function profile()
    {
        return response()->json(auth()->guard('api')->user());
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }

    public function changepassword(Request $request)
    {
        //Validate inputs
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',

        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $loggeduser = auth()->guard('api')->user();
            $loggeduser->password = Hash::make($request->password);
            $loggeduser->save();
        }

        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success',
        ], 200);
    }
}
