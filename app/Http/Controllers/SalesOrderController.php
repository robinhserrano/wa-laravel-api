<?php

namespace App\Http\Controllers;

use App\Models\OrderLine;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salesOrders = SalesOrder::with('orderLine')->get();
        return $salesOrders;
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
        $validatedData = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'product' => 'required',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'tax_excl' => 'required|numeric|min:0',
            'disc' => 'required|numeric|min:0',
            'delivered' => 'required|boolean',
            'invoiced' => 'required|boolean',
            // Add validation rules for other fields
        ]);

        SalesOrder::create($validatedData);

        foreach ($validatedData['order_line'] as $orderLineData) {
            // $orderLine = new OrderLine($orderLineData);
            // $salesOrder->orderLines()->save($orderLine);
            OrderLine::create($orderLineData);
        } 
        return response()->json(['message' => 'Order line created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderLine = SalesOrder::with('orderLine')->findOrFail($id);
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
        $validatedData = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'product' => 'required',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'tax_excl' => 'required|numeric|min:0',
            'disc' => 'required|numeric|min:0',
            'delivered' => 'required|boolean',
            'invoiced' => 'required|boolean',
            // Add validation rules for other fields
        ]);

        $orderLine = SalesOrder::findOrFail($id);
        $orderLine->update($validatedData);

        return response()->json(['message' => 'Order line updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $order)
    {
        $order->delete();
        return response()->json(['message' => 'Deleted sales order successfully'], 200);
    }
}
