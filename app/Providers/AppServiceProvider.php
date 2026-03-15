<?php

namespace App\Providers;

// Import model User untuk digunakan di Gate
use App\Models\User;
use App\Models\StockTransaction;
use App\Observers\StockTransactionObserver;

// Facade Gate untuk membuat authorization (hak akses)
use Illuminate\Support\Facades\Gate;

// Class dasar ServiceProvider
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * Digunakan untuk binding service ke container.
     * Biasanya dipakai untuk dependency injection.
     * 
     * Saat ini belum digunakan.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 
     * Method ini akan dijalankan saat aplikasi pertama kali di-load.
     * Biasanya digunakan untuk:
     * - Authorization (Gate / Policy)
     * - View Composer
     * - Global configuration
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Set locale Carbon ke Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');

        // Register Request Macro for isMobile
        Request::macro('isMobile', function () {
            return preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $this->header('User-Agent'));
        });

        // Register Observers
        StockTransaction::observe(StockTransactionObserver::class);

        /**
         * Global variable for mobile detection and low stock data
         */
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $userAgent = request()->header('User-Agent');
            $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent);
            
            $data = [
                'isMobile' => (bool) $isMobile
            ];

            // Tambahkan data stok rendah jika tabel products sudah ada
            if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                $lowStockProducts = \App\Models\Product::where('stock', '<=', 1)
                    ->take(5)
                    ->get();
                
                $data['lowStockProducts'] = $lowStockProducts;
                $data['lowStockCount'] = \App\Models\Product::where('stock', '<=', 1)->count();
            }

            $view->with($data);
        });

        /**
         * ==========================================
         * 🔐 GATE: ADMIN ACCESS
         * ==========================================
         * 
         * Mendefinisikan hak akses bernama 'admin-access'.
         * 
         * Cara pakai di controller / blade:
         * 
         * Gate::allows('admin-access')
         * atau
         * @can('admin-access')
         */
        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
