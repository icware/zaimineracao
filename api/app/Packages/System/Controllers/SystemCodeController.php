<?php

namespace App\Packages\System\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Packages\System\Models\SystemCode;


class SystemCodeController extends Controller
{
    
    public function index()
{
    try {
        $systemCodes = SystemCode::all();

        return response()->json($systemCodes);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erro ao buscar os códigos.'], 500);
    }
}
    
    public function store($description = null)
    {  
        try {
            if (!$description) {
                $description = 'Aleatório';
            }
            $code = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);

            while (SystemCode::where('code', $code)->exists()) {
                $code = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
            }
    
            SystemCode::create([
                'description' => $description,
                'code' => $code,
            ]);
    
            return response()->json(['code' => $code, 'description' => $description]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao gerar o código.'], 500);
        }
    }

    public function destroy($code){        
        try {
            $systemCode = SystemCode::where('code', $code)->first();

            if ($systemCode) {
                $systemCode->delete();
                return response()->json(['message' => 'Código excluído com sucesso.', 'code' => $code]);
            } else {
                return response()->json(['error' => 'Código não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o código.'], 500);
        }
    }
    public function show($code)
    {
        try {
            $systemCode = SystemCode::where('code', $code)->firstOrFail();

            return response()->json($systemCode);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Código não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar o código.'], 500);
        }
    }

    public function generateCode($description = null)
    {  
        try {
            if (!$description) {
                $description = 'Aleatório';
            }
            $code = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);

            while (SystemCode::where('code', $code)->exists()) {
                $code = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
            }
    
            SystemCode::create([
                'description' => $description,
                'code' => $code,
            ]);
    
            return $code;
        } catch (\Exception $e) {
            return null;
        }
    }
}