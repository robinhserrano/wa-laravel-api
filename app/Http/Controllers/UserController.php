<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedData = ['name', 'email', 'password', 'commission_split', 'access_level'];

        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|min:6',
                'commission_split' => 'required|integer|min:0|max:100',
                'access_level' => 'required|integer|min:1|max:5',
                'sales_manager_id' => 'nullable|integer', 
                // Add validation rules for other fields as needed
            ]);
            User::create(Arr::only($validatedData, $allowedData));
            return response()->json(['message' => 'User created successfully'], 200); // Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->toArray(),
            ], 422); // Unprocessable Entity status code
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        $allowedData = ['name', 'email', 'commission_split', 'access_level']; // Adjust as needed

        try {
            // Define validation rules, excluding unique email for existing user
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|max:255', // Remove unique rule for existing user
                'password' => 'nullable|min:6', 
                'commission_split' => '',
                'access_level' => 'required|integer|min:1|max:5',
                'sales_manager_id' => 'nullable', 
                // Allow optional password update
                // Add validation rules for other fields as needed
            ]);

            // Update only allowed fields
            $user->update(Arr::only($validatedData, $allowedData));

            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->toArray(),
            ], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Deleted sales order successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Sales order not found'], 404);
        }
    }
}
