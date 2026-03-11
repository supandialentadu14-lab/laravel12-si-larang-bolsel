<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\OpdSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\NotaMaster;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockTransactionsExport;

class ReportController extends Controller
{
    /**
     * Load Nota Master data for prefill and reporting.
     */
    protected function loadNotaMaster(): array
    {
        $row = NotaMaster::where('user_id', Auth::id())->first();
        if ($row) {
            return [
                'opd' => ['nama' => $row->opd_nama ?? '', 'alamat' => $row->opd_alamat ?? ''],
                'ppk' => ['nama' => $row->ppk_nama ?? '', 'nip' => $row->ppk_nip ?? '', 'alamat' => $row->ppk_alamat ?? ''],
                'penyedia' => ['toko' => $row->penyedia_toko ?? '', 'pemilik' => $row->penyedia_pemilik ?? '', 'alamat' => $row->penyedia_alamat ?? ''],
                'pejabat' => ['nama' => $row->pejabat_nama ?? '', 'nip' => $row->pejabat_nip ?? ''],
                'pptk' => ['nama' => $row->pptk_nama ?? '', 'nip' => $row->pptk_nip ?? ''],
                'pengurus_barang' => ['nama' => $row->pengurus_barang_nama ?? '', 'nip' => $row->pengurus_barang_nip ?? ''],
                'pengurus_pengguna' => ['nama' => $row->pengurus_pengguna_nama ?? '', 'nip' => $row->pengurus_pengguna_nip ?? ''],
                'bendahara' => ['nama' => $row->bendahara_nama ?? '', 'nip' => $row->bendahara_nip ?? ''],
            ];
        }
        return [
            'opd' => ['nama' => '', 'alamat' => ''],
            'ppk' => ['nama' => '', 'nip' => '', 'alamat' => ''],
            'penyedia' => ['toko' => '', 'pemilik' => '', 'alamat' => ''],
            'pejabat' => ['nama' => '', 'nip' => ''],
            'pptk' => ['nama' => '', 'nip' => ''],
            'pengurus_barang' => ['nama' => '', 'nip' => ''],
            'pengurus_pengguna' => ['nama' => '', 'nip' => ''],
            'bendahara' => ['nama' => '', 'nip' => ''],
        ];
    }

