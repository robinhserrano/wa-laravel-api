<?php

namespace App\Http\Controllers;

use App\Models\LandingPrice;
use App\Models\LandingPriceHistory;
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
        $validatedLandingPrice = $request->validate([
            'name' => 'required',
            'internal_reference' => 'required',
            'product_category' => 'required',
        ]);

        $landingPrice = LandingPrice::create($validatedLandingPrice);

        $allowedHistoryFields = ['installation_service', 'supply_only'];
        $historyData = $request->only($allowedHistoryFields);
        $historyData['recorded_at'] = now();

        $landingPrice->history()->create($historyData);

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
        $landingPrice = LandingPrice::findOrFail($id);

        $validatedLandingPrice = $request->validate([
            'name' => 'required',
            'internal_reference' => 'required',
            'product_category' => 'required',
        ]);

        $landingPrice->update($validatedLandingPrice);

        // $allowedHistoryFields = ['installation_service', 'supply_only'];
        // $historyData = $request->only($allowedHistoryFields);

        // $shouldCreateNewHistory = (!$landingPrice->history->latest() ||
        //     $landingPrice->history->latest()->isDirty($allowedHistoryFields));

        // if ($shouldCreateNewHistory) {
        //     $historyData['landing_price_id'] = $landingPrice->id;
        //     $historyData['recorded_at'] = now();
        //     $landingPrice->history()->create($historyData);
        // }
        $allowedHistoryFields = ['installation_service', 'supply_only'];
        $historyData = $request->only($allowedHistoryFields);

        $latestHistory = $landingPrice->history()->orderBy('created_at', 'desc')->first();

        $shouldCreateNewHistory = (!$latestHistory ||
            array_diff($historyData, (array) $latestHistory) !== []);

        if ($shouldCreateNewHistory) {
            $historyData['landing_price_id'] = $landingPrice->id;
            $historyData['recorded_at'] = now();
            $landingPrice->history()->create($historyData);
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
