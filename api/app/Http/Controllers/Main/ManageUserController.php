<?php

namespace App\Http\Controllers\Main;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordMail;
use App\Models\UserTheme;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Packages\System\Controllers\SystemCodeController;


class ManageUserController extends Controller
{

    protected $systemCode;

    public function __construct(SystemCodeController $systemCodeController)
    {
        $this->systemCode = $systemCodeController;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:30',
            ]);

            $newCode = $this->systemCode->generateCode('system_user');

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'code' => $newCode,
                'active' => true,
                'birth' => $request->birth,
                'super' => false,
            ]);

            $this->sendWelcomeEmail($user);

            return response()->json(['success' => 'Usuário cadastrado com sucesso', 'user' => $user], 201);
        } catch (ValidationException $e) {
            return $this->handleError('Erro ao registrar usuário', 422, $e->getMessage());
        } catch (\Exception $e) {
            return $this->handleError('Erro ao registrar usuário', 500, $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:30',
                'birth' => 'nullable|date',
                'active' => 'boolean',
                'company' => 'nullable|string|max:255',
            ]);

            $user = JWTAuth::parseToken()->authenticate();
            $user->fill($validatedData);
            $user->save();

            $this->sendUpdate($user);

            return response()->json(['message' => 'Usuário atualizado com sucesso'], 202);
        } catch (\Throwable $th) {
            return $this->handleError('Erro interno do servidor', 500, $th->getMessage());
        }
    }

    public function get_or_create_theme(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $theme = UserTheme::where('user_id', $user->id)->first();

        if (!$theme) {
            $defaultValues = [
                'theme' => 'aura-dark-blue',
                'scale' => 12,
                'darkTheme' => true,
                'menuMode' => 'static',
                'ripple' => true,
                'activeMenuItem' => true,
                'inputStyle' => 'outlined'
            ];

            $theme = UserTheme::create(array_merge(['user_id' => $user->id], $defaultValues));
        }

        $updateFields = array_filter($request->all(), function ($key) {
            return in_array($key, ['theme', 'scale', 'darkTheme', 'menuMode', 'ripple', 'activeMenuItem', 'inputStyle']);
        }, ARRAY_FILTER_USE_KEY);

        $theme->update($updateFields);

        return response()->json($theme, 200);
    }

    public function reset_password(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'phone' => 'required',
                'birth' => 'required|date',
            ]);

            $user = User::where('email', $request->email)
                ->where('phone', $request->phone)
                ->where('birth', $request->birth)
                ->first();

            if ($user) {

                ResetPassword::where('user_id', $user->id)->delete();

                $verificationCode = Str::random(6);

                $expiresAt = Carbon::now()->addHour();

                ResetPassword::create([
                    'user_id' => $user->id,
                    'verification_code' => $verificationCode,
                    'expires_at' => $expiresAt
                ]);

                Mail::to($user->email)->send(new ResetPasswordMail($verificationCode));

                return response()->json(['message' => 'Código de redefinição de senha enviado com sucesso.']);
            } else {
                return response()->json(['message' => 'Usuário não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar a solicitação.'], 500);
        }
    }

    public function new_password(Request $request)
    {
        try {
            $request->validate([
                'verification_code' => 'required|string',
                'new_password' => 'required|min:8',
            ]);

            $verification = ResetPassword::where('verification_code', $request->verification_code)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$verification) {
                return response()->json(['message' => 'Invalid or expired verification code.'], 400);
            }

            $user = $verification->user;
            $user->password = bcrypt($request->new_password);
            $user->update_password_at = Carbon::now();
            $user->save();

            $this->sendUpdatePass($user);

            return response()->json(['message' => 'Senha atualizada com sucesso.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar a solicitação.'], 500);
        }
    }

    public function update_password(Request $request)
    {
        try {

            $request->validate([
                'password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = JWTAuth::parseToken()->authenticate();

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Senha atual incorreta'], 400);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            $this->sendUpdatePass($user);

            return response()->json(['message' => 'Senha atualizada com sucesso'], 202);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Manipula o erro e retorna uma resposta JSON genérica.
     *
     * @param string $errorMessage
     * @param int $statusCode
     * @param string|null $errorDetails
     * @return \Illuminate\Http\Response
     */
    private function handleError(string $errorMessage, int $statusCode, ?string $errorDetails = null)
    {
        $response = ['error' => $errorMessage];
        if ($errorDetails !== null) {
            $response['error_details'] = $errorDetails;
        }
        return response()->json($response, $statusCode);
    }

    /**
     * Envia um e-mail de saudação para o usuário após o cadastro.
     *
     * @param \App\Models\User $user
     * @return void
     */
    private function sendWelcomeEmail(User $user)
    {
        $emailData = [
            'user' => $user,
        ];

        try {
            Mail::send('emails.welcome', $emailData, function ($message) use ($user) {
                $message->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject('Usuário registrado com sucesso');
            });
        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'email_error_welcome',
                'message' => 'Erro ao enviar e-mail' . $user->email . ': ' . $e->getMessage(),
            ]);
        }
    }

    private function sendUpdate(User $user)
    {
        try {
            Mail::send('emails.update_profile', ['user' => $user,], function ($message) use ($user) {
                $message->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject('Perfil Atualizado');
            });

        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'email_error_update_profile',
                'message' => 'Erro ao enviar e-mail' . $user->email . ': ' . $e->getMessage(),
            ]);
        }
    }

    private function sendUpdatePass(User $user)
    {
        try {
            Mail::send('emails.update_profile', ['user' => $user,], function ($message) use ($user) {
                $message->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject('Sua senha foi alterada');
            });

        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'email_error_update_profile',
                'message' => 'Erro ao enviar e-mail' . $user->email . ': ' . $e->getMessage(),
            ]);
        }
    }

}
