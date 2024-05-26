<?php

namespace App\Packages\Service\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Packages\Service\Models\Service;
use App\Packages\Service\Resources\ServiceResource;
use App\Packages\System\Controllers\SystemCodeController;

class ServiceController extends Controller
{

    protected $systemCode;

    public function __construct(SystemCodeController $systemCodeController)
    {
        $this->systemCode = $systemCodeController;
    }

    public function index()
    {
        $object = Service::all();
        return ServiceResource::collection($object);
    }
    public function show(Service $object)
    {
        return new ServiceResource($object);
    }
    public function store(Request $request, Service $object)
    {
        $object->fill($request->all());
        $newCode = $this->systemCode->generateCode('services');
        $object->code = $newCode;
        $object->save();
        return new ServiceResource($object);
    }
    public function update(Request $request, Service $object)
    {
        $object->update($request->all());
        return new ServiceResource($object);
    }
    public function destroy(Service $object)
    {
        $object->delete();
        return response()->json(['detail' => 'Serviço excluido'], 200);
    }

}