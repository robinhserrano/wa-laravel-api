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
            'display_name', 'categ_name', 'avg_cost', 'total_value', 'qty_available', 'free_qty', 'incoming_qty', 'outgoing_qty', 'virtual_available',
        ];

        $productList = $request->all();
        $newProducts = [];

        foreach ($productList as $product) {
            $filteredProduct = Arr::only($product, $allowedProduct);
            $existingProduct = ProductStock::where('display_name', $filteredProduct['display_name'])->first();

            if ($existingProduct) {

                $existingProduct->update($filteredProduct);
            } else {

                $newProducts[] = $filteredProduct;
            }

            $orderLines[] = $filteredProduct;
        }

        if (!empty($newProducts)) {
            ProductStock::insert($newProducts);
        }
    }
}
