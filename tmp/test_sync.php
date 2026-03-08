<?php

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = Product::first();
if (!$product) {
    echo "No products found.\n";
    exit;
}

$initialStock = $product->stock;
echo "Initial Stock: " . $initialStock . "\n";

// Create transaction
$transaction = StockTransaction::create([
    'product_id' => $product->id,
    'type' => 'in',
    'quantity' => 10,
    'date' => now(),
    'user_id' => 1,
    'notes' => 'Internal Test Sync'
]);

$product->refresh();
echo "Stock after 'in' 10: " . $product->stock . "\n";

if ($product->stock == $initialStock + 10) {
    echo "SUCCESS: Stock incremented automatically.\n";
} else {
    echo "FAILURE: Stock did not increment correctly.\n";
}

// Update transaction
$transaction->update(['quantity' => 15]);
$product->refresh();
echo "Stock after update to 15: " . $product->stock . "\n";

if ($product->stock == $initialStock + 15) {
    echo "SUCCESS: Stock updated automatically on quantity change.\n";
} else {
    echo "FAILURE: Stock did not update correctly on quantity change.\n";
}

// Delete transaction
$transaction->delete();
$product->refresh();
echo "Stock after deletion: " . $product->stock . "\n";

if ($product->stock == $initialStock) {
    echo "SUCCESS: Stock reverted automatically on deletion.\n";
} else {
    echo "FAILURE: Stock did not revert correctly.\n";
}
