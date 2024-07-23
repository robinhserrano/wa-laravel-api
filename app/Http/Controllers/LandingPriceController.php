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
        $landingPrice = LandingPrice::with('history')->get();
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
        $allowedLandingPrice = ['name', 'internal_reference', 'product_category'];
        $allowedLandingPriceHistory = ['installation_service', 'supply_only', 'recorded_at'];
        $landingPrice = $request->all();
        $filteredLandingPrice = Arr::only($landingPrice, $allowedLandingPrice);
        $createdLandingPrice = LandingPrice::create($filteredLandingPrice);
        $landingPriceHistoryData = Arr::only($landingPrice, $allowedLandingPriceHistory);
        $landingPriceHistoryData['landing_price_id'] = $createdLandingPrice->id;
        LandingPrice::create($landingPriceHistoryData);

        return response()->json(['message' => 'Landing price created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $landingPrice = LandingPrice::with('history')->findOrFail($id);
        return $landingPrice;
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
    public function update(Request $request, $id)
    {
        $landingPrice = LandingPrice::findOrFail($id); // Find the landing price by ID

        $allowedLandingPrice = ['name', 'internal_reference', 'product_category'];
        $allowedLandingPriceHistory = ['installation_service', 'supply_only'];

        $landingPriceData = $request->all();

        // Filter allowed landing price data
        $filteredLandingPrice = Arr::only($landingPriceData, $allowedLandingPrice);

        // Update landing price with filtered data
        $landingPrice->update($filteredLandingPrice);

        $latestHistory = $landingPrice->history()->latest()->first();

        $shouldCreateNewHistory = (!$latestHistory ||
            $latestHistory->installation_service !== $request->input('installation_service') ||
            $latestHistory->supply_only !== $request->input('supply_only'));

        if ($shouldCreateNewHistory) {
            $newHistoryData = Arr::only($landingPriceData, $allowedLandingPriceHistory);
            $newHistoryData['landing_price_id'] = $landingPrice->id;
            $newHistoryData['recorded_at'] = now(); // Set recorded_at to current time

            LandingPrice::create($newHistoryData);
        }

        return response()->json(['message' => 'Landing price updated successfully'], 200);
    }
    // public function update(Request $request, string $id)
    // {
    //     $existingLandingPrice = LandingPrice::findOrFail($id);
    //     $allowedLandingPrice = ['name', 'internal_reference', 'product_category'];
    //     $allowedLandingPriceHistory = ['installation_service', 'supply_only'];
    //     $validatedData = $request->validate(
    //         [
    //             'name' => '',
    //             'internal_reference'  => '',
    //             'product_category' => '',
    //             'installation_service' => '',
    //             'supply_only' => '',
    //         ]
    //     );

    //     //  $orderData = Arr::only($request->all(), $allowedlandingPrice); // Extract only allowed fields


    //     if (!$existingLandingPrice) {
    //         // Order not found, handle error (e.g., return 404 Not Found)
    //         return response()->json(['message' => 'Landing Price not found'], 404);
    //     }

    //     // Update landingPrice
    //     $existingLandingPrice->update(Arr::only($validatedData, $allowedLandingPrice));
    //     return response()->json(['message' => 'Landing Price updated successfully'], 200);
    // }

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