    /**
     * Display the annual report based on date range.
     */
    public function kartuTahunan(Request $request): View
    {
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();
        $categories = \App\Models\Category::all();

        $startDate = $request->input('start_date') ?: now()->startOfYear()->toDateString();
        $endDate = $request->input('end_date') ?: now()->toDateString();
        $categoryId = $request->input('category_id');

        $transactions = StockTransaction::with('product')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('product', function ($q2) use ($categoryId) {
                    $q2->where('category_id', $categoryId);
                });
            })
            ->orderBy('product_id', 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = [];

        foreach ($transactions as $trx) {
            if (! $trx->product) {
                continue;
            }

            $productId = (int) $trx->product_id;
            $date = $trx->date;

            if (! isset($grouped[$productId])) {
                $grouped[$productId] = [
                    'product' => $trx->product,
                    'rows' => [],
                    'saldo' => 0,
                ];
            }

            $masuk = 0;
            $keluar = 0;

            if ($trx->type === 'in') {
                $masuk = $trx->quantity;
                $grouped[$productId]['saldo'] += $trx->quantity;
            }

            if ($trx->type === 'out') {
                $keluar = $trx->quantity;
                $grouped[$productId]['saldo'] -= $trx->quantity;
            }
            
            $grouped[$productId]['rows'][] = [
                'date' => $date,
                'nosur' => $trx->nosur ?? '-',
                'masuk' => $masuk,
                'keluar' => $keluar,
                'harga' => $trx->product->price ?? 0,
                'sisa' => $grouped[$productId]['saldo'],
                'keterangan' => $trx->notes ?? '-',
            ];
        }

        return view('reports.kartu_tahunan', compact('grouped', 'startDate', 'endDate', 'opd', 'master', 'categories', 'categoryId'));
    }

    /**
     * Display the inventory report based on date range.
     */
    public function index(Request $request): View
    {
        $opd = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();
        $categories = \App\Models\Category::all();

        $startDate = $request->input('start_date') ?: now()->startOfYear()->toDateString();
        $endDate = $request->input('end_date') ?: now()->toDateString();
        $categoryId = $request->input('category_id');

        $transactions = StockTransaction::with('product')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('product', function ($q2) use ($categoryId) {
                    $q2->where('category_id', $categoryId);
                });
            })
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = $transactions->groupBy(function ($item) {
            return $item->date.'-'.$item->product_id;
        });

        $reportData = [];

        foreach ($grouped as $items) {
            $first = $items->first();
            $masuk = $items->where('type', 'in')->sum('quantity');
            $keluar = $items->where('type', 'out')->sum('quantity');

            $reportData[] = [
                'date' => $first->date,
                'product_id' => $first->product_id,
                'name' => $first->product->name,
                'harga' => $first->product->price ?? 0,
                'satuan' => $first->product->unit ?? '',
                'masuk' => $masuk,
                'keluar' => $keluar,
            ];
        }

        return view('reports.index', compact('reportData', 'startDate', 'endDate', 'opd', 'master', 'categories', 'categoryId'));
    }

    /**
     * Export general stock report to Excel.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date') ?: now()->startOfYear()->toDateString();
        $endDate   = $request->input('end_date') ?: now()->toDateString();
        $filename = 'Laporan-Persediaan-Barang-' . $startDate . '-sd-' . $endDate . '.xlsx';
        return Excel::download(new StockTransactionsExport($startDate, $endDate), $filename);
    }

    /**
     * Export inventory report to Excel.
     */
    public function exportPersediaan(Request $request)
    {
        $startDate = $request->input('start_date') ?: now()->startOfYear()->toDateString();
        $endDate   = $request->input('end_date') ?: now()->toDateString();
        $categoryId = $request->input('category_id');
        $filename  = 'Laporan-Persediaan-' . $startDate . '-sd-' . $endDate . '.xlsx';
        return Excel::download(new \App\Exports\LaporanPersediaanExport($startDate, $endDate, $categoryId), $filename);
    }

    /**
     * Export annual card report to Excel.
     */
    public function exportKartuTahunan(Request $request)
    {
        $startDate = $request->input('start_date') ?: now()->startOfYear()->toDateString();
        $endDate   = $request->input('end_date') ?: now()->toDateString();
        $categoryId = $request->input('category_id');
        $opd    = OpdSetting::where('user_id', Auth::id())->first();
        $master = $this->loadNotaMaster();

        $transactions = StockTransaction::with('product')
            ->whereBetween('date', [$startDate, $endDate])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('product', function ($q2) use ($categoryId) {
                    $q2->where('category_id', $categoryId);
                });
            })
            ->orderBy('product_id', 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = [];
        foreach ($transactions as $trx) {
            if (!$trx->product) continue;
            $pid = (int) $trx->product_id;
            if (!isset($grouped[$pid])) {
                $grouped[$pid] = ['product' => $trx->product, 'rows' => [], 'saldo' => 0];
            }
            $masuk  = 0; $keluar = 0;
            if ($trx->type === 'in')  { $masuk  = $trx->quantity; $grouped[$pid]['saldo'] += $trx->quantity; }
            if ($trx->type === 'out') { $keluar = $trx->quantity; $grouped[$pid]['saldo'] -= $trx->quantity; }
            $grouped[$pid]['rows'][] = [
                'date'       => $trx->date,
                'nosur'      => $trx->nosur ?? '-',
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'harga'      => $trx->product->price ?? 0,
                'sisa'       => $grouped[$pid]['saldo'],
                'keterangan' => $trx->notes ?? '-',
            ];
        }

        $filename = 'Kartu-Persediaan-' . $startDate . '-sd-' . $endDate . '.xlsx';
        return Excel::download(new \App\Exports\KartuTahunanExport($grouped, $startDate, $endDate, $opd, $master), $filename);
    }
}
