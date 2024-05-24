<?php

namespace App\Http\Controllers\Main;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerificationController extends Controller
{
    public function generateVerificationUrl(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $verificationUrl = $this->verificationUrl($user);

        $this->sendVerificationEmail($user, $verificationUrl);

        return response()->json(['verification_url' => $verificationUrl]);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->code);

        if ($request->hasValidSignature()) {
            $user->email_verified_at = now();
            $user->save();

            return response()->json(['message' => 'E-mail verificado com sucesso']);
        }

        return response()->json(['error' => 'Link de verificação inválido ou expirado'], 400);
    }

    protected function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'email.verify',
            now()->addMinutes(60),
            ['code' => $user->id]
        );
    }

    protected function sendVerificationEmail($user, $verificationUrl)
    {
        Mail::send('emails.verify', ['user' => $user, 'verificationUrl' => $verificationUrl], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Verifique seu endereço de e-mail');
        });

    }
}
