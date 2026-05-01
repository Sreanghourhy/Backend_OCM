<?php

namespace App\Http\Controllers\Api\AuthenticationCenter;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'telegram_user_id' => 'required|string',
        ]);

        $user = User::where('telegram_user_id', $request->telegram_user_id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Telegram account not found.'
            ], 404);
        }

        if ($user->otp_locked_until && now()->lessThan($user->otp_locked_until)) {
            return response()->json([
                'message' => 'Too many attempts. Please try again later.'
            ], 429);
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_sent_at' => now(),
            'otp_expires_at' => now()->addMinutes(5),
            'otp_attempts' => 0,
            'otp_verified_at' => null,
        ]);

        $this->sendTelegramMessage(
            $user->telegram_user_id,
            "Your OTP code is: {$otp}\nThis code expires in 5 minutes."
        );

        return response()->json([
            'message' => 'OTP sent to Telegram successfully.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'telegram_user_id' => 'required|string',
            'otp' => 'required|string',
        ]);

        $user = User::where('telegram_user_id', $request->telegram_user_id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if (!$user->otp_code || !$user->otp_expires_at) {
            return response()->json(['message' => 'OTP not found. Please request a new OTP.'], 400);
        }

        if (now()->gt(\Carbon\Carbon::parse($user->otp_expires_at))) {
            return response()->json([
                'message' => 'OTP expired. Please request a new OTP.',
                'now' => now()->toDateTimeString(),
                'expires_at' => $user->otp_expires_at,
            ], 400);
        }

        if ($user->otp_code != $request->otp) {
            $user->increment('otp_attempts');

            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        $resetToken = Str::random(60);

        $user->update([
            'otp_verified_at' => now(),
            'forgot_password_token' => $resetToken,
            'otp_code' => null,
        ]);

        return response()->json([
            'message' => 'OTP verified successfully.',
            'reset_token' => $resetToken,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('forgot_password_token', $request->reset_token)->first();

        if (!$user || !$user->otp_verified_at) {
            return response()->json([
                'message' => 'Invalid reset token.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'forgot_password_token' => null,
            'otp_code' => null,
            'otp_sent_at' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_resend_count' => 0,
            'otp_locked_until' => null,
            'otp_verified_at' => null,
        ]);

        return response()->json([
            'message' => 'Password reset successfully.'
        ]);
    }

    private function sendTelegramMessage($chatId, $message)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }
}