<?php

namespace App\Http\Controllers\Main;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserLayoutConfig;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function get_auth()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $config = UserLayoutConfig::firstOrCreate(
            ['user_id' => $user->id],
            ['theme' => 'aura-dark-blue', 'scale' => 12, 'dark_mode' => false, 'menu_mode' => 'static']
        );

        $auth = [
            'user' => $user,
            'config' => $config,
            'companies' => $user->associates->map(function ($associate) {
                return [
                    'code' => $associate->company->code,
                    'role' => $associate->role,
                ];
            }),
        ];

        return response()->json($auth, 200);
    }

    public function login(Request $request)
    {
        try {

            $credentials = $request->only(['email', 'password']);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            if (!$user->active) {
                return response()->json(['error' => 'Usuário não está ativo'], 401);
            }

            if (!Auth::validate($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Falha ao tentar se autenticar'], 400);
            }

            $config = UserLayoutConfig::firstOrCreate(
                ['user_id' => $user->id],
                ['theme' => 'aura-dark-blue', 'scale' => 12, 'dark_mode' => false, 'menu_mode' => 'static']
            );

            $additionalData = [
                'companies' => $user->associates->map(function ($associate) {
                    return [
                        'code' => $associate->company->code,
                        'role' => $associate->role,
                    ];
                }),
            ];

            $token = auth('api')->claims($additionalData)->attempt($credentials);

            $auth = [
                'user' => $user,
                'config' => $config,
                'companies' => $user->associates->map(function ($associate) {
                    return [
                        'code' => $associate->company->code,
                        'role' => $associate->role,
                    ];
                }),
            ];

            return $this->response_token($token, $auth);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro interno do servidor', 'error' => $th->getMessage()], 500);
        }
    }

    public function token(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro interno do servidor', 'error' => $th->getMessage()], 500);
        }
    }

    protected function response_token($token, $auth)
    {
        return response()->json([
            'token' => $token,
            'auth' => $auth,
            'type' => 'Bearer',
        ], 200);
    }
}
