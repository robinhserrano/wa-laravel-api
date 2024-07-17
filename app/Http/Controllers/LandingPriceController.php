<?php

namespace App\Http\Controllers;

use App\Models\LandingPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Exception;


class LandingPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $landingPrice = LandingPrice::all();
        return $landingPrice;
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
        $allowedLandingPrice = ['name', 'internal_reference', 'product_category', 'installation_service', 'supply_only'];
        $landingPrice = $request->all();
        $filteredLandingPrice = Arr::only($landingPrice, $allowedLandingPrice);
        LandingPrice::create($filteredLandingPrice);

        return response()->json(['message' => 'Sales order created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesOrder = LandingPrice::findOrFail($id);
        return $salesOrder;
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
        $existingLandingPrice = LandingPrice::findOrFail($id);
        $allowedLandingPrice = ['name', 'internal_reference', 'product_category', 'installation_service', 'supply_only'];
        $validatedData = $request->validate(
            [
                'name' => '',
                'internal_reference'  => '',
                'product_category' => '',
                'installation_service' => '',
                'supply_only' => '',
            ]
        );

        //  $orderData = Arr::only($request->all(), $allowedSalesOrder); // Extract only allowed fields


        if (!$existingLandingPrice) {
            // Order not found, handle error (e.g., return 404 Not Found)
            return response()->json(['message' => 'Landing Price not found'], 404);
        }

        // Update SalesOrder
        $existingLandingPrice->update(Arr::only($validatedData, $allowedLandingPrice));
        return response()->json(['message' => 'Landing Price updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $landingPrice = LandingPrice::findOrFail($id);
            $landingPrice->delete();
            return response()->json(['message' => 'Deleted Landing Price successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Landing Price not found'], 404);
        }
    }
}

