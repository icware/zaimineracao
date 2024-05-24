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


    /**
     * Get list of companies.
     *
     * @group Companies
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Company One",
     *     "code": "COMP1",
     *     "client": "Client One",
     *     "cnpj": "12345678000100",
     *     "responsible_cpf": "12345678901",
     *     "company_name": "Company One Ltd.",
     *     "address_type": "Street",
     *     "address": "123 Main St",
     *     "number": "100",
     *     "complement": "Suite 200",
     *     "neighborhood": "Downtown",
     *     "postal_code": "12345-678",
     *     "state": "SP",
     *     "country": "Brazil",
     *     "phone": "+55 11 98765-4321",
     *     "email": "contact@companyone.com",
     *     "trading_name": "Company One",
     *     "registration_status": "Active",
     *     "status": true,
     *   }
     * ]
     */

    public function index()
    {
        $object = Company::all();
        return CompanyResource::collection($object);
    }

    /**
     * Get a specific company.
     *
     * @group Companies
     * @urlParam id required The ID of the company. Example: 1
     * @response 200 {
     *   "id": 1,
     *   "name": "Company One",
     *   "code": "COMP1",
     *   "client": "Client One",
     *   "cnpj": "12345678000100",
     *   "responsible_cpf": "12345678901",
     *   "company_name": "Company One Ltd.",
     *   "address_type": "Street",
     *   "address": "123 Main St",
     *   "number": "100",
     *   "complement": "Suite 200",
     *   "neighborhood": "Downtown",
     *   "postal_code": "12345-678",
     *   "state": "SP",
     *   "country": "Brazil",
     *   "phone": "+55 11 98765-4321",
     *   "email": "contact@companyone.com",
     *   "trading_name": "Company One",
     *   "registration_status": "Active",
     *   "status": true,
     * }
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Company] 1"
     * }
     */

    public function show(Company $company)
    {
        $company->load('users');
        return new CompanyResource($company);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', // Required, maximum 255 characters
                'code' => 'nullable|string|max:255|unique:companies,code', // Nullable, maximum 255 characters, unique in companies table
                'cnpj' => 'nullable|string|max:14|unique:companies,cnpj', // Nullable, maximum 14 characters, unique in companies table
                'responsible_cpf' => 'nullable|string|max:11', // Nullable, maximum 11 characters
                'company_name' => 'required|string|max:255', // Required, maximum 255 characters
                'address_type' => 'required|string|max:255', // Required, maximum 255 characters
                'address' => 'required|string|max:255', // Required, maximum 255 characters
                'number' => 'required|string|max:10', // Required, maximum 10 characters
                'complement' => 'nullable|string|max:255', // Nullable, maximum 255 characters
                'neighborhood' => 'required|string|max:255', // Required, maximum 255 characters
                'postal_code' => 'required|string|max:10', // Required, maximum 10 characters
                'state' => 'required|string|max:255', // Required, maximum 255 characters
                'country' => 'required|string|max:255', // Required, maximum 255 characters
                'phone' => 'required|string|max:20', // Required, maximum 20 characters
                'email' => 'required|email|max:255', // Required, valid email format, maximum 255 characters
                'trading_name' => 'nullable|string|max:255', // Nullable, maximum 255 characters
                'registration_status' => 'required|string|max:255', // Required, maximum 255 characters
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $newCode = $this->systemCode->generateCode('company');

            $object = Company::create([
                'name' => $request->input('name'), // Company name
                'code' => $newCode, // Unique company code
                'client' => $request->input('client'), // Client name
                'cnpj' => $request->input('cnpj'), // Unique CNPJ of the company
                'responsible_cpf' => $request->input('responsible_cpf'), // CPF of the responsible person
                'company_name' => $request->input('company_name'), // Official company name (Razão Social)
                'address_type' => $request->input('address_type'), // Type of address (Tipo Logradouro)
                'address' => $request->input('address'), // Street address (Logradouro)
                'number' => $request->input('number'), // Address number (Número)
                'complement' => $request->input('complement'), // Address complement (Complemento)
                'neighborhood' => $request->input('neighborhood'), // Neighborhood (Bairro)
                'postal_code' => $request->input('postal_code'), // Postal code (CEP)
                'state' => $request->input('state'), // State (Estado)
                'country' => $request->input('country'), // Country (País)
                'phone' => $request->input('phone'), // Contact phone number (Telefone)
                'email' => $request->input('email'), // Contact email (Email)
                'trading_name' => $request->input('trading_name'), // Trading name (Nome Fantasia)
                'registration_status' => $request->input('registration_status'), // Registration status (Situação Cadastral)
                'status' => $request->input('status', false), // Company status (active/inactive), default to false if not provided
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
                'name' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'code' => 'sometimes|nullable|string|max:255|unique:companies,code,' . $company->id, // Sometimes nullable, maximum 255 characters, unique in companies table except current company
                'cnpj' => 'sometimes|nullable|string|max:14|unique:companies,cnpj,' . $company->id, // Sometimes nullable, maximum 14 characters, unique in companies table except current company
                'responsible_cpf' => 'sometimes|nullable|string|max:11', // Sometimes nullable, maximum 11 characters
                'company_name' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'address_type' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'address' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'number' => 'sometimes|required|string|max:10', // Sometimes required, maximum 10 characters
                'complement' => 'sometimes|nullable|string|max:255', // Sometimes nullable, maximum 255 characters
                'neighborhood' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'postal_code' => 'sometimes|required|string|max:10', // Sometimes required, maximum 10 characters
                'state' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'country' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'phone' => 'sometimes|required|string|max:20', // Sometimes required, maximum 20 characters
                'email' => 'sometimes|required|email|max:255', // Sometimes required, valid email format, maximum 255 characters
                'trading_name' => 'sometimes|nullable|string|max:255', // Sometimes nullable, maximum 255 characters
                'registration_status' => 'sometimes|required|string|max:255', // Sometimes required, maximum 255 characters
                'status' => 'sometimes|boolean', // Sometimes boolean
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
