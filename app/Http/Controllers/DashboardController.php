<?php

// Menentukan namespace lokasi controller
namespace App\Http\Controllers;

// Mengimpor Model Category
use App\Models\Category;

// Mengimpor Model Product
use App\Models\Product;

// Mengimpor Model StockTransaction
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\OpdSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Digunakan untuk query database mentah (raw query)
use Illuminate\Support\Facades\DB;

// Digunakan untuk tipe return berupa View
use Illuminate\View\View;

// Digunakan untuk manipulasi tanggal
use Carbon\Carbon;

// Controller untuk mengatur tampilan dashboard
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard
     */
    public function index(): View
    {
        // ============================
        // STATISTIK UTAMA (HARI INI)
        // ============================

        $totalProducts = Product::where('user_id', Auth::id())->count();
        $totalCategories = Category::where('user_id', Auth::id())->count();
        $selectedDate = request('date') ? Carbon::parse(request('date')) : Carbon::today();
        $agg = StockTransaction::select('product_id', DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as net'))
            ->whereDate('date', '<=', $selectedDate)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');
        $productsBase = Product::where('user_id', Auth::id())->select('id', 'name', 'min_stock', 'price')->get();
        $productsWithNet = $productsBase->map(function ($p) use ($agg) {
            $p->stock_on_date = (int)($agg[$p->id]->net ?? 0);
            return $p;
        });
        $criticalProducts = $productsWithNet->filter(fn ($p) => $p->stock_on_date <= $p->min_stock)->values();
        $lowStockCount = $criticalProducts->count();
        $totalStock = $productsWithNet->sum('stock_on_date');
        $totalInventoryValue = $productsWithNet->sum(function ($p) {
            return (int)($p->stock_on_date ?? 0) * (float)($p->price ?? 0);
        });
        $supplierCount = Supplier::where('user_id', Auth::id())->count();
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $disk = Storage::disk('local');
        $uid = Auth::id();
        $pinjamFiles = $disk->exists('users/'.$uid.'/pinjam_pakai') ? $disk->files('users/'.$uid.'/pinjam_pakai') : [];
        $belanjaFiles = $disk->exists('users/'.$uid.'/belanja-modal') ? $disk->files('users/'.$uid.'/belanja-modal') : [];
        $notaFiles = $disk->exists('users/'.$uid.'/nota-pesanan') ? $disk->files('users/'.$uid.'/nota-pesanan') : [];
        $pemeriksaanFiles = $disk->exists('users/'.$uid.'/bap-pemeriksaan') ? $disk->files('users/'.$uid.'/bap-pemeriksaan') : [];
        $penerimaanFiles = $disk->exists('users/'.$uid.'/bap-penerimaan') ? $disk->files('users/'.$uid.'/bap-penerimaan') : [];
        $kwitansiFiles = $disk->exists('users/'.$uid.'/kwitansi') ? $disk->files('users/'.$uid.'/kwitansi') : [];
        $opnameFiles = $disk->exists('users/'.$uid.'/opname') ? $disk->files('users/'.$uid.'/opname') : [];
        // ============================
        // DATA HARI INI
        // ============================
        $pinjamCount = collect($pinjamFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $belanjaModalCount = collect($belanjaFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $notaCount = collect($notaFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $pemeriksaanCount = collect($pemeriksaanFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $penerimaanCount = collect($penerimaanFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $kwitansiCount = collect($kwitansiFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $opnameCount = collect($opnameFiles)->filter(fn($f) => str_ends_with($f, '.json'))->count();
        $today = $selectedDate;
        $yesterday = (clone $selectedDate)->subDay();
        $inToday = StockTransaction::where('user_id', Auth::id())->where('type', 'in')->whereDate('date', $today)->sum('quantity');
        $outToday = StockTransaction::where('user_id', Auth::id())->where('type', 'out')->whereDate('date', $today)->sum('quantity');
        $valueInToday = StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->where('stock_transactions.type', 'in')
            ->where('stock_transactions.user_id', Auth::id())
            ->whereDate('stock_transactions.date', $today)
            ->sum(DB::raw('stock_transactions.quantity * products.price'));
        $valueOutToday = StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->where('stock_transactions.type', 'out')
            ->where('stock_transactions.user_id', Auth::id())
            ->whereDate('stock_transactions.date', $today)
            ->sum(DB::raw('stock_transactions.quantity * products.price'));
        $transactionsToday = StockTransaction::where('user_id', Auth::id())->whereDate('date', $today)->count();
        $inYesterday = StockTransaction::where('user_id', Auth::id())->where('type', 'in')->whereDate('date', $yesterday)->sum('quantity');
        $percentageChange = $inYesterday > 0 ? (($inToday - $inYesterday) / $inYesterday) * 100 : 0;
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->latest()
            ->take(10)
            ->get();

        // ============================
        // DATA UNTUK CHART (HARI INI PER JAM)
        // ============================

        $hourFormat = DB::connection()->getDriverName() === 'sqlite' 
            ? 'strftime("%H", date) as hour' 
            : 'DATE_FORMAT(date, "%H") as hour';

        $raw = StockTransaction::select(
                DB::raw($hourFormat),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as total_out')
            )
            ->where('user_id', Auth::id())
            ->whereDate('date', $today)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->keyBy('hour');
        $labels = collect(range(0, 23))->map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT).':00');
        $dataIn = $labels->map(function($label) use ($raw) {
            $h = substr($label, 0, 2);
            return (int)($raw[$h]->total_in ?? 0);
        });
        $dataOut = $labels->map(function($label) use ($raw) {
            $h = substr($label, 0, 2);
            return (int)($raw[$h]->total_out ?? 0);
        });



        // ============================
        // DATA TREN BULANAN (6 BULAN TERAKHIR) - OPTIMIZED
        // ============================
        $sixMonthsAgo = Carbon::today()->subMonths(5)->startOfMonth();
        
        $monthlyData = StockTransaction::select(
                DB::raw(DB::connection()->getDriverName() === 'sqlite' ? 'strftime("%Y-%m", date) as month' : 'DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as total_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as total_out')
            )
            ->where('user_id', Auth::id())
            ->whereDate('date', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlyValueData = DB::table('stock_transactions')
            ->join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->select(
                DB::raw(DB::connection()->getDriverName() === 'sqlite' ? 'strftime("%Y-%m", stock_transactions.date) as month' : 'DATE_FORMAT(stock_transactions.date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN stock_transactions.type = "in" THEN stock_transactions.quantity * products.price ELSE 0 END) as val_in'),
                DB::raw('SUM(CASE WHEN stock_transactions.type = "out" THEN stock_transactions.quantity * products.price ELSE 0 END) as val_out')
            )
            ->where('stock_transactions.user_id', Auth::id())
            ->whereDate('stock_transactions.date', '>=', $sixMonthsAgo)
            ->whereNull('stock_transactions.deleted_at')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $monthlyLabels = collect([]);
        $monthlyIn = collect([]);
        $monthlyOut = collect([]);
        $monthlyValueIn = collect([]);
        $monthlyValueOut = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $monthObj = Carbon::today()->subMonths($i);
            $key = $monthObj->format('Y-m');
            
            $monthlyLabels->push($monthObj->translatedFormat('M Y'));
            $monthlyIn->push((int)($monthlyData[$key]->total_in ?? 0));
            $monthlyOut->push((int)($monthlyData[$key]->total_out ?? 0));
            $monthlyValueIn->push((float)($monthlyValueData[$key]->val_in ?? 0));
            $monthlyValueOut->push((float)($monthlyValueData[$key]->val_out ?? 0));
        }

        // ============================
        // TOP 5 PRODUK PALING AKTIF
        // ============================
        $topProducts = Product::where('user_id', '=', Auth::id())
            ->withCount(['transactions' => function($q) {
                $q->where('user_id', '=', Auth::id());
            }])
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();

        // ============================
        // DISTRIBUSI STOK PER KATEGORI
        // ============================
        $categoryDistribution = Category::where('user_id', '=', Auth::id())
            ->withCount(['products as total_stock' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(stock), 0)'));
            }])->get()->filter(fn($c) => $c->total_stock > 0);

        $categoryLabels = $categoryDistribution->pluck('name');
        $categoryValues = $categoryDistribution->pluck('total_stock');

        $isMobile = request()->isMobile();
        $view = $isMobile ? 'mobile.dashboard' : 'dashboard';

        return view($view, compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockCount',
            'criticalProducts',
            'totalInventoryValue',
            'supplierCount',
            'opd',
            'pinjamCount',
            'percentageChange',
            'recentTransactions',
            'labels',
            'dataIn',
            'dataOut',
            'inToday',
            'outToday',
            'valueInToday',
            'valueOutToday',
            'transactionsToday',
            'monthlyLabels',
            'monthlyIn',
            'monthlyOut',
            'categoryLabels',
            'categoryValues',
            'topProducts',
            'monthlyValueIn',
            'monthlyValueOut',
            'belanjaModalCount',
            'notaCount',
            'pemeriksaanCount',
            'penerimaanCount',
            'kwitansiCount',
            'opnameCount'
        ));
}
}
