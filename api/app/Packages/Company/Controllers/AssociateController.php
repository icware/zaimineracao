<?php
namespace App\Packages\Company\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Packages\Company\Models\Associate;
use App\Packages\Company\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class AssociateController extends Controller
{
    public function index(Company $company)
    {
        return response()->json($company->associates);
    }

    public function show(Company $company, $id)
    {
        try {
            $associate = $company->associates()->findOrFail($id);
            return response()->json($associate);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Associate not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function store(Request $request, Company $company)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role' => 'nullable|string',
                'enabled' => 'nullable|boolean',
            ]);

            $associate = $company->associates()->create([
                'user_id' => $request->user_id,
                'role' => $request->role,
                'enabled' => $request->enabled ?? true,
            ]);

            return response()->json($associate, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function update(Request $request, Company $company, $id)
    {
        try {
            $request->validate([
                'role' => 'nullable|string',
                'enabled' => 'nullable|boolean',
            ]);

            $associate = $company->associates()->findOrFail($id);
            $associate->update($request->all());

            return response()->json($associate);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Associate not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function destroy(Company $company, $id)
    {
        try {
            $associate = $company->associates()->findOrFail($id);
            $associate->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Associate not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
