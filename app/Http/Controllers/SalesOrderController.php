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
        $salesOrders = SalesOrder::with('user', 'orderLine')->get();
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

        //SalesOrder
        if ($existingOrder) {
            // Name already exists, handle update scenario
            // $existingOrder->update(Arr::only($orderData, $allowedSalesOrder));

            if (!empty($request['order_line'])) {

                foreach ($request['order_line'] as $orderLineData) {
                    // Set the sales_order_id based on the parent SalesOrder
                    // $existingOrderLine =  //OrderLine::find($orderLineData['id']);
                    //     $orderLineData['sales_order_id'] = $existingOrder->id;

                    // $existingOrderLine = OrderLine::where('sales_order_id', $orderLineData->id);

                    $existingOrderLine = OrderLine::where('sales_order_id', $existingOrder->id)
                        ->where('product', $orderLineData['product'])
                        ->first();

                    if ($existingOrder) {
                        $existingOrderLine->update(Arr::only($orderLineData, $allowedOrderLine));
                    } else {
                        OrderLine::create(Arr::only($orderLineData, $allowedOrderLine));
                    }
                }
            }


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

        return response()->json(['message' => 'Sales order created successfully'], 201); // Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesOrder = SalesOrder::with('user', 'orderLine')->findOrFail($id);
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
        $existingSalesOrder = SalesOrder::findOrFail($id);

        $allowedSalesOrder = ['amount_to_invoice', 'amount_total', 'amount_untaxed', 'create_date', 'delivery_status', 'internal_note_display', 'name', 'partner_id_contact_address', 'partner_id_display_name', 'partner_id_phone', 'state', 'x_studio_commission_paid', 'x_studio_invoice_payment_status', 'x_studio_payment_type', 'x_studio_referrer_processed', 'x_studio_sales_rep_1', 'x_studio_sales_source', 'confirmed_by_manager', 'additional_deduction', 'manual_notes'];

        $validatedData = $request->validate(
            [
                'amount_to_invoice' => '',
                'amount_total'  => '',
                'amount_untaxed' => '',
                'create_date' => '',
                'delivery_status' => '',
                'internal_note_display' => '',
                'name' => 'required|max:255',
                'partner_id_contact_address' => '',
                'partner_id_display_name' => '',
                'partner_id_phone' => '',
                'state' => '',
                'x_studio_commission_paid' => '',
                'x_studio_invoice_payment_status' => '',
                'x_studio_payment_type' => '',
                'x_studio_referrer_processed' => '',
                'x_studio_sales_rep_1' => '',
                'x_studio_sales_source' => '',
                'confirmed_by_manager' => '',
                'additional_deduction' => '',
                'manual_notes' => '',
            ]
        );

        //  $orderData = Arr::only($request->all(), $allowedSalesOrder); // Extract only allowed fields


        if (!$existingSalesOrder) {
            // Order not found, handle error (e.g., return 404 Not Found)
            return response()->json(['message' => 'Sales order not found'], 404);
        }

        // Update SalesOrder
        $existingSalesOrder->update(Arr::only($validatedData, $allowedSalesOrder));
        return response()->json(['message' => 'Sales order updated successfully'], 200);
    }

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

    public function bulkStore(Request $request)
    {
        $allowedSalesOrder = ['amount_to_invoice', 'amount_total', 'amount_untaxed', 'create_date', 'delivery_status', 'internal_note_display', 'name', 'partner_id_contact_address', 'partner_id_display_name', 'partner_id_phone', 'state', 'x_studio_commission_paid', 'x_studio_invoice_payment_status', 'x_studio_payment_type', 'x_studio_referrer_processed', 'x_studio_sales_rep_1', 'x_studio_sales_source'];
        $allowedOrderLine = ['sales_order_id', 'product', 'description', 'quantity', 'unit_price', 'tax_excl', 'disc', 'taxes', 'delivered', 'invoiced'];
        $salesOrders = [];
        $orderLines = [];

        $salesOrderList = $request->all();

        foreach ($salesOrderList as $orderData) {
            $filteredSalesOrder = Arr::only($orderData, $allowedSalesOrder);

            // Check if sales order already exists by name (unique identifier)
            $existingSalesOrder = SalesOrder::where('name', $filteredSalesOrder['name'])->first();

            if ($existingSalesOrder) {
                // Update existing sales order
                $existingSalesOrder->update($filteredSalesOrder);
            } else {
                $salesOrders[] = $filteredSalesOrder;
            }

            saveOrUpdateOrderLines($orderData, $existingSalesOrder, $filteredSalesOrder['name']);
        }

        // Insert new sales orders in bulk (if any)
        if (!empty($salesOrders)) {
            SalesOrder::insert($salesOrders);
        }

        return response()->json(['message' => 'Sales orders created or updated successfully'], 201); // Created
    }

    public function getSalesByReps(Request $request)
    {
        $personList = $request->get('reps', []); // Get list of reps from request query string

        $salesOrders = SalesOrder::with('user', 'orderLine')
            ->whereIn('x_studio_sales_rep_1', $personList)
            ->get();

        return response()->json($salesOrders);
    }

    public function bulkUpdateDeadlines(Request $request)
    {

        $dateDeadlines = $request->all();
        $updatedCount = 0;

        // Get all sales orders that are not in $dateDeadlines
        $salesOrdersToUpdate = SalesOrder::whereNotIn('name', array_column($dateDeadlines, 'name'))->get();

        foreach ($salesOrdersToUpdate as $salesOrder) {
            $salesOrder->update(['date_deadline' => null]);
            $updatedCount++;
        }

        // Update existing sales orders that are in $dateDeadlines
        foreach ($dateDeadlines as $deadline) {
            $existingSalesOrder = SalesOrder::where('name', $deadline['name'])->first();
            if ($existingSalesOrder) {
                $existingSalesOrder->update(['date_deadline' => $deadline['date_deadline']]);
                $updatedCount++;
            }
        }

        return response()->json(['message' => 'Sales updated deadlines count: ' . $updatedCount], 200); // Created
    }

    public function updateManualAddition(Request $request)
    {
        $json = $request->all();
        $data = SalesOrder::where('name', $json['name'])->first();
        try {
            $data->update(['last_manual_add_by' => $json['user_id'], 'manual_notes' => $json['manual_notes'], 'additional_deduction' => $json['additional_deduction']]);

            return response()->json(['message' => 'Manual Addition updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update Manual Addition'], 404);
        }
    }

    public function updateConfirmedBy(Request $request)
    {
        $json = $request->all();
        $data = SalesOrder::where('name', $json['name'])->first();
        try {
            $data->update(['last_confirmed_by' => $json['user_id'], 'confirmed_by_manager' => $json['confirmed_by_manager']]);

            return response()->json(['message' => 'Confirmed By Manager updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update Confirmed By Manager'], 404);
        }
    }

    public function updateSalesOrderUserIds(Request $request)
    {
        $jsons = $request->all();
        $updatedCount = 0;
        try {

            foreach ($jsons as $json) {
                $existingSalesOrder = SalesOrder::where('id', $json['id'])->first();
                if ($existingSalesOrder) {
                    $existingSalesOrder->update(['user_id' => $json['user_id']]);
                    $updatedCount++;
                }
            }

            return response()->json(['message' => 'Successfulluy updated SalesOrderUserIds count: ' . $updatedCount], 200); // Created
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update SalesOrderUserIds'], 404);
        }
    }

    public function getPaginatedSalesOrders(Request $request)
    {
        // Retrieve 'per_page' query parameter from the request, default to 50 if not provided
        $perPage = $request->query('per_page', 50);

        // Retrieve 'page' query parameter from the request, default to 1 if not provided
        $currentPage = $request->query('page', 1);

        // Start building the query to fetch SalesOrders with related 'user' and 'orderLine'
        $salesOrdersQuery = SalesOrder::with('user', 'orderLine');

        // Paginate the results based on the provided $perPage and $currentPage
        $salesOrders = $salesOrdersQuery->paginate($perPage, ['*'], 'page', $currentPage);

        // Prepare pagination information
        $pagination = [
            'total_items' => $salesOrders->total(),
            'current_page' => $salesOrders->currentPage(),
            'per_page' => $salesOrders->perPage(),
            'last_page' => $salesOrders->lastPage(),
        ];

        // Return the response as JSON
        return response()->json([
            'data' => $salesOrders->items(), // Retrieve paginated items
            'pagination' => $pagination,
        ]);
    }

    public function updateEnteredOdooBy(Request $request)
    {
        $json = $request->all();
        $data = SalesOrder::where('name', $json['name'])->first();
        try {
            $data->update(['last_entered_odoo_by' => $json['user_id'], 'is_entered_odoo' => $json['is_entered_odoo']]);

            return response()->json(['message' => 'Entered Odoo By updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update Entered Odoo By'], 404);
        }
    }
}

