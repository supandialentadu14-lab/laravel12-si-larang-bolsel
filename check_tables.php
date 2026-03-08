<?php
$tables = [
    'categories', 'suppliers', 'products', 'stock_transactions',
    'berita_opnames', 'berita_pinjam_pakais', 'belanja_modals',
    'nota_pesanans', 'nota_masters', 'pemeriksaans', 'penerimaans', 'kwitansis',
    'activity_logs', 'notifications'
];

foreach ($tables as $table) {
    if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
        $hasUserId = \Illuminate\Support\Facades\Schema::hasColumn($table, 'user_id');
        echo str_pad($table, 25) . ": " . ($hasUserId ? 'YES' : 'NO') . PHP_EOL;
    } else {
        echo str_pad($table, 25) . ": NOT FOUND" . PHP_EOL;
    }
}
