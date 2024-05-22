<?php

namespace App\Packages\Service\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packages\Service\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        // Recupera todos os radares e retorna como JSON
        $radares = Service::all();
        return response()->json($radares);
    }

    public function store(Request $request)
    {
        // Valida os dados recebidos na requisição
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Cria um novo radar com base nos dados recebidos e o salva no banco de dados
        $radar = new Service();
        $radar->name = $request->name;
        $radar->save();

        // Retorna uma resposta JSON com o novo radar criado
        return response()->json($radar, 201);
    }
}