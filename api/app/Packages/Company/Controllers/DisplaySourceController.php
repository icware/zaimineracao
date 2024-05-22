<?php

namespace App\Packages\Company\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Packages\Company\Models\Company;
use App\Packages\Company\Models\Display;
use App\Packages\Company\Models\DisplaySource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DisplaySourceController extends Controller
{
    public function index(Company $company, Display $display)
    {
        try {
            return response()->json($display->displaySources);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Display $display, $id)
    {
        try {
            $displaySource = $display->displaySources()->findOrFail($id);
            return response()->json($displaySource);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'DisplaySource not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request, Company $company, Display $display)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_code' => 'required|integer',
                'source_id' => 'required|integer',
                'source_key' => 'required|string|max:255',
                'source_format' => 'required|string|max:255',
                'visible' => 'boolean',
                'settings' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $displaySource = $display->displaySources()->create([
                'service_code' => $request->service_code,
                'source_id' => $request->source_id,
                'source_key' => $request->source_key,
                'source_format' => $request->source_format,
                'visible' => $request->visible ?? false,
                'settings' => $request->settings ?? [],
            ]);

            return response()->json($displaySource, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Company $company, Display $display, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_code' => 'sometimes|required|integer',
                'source_id' => 'sometimes|required|integer',
                'source_key' => 'sometimes|required|string|max:255',
                'source_format' => 'sometimes|required|string|max:255',
                'visible' => 'sometimes|boolean',
                'settings' => 'sometimes|nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $displaySource = $display->displaySources()->findOrFail($id);
            $displaySource->update($request->all());

            return response()->json($displaySource);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'DisplaySource not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Company $company, Display $display, $id)
    {
        try {
            $displaySource = $display->displaySources()->findOrFail($id);
            $displaySource->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'DisplaySource not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
