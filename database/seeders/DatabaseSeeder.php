<?php

namespace Database\Seeders;

use App\Models\OrderLine;
use App\Models\SalesOrder;
use App\Models\User;
use Database\Factories\OrderLineFactory;
use Database\Factories\SalesOrderFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $salesOrderFactory = SalesOrderFactory::new();
        $salesOrders = $salesOrderFactory->count(20)->create();

        // Seed order lines for each sales order
        foreach ($salesOrders as $salesOrder) {
            $orderLineFactory = OrderLineFactory::new();
            $orderLineFactory->count(rand(1, 5))->create([
                'sales_order_id' => $salesOrder->id,
            ]);
        }
    }
}