function saveOrUpdateOrderLines(array $orderData, ?SalesOrder $existingSalesOrder = null, string $filteredSalesOrderName = ''): void
{
    $existingOrderLineProducts = [];
    $orderLinesToUpdate = [];
    $orderLines = [];

    // Extract order line data
    $incomingOrderLines = [];
    foreach ($orderData['order_line'] as $orderLineData) {
        $filteredOrderLine = new OrderLine([
            'product' => $orderLineData['product'],
            'description' => $orderLineData['description'] ?? null,
            'quantity' => $orderLineData['quantity'],
            'unit_price' => $orderLineData['unit_price'],
            'tax_excl' => $orderLineData['tax_excl'] ?? null,
            'disc' => $orderLineData['disc'] ?? null,
            'taxes' => $orderData['taxes'] ?? null,
            'delivered' => $orderLineData['delivered'] ?? null,
            'invoiced' => $orderLineData['invoiced'] ?? null,
        ]);
        $incomingOrderLines[$filteredOrderLine->product] = $filteredOrderLine;
    }

    // Find existing order lines
    $existingOrderLines = ($existingSalesOrder)
        ? OrderLine::where('sales_order_id', $existingSalesOrder->id)->get()
        : OrderLine::where('sales_order_id', $filteredSalesOrderName)->get();

    // Merge existing and incoming order lines
    $mergedOrderLines = [];
    foreach ($existingOrderLines as $existingOrderLine) {
        if (isset($incomingOrderLines[$existingOrderLine->product])) {
            $incomingOrderLines[$existingOrderLine->product]->id = $existingOrderLine->id;
            $incomingOrderLines[$existingOrderLine->product]->updated_at = $existingOrderLine->updated_at;
            $incomingOrderLines[$existingOrderLine->product]->created_at = $existingOrderLine->created_at;
            $orderLinesToUpdate[] = $existingOrderLine->product;
        }
        $mergedOrderLines[] = $existingOrderLine;
    }
    $mergedOrderLines = array_merge($mergedOrderLines, array_values($incomingOrderLines));

    // Separate lines for update and creation
    foreach ($mergedOrderLines as $mergedOrderLine) {
        $mergedOrderLine->sales_order_id = ($existingSalesOrder) ? $existingSalesOrder->id : $filteredSalesOrderName;
        if (in_array($mergedOrderLine->product, $orderLinesToUpdate)) {
            $mergedOrderLine->save();
        } else {
            $orderLines[] = $mergedOrderLine->toArray();
        }
        $existingOrderLineProducts[] = $mergedOrderLine->product;
    }

    // Create new order lines
    if (!empty($orderLines)) {
        // Add sales_order_id to all order lines being inserted
        foreach ($orderLines as &$line) {
            $line['sales_order_id'] = ($existingSalesOrder) ? $existingSalesOrder->id : $filteredSalesOrderName;
        }
        OrderLine::insert($orderLines);
    }

    // Identify and delete order lines to be removed
    // Directly delete based on existing order lines that are not in incoming data
    $existingOrderLineIdsToDelete = $existingOrderLines
        ->whereNotIn('product', $existingOrderLineProducts)
        ->pluck('id');

    if (!empty($existingOrderLineIdsToDelete)) {
        OrderLine::whereIn('id', $existingOrderLineIdsToDelete)->delete();
    }
}
