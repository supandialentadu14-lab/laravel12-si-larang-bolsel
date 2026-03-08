<?php

$admin = \App\Models\User::where('role', 'admin')->first();
\Illuminate\Support\Facades\Auth::login($admin);

// This query triggers the join that caused the ambiguous error
$out = \App\Models\StockTransaction::where('type', 'out')->join('products', 'stock_transactions.product_id', '=', 'products.id')->sum(\Illuminate\Support\Facades\DB::raw('stock_transactions.quantity * products.price'));

echo "Success, query executed. Sum calculated: " . $out . "\n";
$cat = \App\Models\Category::with('products')->first();
echo "Success, loaded category with products.\n";

