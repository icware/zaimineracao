<?php

namespace App\Http\Controllers\Main;

use Carbon\Carbon;
use App\Models\SystemLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function get_email_verify(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                EmailVerification::where("user_id", $user->id)->delete();

                $verificationCode = Str::random(6);

                $expiresAt = Carbon::now()->addHour();

                EmailVerification::create([
                    'user_id' => $user->id,
                    'verification_code' => $verificationCode,
                    'expires_at' => $expiresAt
                ]);

                $this->sendVerificationEmail($user, $verificationCode);

                return response()->json(['message' => 'Código de verificação enviado.']);

            } else {
                return response()->json(['message' => 'Usuário não encontrado.'], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar a solicitação.'], 500);
        }

    }

    public function email_verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $verification = EmailVerification::where('verification_code', $request->verification_code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 400);
        }

        $user = $verification->user;
        $user->email_verified_at = Carbon::now();
        $user->save();

        $verification->delete();

        try {
            Mail::send('emails.user_verify', ['user' => $user,], function ($message) use ($user) {
                $message->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject('Email verificado');
            });

        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'email_error_email_verify',
                'message' => 'Erro ao enviar e-mail' . $user->email . ': ' . $e->getMessage(),
            ]);
        }

        return response()->json(['message' => 'Email verified successfully.']);
    }

    protected function sendVerificationEmail($user, $verification)
    {
        Mail::send('emails.verify', ['user' => $user, 'verification' => $verification], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Verifique seu endereço de e-mail');
        });

    }
}
