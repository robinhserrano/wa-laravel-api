<?php

namespace App\Http\Controllers;

use App\Models\OrderLine;
use Illuminate\Http\Request;
use Exception;

class OrderLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderLines = OrderLine::all();
        return $orderLines;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderLine = OrderLine::findOrFail($id);
        return $orderLine;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $orderLine = OrderLine::findOrFail($id);
            $orderLine->delete();
            return response()->json(['message' => 'Deleted order line successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Order line not found'], 404);
        }
    }
}
