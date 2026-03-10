<?php

namespace App\Observers;

use App\Models\StockTransaction;

class StockTransactionObserver
{
    /**
     * Handle the StockTransaction "created" event.
     */
    public function created(StockTransaction $stockTransaction): void
    {
        $product = $stockTransaction->product;
        if ($stockTransaction->type === 'in') {
            $product->increment('stock', $stockTransaction->quantity);
        } else {
            $product->decrement('stock', $stockTransaction->quantity);
        }

        $this->checkStok($product);
    }

    /**
     * Handle the StockTransaction "updated" event.
     */
    public function updated(StockTransaction $stockTransaction): void
    {
        $product = $stockTransaction->product;
        
        // Reverse old quantity
        $oldType = $stockTransaction->getOriginal('type');
        $oldQty = $stockTransaction->getOriginal('quantity');
        
        if ($oldType === 'in') {
            $product->decrement('stock', $oldQty);
        } else {
            $product->increment('stock', $oldQty);
        }

        // Apply new quantity
        if ($stockTransaction->type === 'in') {
            $product->increment('stock', $stockTransaction->quantity);
        } else {
            $product->decrement('stock', $stockTransaction->quantity);
        }

        $this->checkStok($product);
    }

    protected function checkStok($product)
    {
        $product->refresh();
        if ($product->stock <= $product->min_stock) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LowStockNotification($product));
        }
    }

    /**
     * Handle the StockTransaction "deleted" event.
     */
    public function deleted(StockTransaction $stockTransaction): void
    {
        $product = $stockTransaction->product;
        if ($stockTransaction->type === 'in') {
            $product->decrement('stock', $stockTransaction->quantity);
        } else {
            $product->increment('stock', $stockTransaction->quantity);
        }
    }

    /**
     * Handle the StockTransaction "restored" event.
     */
    public function restored(StockTransaction $stockTransaction): void
    {
        //
    }

    /**
     * Handle the StockTransaction "force deleted" event.
     */
    public function forceDeleted(StockTransaction $stockTransaction): void
    {
        //
    }
}
