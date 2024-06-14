<?php

namespace App\Http\Controllers;

use App\Models\OrderLine;
use App\Models\SalesOrder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $allowedSalesOrder = ['amount_to_invoice', 'amount_total', 'amount_untaxed', 'create_date', 'delivery_status', 'internal_note_display', 'name', 'partner_id_contact_address', 'partner_id_display_name', 'partner_id_phone', 'state', 'x_studio_commission_paid', 'x_studio_invoice_payment_status', 'x_studio_payment_type', 'x_studio_referrer_processed', 'x_studio_sales_rep_1', 'x_studio_sales_source'];
        $allowedOrderLine = ['sales_order_id', 'product', 'description', 'quantity', 'unit_price', 'tax_excl', 'disc', 'taxes', 'delivered', 'invoiced'];

        $orderData = ['amount_to_invoice' => $request['amount_to_invoice'], 'amount_total'  => $request['amount_total'], 'amount_untaxed' => $request['amount_untaxed'], 'create_date' => $request['create_date'], 'delivery_status' => $request['delivery_status'], 'internal_note_display' => $request['internal_note_display'], 'name' => $request['name'], 'partner_id_contact_address' => $request['partner_id_contact_address'], 'partner_id_display_name' => $request['partner_id_display_name'], 'partner_id_phone' => $request['partner_id_phone'], 'state' => $request['state'], 'x_studio_commission_paid' => $request['x_studio_commission_paid'], 'x_studio_invoice_payment_status' => $request['x_studio_invoice_payment_status'], 'x_studio_payment_type' => $request['x_studio_payment_type'], 'x_studio_referrer_processed' => $request['x_studio_referrer_processed'], 'x_studio_sales_rep_1' => $request['x_studio_sales_rep_1'], 'x_studio_sales_source' => $request['x_studio_sales_source']];
        $existingOrder = SalesOrder::where('name', $orderData['name'])->first();

        if ($existingOrder) {
            // Name already exists, handle update scenario
            $existingOrder->update(Arr::only($orderData, $allowedSalesOrder));
            return response()->json(['message' => 'Sales order updated successfully'], 200); // OK
        } else {
            // New Sales Order, create a new instance

            $salesOrder = new SalesOrder();
            $salesOrder->fill(Arr::only($orderData, $allowedSalesOrder));
            $salesOrder->save();
            if (!empty($request['order_line'])) {
                foreach ($request['order_line'] as $orderLineData) {
                    // Set the sales_order_id based on the parent SalesOrder
                    $orderLineData['sales_order_id'] = $salesOrder->id;

                    // Create and save a new OrderLine instance
                    OrderLine::create(Arr::only($orderLineData, $allowedOrderLine));
                }
            }
        }

        if (!empty($request['order_line'])) {
            $existingOrderLineIds = [];
            foreach ($request['order_line'] as $orderLineData) {
                if (isset($orderLineData['id'])) {
                    $existingOrderLineIds[] = $orderLineData['id'];
                }
            }

            // Delete entries not in the request
            $existingOrderLineIdsFromDB = OrderLine::where('sales_order_id', $salesOrder->id)
                ->pluck('id')
                ->toArray();

            $deleteOrderLineIds = array_diff($existingOrderLineIdsFromDB, $existingOrderLineIds);

            if (!empty($deleteOrderLineIds)) {
                OrderLine::whereIn('id', $deleteOrderLineIds)->delete();
            }

            // Update existing entries
            foreach ($request['order_line'] as $orderLineData) {
                if (isset($orderLineData['id'])) {
                    $existingOrderLine = OrderLine::find($orderLineData['id']);
                    if ($existingOrderLine) {
                        $existingOrderLine->update(Arr::only($orderLineData, $allowedOrderLine));
                    }
                }
            }

            // Create new entries (without 'id')
            foreach ($request['order_line'] as $orderLineData) {
                if (!isset($orderLineData['id'])) {
                    $orderLineData['sales_order_id'] = $salesOrder->id;
                    OrderLine::create(Arr::only($orderLineData, $allowedOrderLine));
                }
            }
        }

        return response()->json(['message' => 'Sales order created successfully'], 201); // Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesOrder = SalesOrder::with('orderLine')->findOrFail($id);
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
        // $validatedData = $request->validate([
        //     'sales_order_id' => 'required|exists:sales_orders,id',
        //     'product' => 'required',
        //     'quantity' => 'required|integer|min:1',
        //     'unit_price' => 'required|numeric|min:0',
        //     'tax_excl' => 'required|numeric|min:0',
        //     'disc' => 'required|numeric|min:0',
        //     'delivered' => 'required|boolean',
        //     'invoiced' => 'required|boolean',
        //     // Add validation rules for other fields
        // ]);

        // $salesOrder = SalesOrder::findOrFail($id);
        // $salesOrder->update($validatedData);

        // return response()->json(['message' => 'Order line updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy( SalesOrder $order)
    // {
    //     $request->disableEscapingToken();

    //     $salesOrder = SalesOrder::findOrFail($id);
    //     $salesOrder->delete();
    //     return response()->json(['message' => 'Deleted sales order successfully'], 200);
    // }

    public function destroy(string $id)
    {
        try {
            $salesOrder = SalesOrder::findOrFail($id);
            $salesOrder->delete();
            return response()->json(['message' => 'Deleted sales order successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Sales order not found'], 404);
        }
    }
}