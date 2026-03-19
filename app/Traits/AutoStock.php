<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait AutoStock
{
    /**
     * Record items from BAP/Kwitansi to Stock Transactions (Type: IN)
     */
    protected function recordItemsToStock(array $items, string $nosur, string $date, string $notes = 'Otomatis dari Sistem'): void
    {
        $userId = Auth::id();
        if (!$userId) return;

        foreach ($items as $item) {
            $name = $item['nama'] ?? ($item['name'] ?? '');
            $qty = (int)($item['kuantitas'] ?? ($item['qty'] ?? 0));

            if ($name === '' || $qty <= 0) continue;

            // Find matching product in database by name mapping
            $product = Product::where('user_id', $userId)
                ->where('name', $name)
                ->first();

            if ($product) {
                // Check if already recorded to avoid double recording for this document
                $exists = StockTransaction::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->where('nosur', $nosur)
                    ->where('type', 'in')
                    ->exists();

                if (!$exists) {
                    DB::transaction(function () use ($product, $qty, $date, $nosur, $notes, $userId) {
                        StockTransaction::create([
                            'product_id' => $product->id,
                            'user_id' => $userId,
                            'type' => 'in',
                            'quantity' => $qty,
                            'date' => $date,
                            'nosur' => $nosur,
                            'notes' => $notes,
                        ]);

                        // Update physical stock (Model Observer or direct increment)
                        // Note: StockTransaction might have an observer, but let's be explicit if needed
                        // Based on StockController, it manually increments/decrements.
                        $product->increment('stock', $qty);
                    });
                }
            } else {
                Log::warning("AutoStock: Product not found for name '{$name}' (User ID: {$userId})");
            }
        }
    }

    /**
     * Remove Stock Transactions related to a source document
     */
    protected function removeStockBySource(string $nosur, ?string $altNosur = null): void
    {
        $userId = Auth::id();
        if (!$userId) return;

        $query = StockTransaction::where('user_id', $userId)
            ->where(function ($q) use ($nosur, $altNosur) {
                $q->where('nosur', $nosur);
                if ($altNosur) {
                    $q->orWhere('nosur', $altNosur);
                }
            });

        $transactions = $query->get();

        foreach ($transactions as $tx) {
            DB::transaction(function () use ($tx) {
                $product = Product::find($tx->product_id);
                if ($product) {
                    if ($tx->type === 'in') {
                        $product->decrement('stock', $tx->quantity);
                    } else {
                        $product->increment('stock', $tx->quantity);
                    }
                }
                $tx->delete();
            });
        }
    }
}
