<?php

namespace App\Packages\Company\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Packages\Company\Models\Display;
use App\Packages\Company\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class DisplayController extends Controller
{
    public function index(Company $company)
    {
        return response()->json($company->displays);
    }

    public function show(Company $company, $id)
    {
        try {
            $display = $company->displays()->findOrFail($id);
            return response()->json($display);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Display not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function store(Request $request, Company $company)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'visible' => 'boolean',
            ]);

            $display = $company->displays()->create([
                'name' => $request->name,
                'visible' => $request->visible ?? false,
            ]);

            return response()->json($display, 201);
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
                'name' => 'string|max:255',
                'visible' => 'boolean',
            ]);

            $display = $company->displays()->findOrFail($id);
            $display->update($request->all());

            return response()->json($display);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Display not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function destroy(Company $company, $id)
    {
        try {
            $display = $company->displays()->findOrFail($id);
            $display->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Display not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
