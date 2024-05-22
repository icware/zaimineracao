<?php

namespace App\Packages\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Packages\Company\Models\Company;
use App\Packages\System\Controllers\SystemCodeController;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{   
    
    protected $systemCode;

    public function __construct(SystemCodeController $systemCodeController)
    {
        $this->systemCode = $systemCodeController;
    }

    public function index()
    {
        $radares = Company::all();
        return response()->json($radares);
    }

    public function store(Request $request)    {
        

        try {            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'client' => 'nullable|string|max:255',
                'cnpj' => 'nullable|string|max:20|unique:companies,cnpj', // Assuming CNPJ is a string of 14 characters
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $newCode = $this->systemCode->generateCode('company');
    
            $company = Company::create([
                'name' => $request->input('name'),
                'code' => $newCode,
                'client' => $request->input('client'),
                'cnpj' => $request->input('cnpj'),
            ]);
    
            return response()->json($company, 201);   

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Informações ausentes'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro na criação', 'error' => $e->getMessage()], 500);
        }
        

    }
}