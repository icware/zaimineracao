<?php

namespace App\Http\Controllers\Main;

use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Models\UserLayoutConfig;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Packages\System\Controllers\SystemCodeController;
use Illuminate\Support\Facades\URL;

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

            $verificationUrl = $this->verificationUrl($user);

            $this->sendVerificationEmail($user, $verificationUrl);

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

            return response()->json(['message' => 'Usuário atualizado com sucesso'], 202);
        } catch (\Throwable $th) {
            return $this->handleError('Erro interno do servidor', 500, $th->getMessage());
        }
    }

    public function get_or_create_config(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $config = UserLayoutConfig::updateOrCreate(
            ['user_id' => $user->id],
            $request->only('theme', 'scale', 'dark_mode', 'menu_mode')
        );
        return response()->json(['status' => 'success']);
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
                $newCode = $this->systemCode->generateCode('reset_password');
                $user->update(['reset_password_code' => $newCode]);

                Mail::to($user->email)->send(new ResetPasswordMail($newCode));

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
                'reset_password_code' => 'required',
                'password' => 'required|min:8',
            ]);
            $user = User::where('reset_password_code', $request->reset_password_code)->first();
            if ($user) {
                $user->update([
                    'password' => bcrypt($request->password),
                    'reset_password_code' => null,
                ]);

                return response()->json(['message' => 'Senha atualizada com sucesso.']);
            } else {
                return response()->json(['message' => 'Código de redefinição de senha inválido.'], 404);
            }
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
                    ->subject('Bem-vindo ao nosso sistema');
            });

            SystemLog::create([
                'action' => 'welcome_email_sent',
                'message' => 'E-mail de boas-vindas enviado para o usuário ' . $user->email,
            ]);
        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'welcome_email_error',
                'message' => 'Erro ao enviar e-mail de boas-vindas para o usuário ' . $user->email . ': ' . $e->getMessage(),
            ]);
        }
    }

    private function sendUpdate(User $user)
    {
        $emailData = [
            'user' => $user,
        ];

        try {
            Mail::send('emails.update_profile', $emailData, function ($message) use ($user) {
                $message->to($user->email, $user->first_name . ' ' . $user->last_name)
                    ->subject('Perfil Atualizado');
            });

            SystemLog::create([
                'action' => 'welcome_email_sent',
                'message' => 'E-mail de boas-vindas enviado para o usuário ' . $user->email,
            ]);
        } catch (\Exception $e) {
            SystemLog::create([
                'action' => 'welcome_email_error',
                'message' => 'Erro ao enviar e-mail de boas-vindas para o usuário ' . $user->email . ': ' . $e->getMessage(),
            ]);
        }
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
