<?php

namespace App\Packages\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packages\Company\Models\Company;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function getSettings()
    {        
        $radares = Company::all();
        return response()->json($radares);
    }

    public function store(Request $request)
    {
        // Valida os dados recebidos na requisição
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cria um novo radar com base nos dados recebidos e o salva no banco de dados
        $radar = new Company();
        $radar->name = $request->name;
        $radar->save();

        // Retorna uma resposta JSON com o novo radar criado
        return response()->json($radar, 201);
    }
}