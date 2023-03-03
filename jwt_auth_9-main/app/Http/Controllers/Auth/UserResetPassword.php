<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\EmailJob;
use App\Models\UserPasswordRest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserResetPassword extends Controller
{
    public function send_reset_password_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;

        // Check User's Email Exists or Not
        $user = User::where('email', $email)->first();
        if (! $user) {
            return response([
                'message' => 'Email doesnt exists',
                'status' => 'failed',
            ], 404);
        }
        dispatch(new EmailJob($email));

        return response([
            'message' => 'Password Reset Email Sent',
            'status' => 'success',
        ], 200);
    }

    public function verify(Request $request)
    {
        // $token =  DB::table('user_password_rests')->where('token', '=', $request->otp)->get();
        $token = UserPasswordRest::where('token', $request->otp)->first();

        if ($token) {
            return response([
                'message' => 'Verify otp Successfully',
                'status' => 'success',
            ], 200);
        } else {
            return response([
                'message' => 'Invalid OTP',
                'status' => 'failed',
            ], 400);
        }
    }

    public function reset(Request $request, $token)
    {
        // Delete Token older than 2 minute
        // $formatted = Carbon::now()->subMinutes(2)->toDateTimeString();
        // UserPasswordRest::where('created_at', '<=', $formatted)->delete();

        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $passwordreset = UserPasswordRest::where('token', $token)->first();

        if (! $passwordreset) {
            return response([
                'message' => 'Token is Invalid or Expired',
                'status' => 'failed',
            ], 404);
        }
        $user = User::where('email', $passwordreset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after resetting password
        UserPasswordRest::where('email', $user->email)->delete();

        return response([
            'message' => 'Password Reset Success',
            'status' => 'success',
        ], 200);
    }
}
