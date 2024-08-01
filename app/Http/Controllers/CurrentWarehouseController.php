<?php

namespace App\Http\Controllers;

use App\Models\CurrentWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CurrentWarehouseController extends Controller
{
    public function index()
    {
        $currentWarehouse = CurrentWarehouse::all();
        return $currentWarehouse;
    }

    public function bulkStore(Request $request)
    {
        $allowedWarehouse = [
            'warehouse_id', 'name', 'code',
        ];

        $warehouseList = $request->all();
        $newWarehouses = [];

        foreach ($warehouseList as $warehouse) {
            $filteredWarehouse = Arr::only($warehouse, $allowedWarehouse);
            $existingWarehouse = CurrentWarehouse::where('warehouse_id', $filteredWarehouse['warehouse_id'])->first();

            if ($existingWarehouse) {

                $existingWarehouse->update($filteredWarehouse);
            } else {

                $newWarehouses[] = $filteredWarehouse;
            }

            $orderLines[] = $filteredWarehouse;
        }

        if (!empty($newWarehouses)) {
            CurrentWarehouse::insert($newWarehouses);
        }

        $existingWarehouseIds = CurrentWarehouse::pluck('warehouse_id')->toArray();

        // Find IDs to delete
        $incomingWarehouseIds = array_column($warehouseList, 'warehouse_id'); // Assuming 'id' exists in incoming data
        $idsToDelete = array_diff($existingWarehouseIds, $incomingWarehouseIds);

        // Delete warehouses with missing IDs
        if (!empty($idsToDelete)) {
            CurrentWarehouse::whereIn('warehouse_id', $idsToDelete)->delete();
        }
    }
}
