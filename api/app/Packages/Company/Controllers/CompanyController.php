<?php
namespace App\Packages\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packages\Company\Models\Company;
use App\Packages\System\Controllers\SystemCodeController;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Packages\Company\Resources\CompanyResource;

class CompanyController extends Controller
{
    protected $systemCode;

    public function __construct(SystemCodeController $systemCodeController)
    {
        $this->systemCode = $systemCodeController;
    }

    public function index()
    {
        $object = Company::all();
        return CompanyResource::collection($object);
    }

    public function show(Company $company)
    {
        $company->load('users');
        return new CompanyResource($company);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'client' => 'nullable|string|max:255',
                'cnpj' => 'nullable|string|max:14|unique:companies,cnpj',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $newCode = $this->systemCode->generateCode('company');

            $object = Company::create([
                'name' => $request->input('name'),
                'code' => $newCode,
                'client' => $request->input('client'),
                'cnpj' => $request->input('cnpj'),
            ]);

            return new CompanyResource($object);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Informações ausentes', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na criação', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Company $company)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'client' => 'nullable|string|max:255',
                'cnpj' => 'nullable|string|max:14|unique:companies,cnpj,' . $company->id,
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $company->update($request->only(['name', 'client', 'cnpj']));

            return new CompanyResource($company);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Informações ausentes', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na atualização', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return response()->json(['message' => 'Company deleted successfully', 'code' => $company->code]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na exclusão', 'error' => $e->getMessage()], 500);
        }
    }
}
