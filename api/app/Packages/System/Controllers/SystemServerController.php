<?php

namespace App\Packages\System\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packages\System\Models\SystemServer;
use App\Packages\System\Controllers\SystemCodeController;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Packages\System\Resources\SystemServerResource;

class SystemServerController extends Controller
{
    protected $systemCode;

    public function __construct(SystemCodeController $systemCodeController)
    {
        $this->systemCode = $systemCodeController;
    }

    public function index()
    {
        $object = SystemServer::all();
        return SystemServerResource::collection($object);
    }

    public function show(SystemServer $object)
    {
        return new SystemServerResource($object);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'nullable|in:internal,external',
                'address' => 'required|string|max:255',
                'enabled' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $newCode = $this->systemCode->generateCode('SystemServer');

            $object = SystemServer::create([
                'name' => $request->input('name'),
                'code' => $newCode,
                'type' => $request->input('type'),
                'address' => $request->input('address'),
                'enabled' => $request->input('enabled'),
            ]);

            return new SystemServerResource($object);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Informações ausentes', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na criação', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, SystemServer $object)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'type' => 'nullable|in:internal,external',
                'address' => 'sometimes|string|max:255',
                'enabled' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $object->update([$validator]);

            return new SystemServerResource($object);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Informações ausentes', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na atualização', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(SystemServer $object)
    {
        try {
            $object->delete();
            return response()->json(['message' => 'SystemServer deleted successfully', 'code' => $object->code]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na exclusão', 'error' => $e->getMessage()], 500);
        }
    }
}
