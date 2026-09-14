<?php
// ==========================================================
// TAMBAHKAN BLOK INI KE FILE routes/web.php YANG SUDAH ADA
// Jangan lupa tambahkan use statement di paling atas file:
// use App\Http\Controllers\BtsTowerController;
// ==========================================================

Route::middleware(['auth'])->group(function () {
    Route::get('bts-towers-export/excel', [BtsTowerController::class, 'exportExcel'])->name('bts-towers.export-excel');
    Route::get('bts-towers/{btsTower}/report', [BtsTowerController::class, 'reportPdf'])->name('bts-towers.report');
    Route::resource('bts-towers', BtsTowerController::class);
});
