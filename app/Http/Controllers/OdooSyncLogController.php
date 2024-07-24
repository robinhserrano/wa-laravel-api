<?php

namespace App\Http\Controllers;

use App\Models\OdooSyncLog;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class OdooSyncLogController extends Controller
{
    public function index()
    {
        $odooSyncLog = OdooSyncLog::all();
        return $odooSyncLog;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'total_items' => 'required',
            ]);
            OdooSyncLog::create($validatedData);
            return response()->json(['message' => 'OdooSyncLog created successfully'], 200); // Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->toArray(),
            ], 422); // Unprocessable Entity status code
        }
    }

    public function show(string $id)
    {
        $odooSyncLog = OdooSyncLog::findOrFail($id);
        return $odooSyncLog;
    }

    public function destroy(string $id)
    {
        try {
            $odooSyncLog = OdooSyncLog::findOrFail($id);
            $odooSyncLog->delete();
            return response()->json(['message' => 'Deleted OdooSyncLog successfully'], 200);
        } catch (OdooSyncLog $e) {
            return response()->json(['error' => 'OdooSyncLog not found'], 404);
        }
    }
}
