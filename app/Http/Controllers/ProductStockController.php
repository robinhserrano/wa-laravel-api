<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductStockController extends Controller
{
    public function index()
    {
        $productStock = ProductStock::all();
        return $productStock;
    }

    public function bulkStore(Request $request)
    {
        $allowedProduct = [
            'display_name',
            'categ_name',
            'avg_cost',
            'total_value',
            'qty_available',
            'free_qty',
            'incoming_qty',
            'outgoing_qty',
            'virtual_available',
            'warehouse_id',
        ];

        $productList = $request->all();
        $newProducts = [];
        $newDisplayNames = [];

        foreach ($productList as $product) {
            $filteredProduct = Arr::only($product, $allowedProduct);
            $newDisplayNames[] = $filteredProduct['display_name'];

            // Check if the product already exists
            $existingProduct = ProductStock::where('display_name', $filteredProduct['display_name'])
                ->where('warehouse_id', $filteredProduct['warehouse_id'])
                ->first();

            if ($existingProduct) {
                // Update existing product
                $existingProduct->update($filteredProduct);
            } else {
                // Add new product to the new products array
                $newProducts[] = $filteredProduct;
            }

            $orderLines[] = $filteredProduct;
        }

        // Create new products if any
        if (!empty($newProducts)) {
            foreach ($newProducts as $product) {
                ProductStock::create($product);
            }
        }

        // Delete existing products where display_name is not in the new list
        ProductStock::whereNotIn('display_name', $newDisplayNames)->delete();
    }
}
