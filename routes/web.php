<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BelanjaModalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenPaketController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\NotaPesananController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PinjamPakaiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/backup/run', [\App\Http\Controllers\BackupController::class, 'run'])->name('backup.run');

    // Chat System
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread', [\App\Http\Controllers\ChatController::class, 'getUnreadMessages'])->name('chat.unread');
    Route::get('/chat/{user}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::put('/chat/message/{message}', [\App\Http\Controllers\ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/message/{message}', [\App\Http\Controllers\ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/bulk-delete', [\App\Http\Controllers\ChatController::class, 'bulkDelete'])->name('chat.bulk_delete');
    Route::post('/chat/clear/{user}', [\App\Http\Controllers\ChatController::class, 'clearConversation'])->name('chat.clear');

    // Notifications
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Master Data
    Route::middleware('permission:master_data')->group(function () {
        // Categories
        Route::resource('categories', CategoryController::class);
        Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDestroy'])->name('categories.bulk_delete');

        // Suppliers
        Route::resource('suppliers', SupplierController::class);
        Route::post('suppliers/bulk-delete', [SupplierController::class, 'bulkDestroy'])->name('suppliers.bulk_delete');

        // Products
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::resource('products', ProductController::class);
        Route::post('products/bulk-delete', [ProductController::class, 'bulkDestroy'])->name('products.bulk_delete');
        Route::get('products/{product}/barcode', [ProductController::class, 'printBarcode'])->name('products.barcode');
        Route::get('import/products', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/products', [ImportController::class, 'importProducts'])->name('import.products');
    });

    // Stock Management
    Route::middleware('permission:transaksi')->group(function () {
        Route::resource('stock', StockController::class)->except(['edit']);
        Route::get('stock/{stock}/edit', [StockController::class, 'edit'])->name('stock.edit');
        Route::post('stock/bulk-delete', [StockController::class, 'bulkDestroy'])->name('stock.bulk_delete');
    });

    // Laporan Persediaan
    Route::middleware('permission:laporan_persediaan')->group(function () {
        Route::get('reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export_excel');
        Route::get('reports/export-persediaan', [ReportController::class, 'exportPersediaan'])->name('reports.export_persediaan');
        Route::get('reports/export-kartu-tahunan', [ReportController::class, 'exportKartuTahunan'])->name('reports.export_kartu_tahunan');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/kartu.tahunan', [ReportController::class, 'kartuTahunan'])->name('reports.kartu.tahunan');
    });

    // Berita Acara Pinjam Pakai
    Route::middleware('permission:pinjam_pakai')->group(function () {
        Route::get('reports/berita-pinjam-pakai', [PinjamPakaiController::class, 'form'])->name('reports.pinjam.form');
        Route::post('reports/berita-pinjam-pakai', [PinjamPakaiController::class, 'report'])->name('reports.pinjam.report');
        Route::get('reports/berita-pinjam-pakai/save', function () {
            return redirect()->route('reports.pinjam.form');
        });
        Route::post('reports/berita-pinjam-pakai/save', [PinjamPakaiController::class, 'save'])->name('reports.pinjam.save');
        Route::get('reports/berita-pinjam-pakai/list', [PinjamPakaiController::class, 'list'])->name('reports.pinjam.list');
        Route::post('reports/berita-pinjam-pakai/bulk-delete', [PinjamPakaiController::class, 'bulkDelete'])->name('reports.pinjam.bulk_delete');
        Route::get('reports/berita-pinjam-pakai/{id}/edit', [PinjamPakaiController::class, 'edit'])->name('reports.pinjam.edit');
        Route::get('reports/berita-pinjam-pakai/{id}', [PinjamPakaiController::class, 'show'])->name('reports.pinjam.show');
        Route::delete('reports/berita-pinjam-pakai/{id}/delete', [PinjamPakaiController::class, 'delete'])->name('reports.pinjam.delete');
        Route::get('reports/berita-pinjam-pakai/{id}/delete', function () {
            return redirect()->route('reports.pinjam.list');
        });
    });

    // Berita Acara Stock Opname Persediaan
    Route::middleware('permission:stock_opname')->group(function () {
        Route::get('reports/berita-opname', [OpnameController::class, 'form'])->name('reports.opname.form');
        Route::get('reports/berita-opname/prefill', [OpnameController::class, 'prefill'])->name('reports.opname.prefill');
        Route::post('reports/berita-opname', [OpnameController::class, 'report'])->name('reports.opname.report');
        Route::post('reports/berita-opname/save', [OpnameController::class, 'save'])->name('reports.opname.save');
        Route::get('reports/berita-opname/{id}/edit', [OpnameController::class, 'edit'])->name('reports.opname.edit');
        Route::get('reports/berita-opname/list', [OpnameController::class, 'list'])->name('reports.opname.list');
        Route::get('reports/berita-opname/{id}', [OpnameController::class, 'show'])->name('reports.opname.show');
        Route::delete('reports/berita-opname/{id}/delete', [OpnameController::class, 'delete'])->name('reports.opname.delete');
        Route::post('reports/berita-opname/bulk-delete', [OpnameController::class, 'bulkDelete'])->name('reports.opname.bulk_delete');
    });

    // Belanja Modal
    Route::middleware('permission:laporan_belanja')->group(function () {
        Route::get('reports/belanja-modal', [BelanjaModalController::class, 'form'])->name('reports.belanja.modal.form');
        Route::post('reports/belanja-modal', [BelanjaModalController::class, 'report'])->name('reports.belanja.modal.report');
        Route::post('reports/belanja-modal/save', [BelanjaModalController::class, 'save'])->name('reports.belanja.modal.save');
        Route::get('reports/belanja-modal/save', function () {
            return redirect()->route('reports.belanja.modal.list');
        });
        Route::get('reports/belanja-modal/list', [BelanjaModalController::class, 'index'])->name('reports.belanja.modal.list');
        Route::get('reports/belanja-modal/preview-all', [BelanjaModalController::class, 'previewAll'])->name('reports.belanja.modal.preview_all');
        Route::get('reports/belanja-modal/export-excel-all', [BelanjaModalController::class, 'exportExcelAll'])->name('reports.belanja.modal.export_excel_all');
        Route::get('reports/belanja-modal/{id}/export-excel', [BelanjaModalController::class, 'exportExcel'])->name('reports.belanja.modal.export_excel');
        Route::get('reports/belanja-modal/{id}', [BelanjaModalController::class, 'show'])->name('reports.belanja.modal.show');
        Route::get('reports/belanja-modal/{id}/edit', [BelanjaModalController::class, 'edit'])->name('reports.belanja.modal.edit');
        Route::delete('reports/belanja-modal/{id}/delete', [BelanjaModalController::class, 'delete'])->name('reports.belanja.modal.delete');
        Route::post('reports/belanja-modal/bulk-delete', [BelanjaModalController::class, 'bulkDelete'])->name('reports.belanja.modal.bulk_delete');
    });

    // Nota Pesanan
    Route::middleware('permission:surat_pesanan')->group(function () {
        Route::get('reports/nota-pesanan', [NotaPesananController::class, 'form'])->name('reports.nota.form');
        Route::post('reports/nota-pesanan', [NotaPesananController::class, 'report'])->name('reports.nota.report');
        Route::post('reports/nota-pesanan/save', [NotaPesananController::class, 'save'])->name('reports.nota.save');
        Route::get('reports/nota-pesanan/list', [NotaPesananController::class, 'list'])->name('reports.nota.list');
        Route::get('reports/nota-pesanan/{id}', [NotaPesananController::class, 'show'])->name('reports.nota.show');
        Route::get('reports/nota-pesanan/{id}/edit', [NotaPesananController::class, 'edit'])->name('reports.nota.edit');
        Route::post('reports/nota-pesanan/{id}/update', [NotaPesananController::class, 'update'])->name('reports.nota.update');
        Route::delete('reports/nota-pesanan/{id}/delete', [NotaPesananController::class, 'delete'])->name('reports.nota.delete');
        Route::post('reports/nota-pesanan/bulk-delete', [NotaPesananController::class, 'bulkDelete'])->name('reports.nota.bulk_delete');
    });

    // Berita Acara Pemeriksaan Barang/Pekerjaan (berdasarkan Nota Pesanan)
    Route::middleware('permission:pemeriksaan')->group(function () {
        Route::get('reports/berita-pemeriksaan', [PemeriksaanController::class, 'form'])->name('reports.pemeriksaan.form');
        Route::post('reports/berita-pemeriksaan', [PemeriksaanController::class, 'report'])->name('reports.pemeriksaan.report');
        Route::get('reports/berita-pemeriksaan/save', function () {
            return redirect()->route('reports.pemeriksaan.form');
        });
        Route::post('reports/berita-pemeriksaan/save', [PemeriksaanController::class, 'save'])->name('reports.pemeriksaan.save');
        Route::get('reports/berita-pemeriksaan/list', [PemeriksaanController::class, 'list'])->name('reports.pemeriksaan.list');
        Route::get('reports/berita-pemeriksaan/{id}', [PemeriksaanController::class, 'show'])->name('reports.pemeriksaan.show');
        Route::get('reports/berita-pemeriksaan/{id}/edit', [PemeriksaanController::class, 'edit'])->name('reports.pemeriksaan.edit');
        Route::delete('reports/berita-pemeriksaan/{id}/delete', [PemeriksaanController::class, 'delete'])->name('reports.pemeriksaan.delete');
        Route::post('reports/berita-pemeriksaan/bulk-delete', [PemeriksaanController::class, 'bulkDelete'])->name('reports.pemeriksaan.bulk_delete');
    });

    Route::middleware('permission:penerimaan')->group(function () {
        Route::get('reports/berita-penerimaan', [PenerimaanController::class, 'form'])->name('reports.penerimaan.form');
        Route::post('reports/berita-penerimaan', [PenerimaanController::class, 'report'])->name('reports.penerimaan.report');
        Route::post('reports/berita-penerimaan/save', [PenerimaanController::class, 'save'])->name('reports.penerimaan.save');
        Route::get('reports/berita-penerimaan/list', [PenerimaanController::class, 'list'])->name('reports.penerimaan.list');
        Route::get('reports/berita-penerimaan/{id}/edit', [PenerimaanController::class, 'edit'])->name('reports.penerimaan.edit');
        Route::get('reports/berita-penerimaan/{id}', [PenerimaanController::class, 'show'])->name('reports.penerimaan.show');
        Route::delete('reports/berita-penerimaan/{id}/delete', [PenerimaanController::class, 'delete'])->name('reports.penerimaan.delete');
        Route::post('reports/berita-penerimaan/bulk-delete', [PenerimaanController::class, 'bulkDelete'])->name('reports.penerimaan.bulk_delete');
    });

    Route::middleware('permission:berkas_lainnya')->group(function () {
        Route::get('reports/kwitansi', [KwitansiController::class, 'form'])->name('reports.kwitansi.form');
        Route::post('reports/kwitansi', [KwitansiController::class, 'report'])->name('reports.kwitansi.report');
        Route::post('reports/kwitansi/save', [KwitansiController::class, 'save'])->name('reports.kwitansi.save');
        Route::get('reports/kwitansi/print-all', [KwitansiController::class, 'printAll'])->name('reports.kwitansi.print_all');
        Route::get('reports/kwitansi/list', [KwitansiController::class, 'list'])->name('reports.kwitansi.list');
        Route::get('reports/kwitansi/{id}/show', [KwitansiController::class, 'show'])->name('reports.kwitansi.show');
        Route::get('reports/kwitansi/{id}/edit', [KwitansiController::class, 'edit'])->name('reports.kwitansi.edit');
        Route::post('reports/kwitansi/{id}/update', [KwitansiController::class, 'update'])->name('reports.kwitansi.update');
        Route::delete('reports/kwitansi/{id}/delete', [KwitansiController::class, 'delete'])->name('reports.kwitansi.delete');
        Route::post('reports/kwitansi/bulk-delete', [KwitansiController::class, 'bulkDelete'])->name('reports.kwitansi.bulk_delete');
    });

    Route::middleware(['permission:surat_pesanan', 'permission:pemeriksaan', 'permission:penerimaan', 'permission:berkas_lainnya'])->group(function () {
        Route::get('reports/paket-dokumen/{notaId}', [DokumenPaketController::class, 'show'])->name('reports.paket.show');
    });
    // Unified Settings: OPD Profil & Penandatangan
    Route::middleware('permission:pengaturan_opd')->group(function () {
        Route::get('settings/opd', [OpdController::class, 'edit'])->name('settings.opd.edit');
        Route::post('settings/opd', [OpdController::class, 'update'])->name('settings.opd.update');
        Route::get('settings/opd/list', [OpdController::class, 'edit'])->name('settings.opd.index');
        Route::get('settings/nota-master', [OpdController::class, 'edit'])->name('settings.nota.master.edit');
        Route::post('settings/nota-master', [OpdController::class, 'update'])->name('settings.nota.master.update');
        Route::get('settings/nota-master/list', [OpdController::class, 'edit'])->name('settings.nota.master.list');
    });

    // Legacy Aliases for Consolidated Settings

    Route::get('profile', [UserController::class, 'editSelf'])->name('profile.edit');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // User Management (Admin only)
    Route::middleware('can:admin-access')->group(function () {
        Route::resource('users', UserController::class);
        // GET fallback: redirect ke index jika akses langsung via URL
        Route::get('users/{user}/toggle-active', fn () => redirect()->route('users.index'));
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('users/{user}/toggle-chat', [UserController::class, 'toggleChat'])->name('users.toggle-chat');
        Route::get('users/{user}/backup', [\App\Http\Controllers\BackupController::class, 'downloadUser'])->name('users.backup');
        Route::post('users/{user}/restore', [\App\Http\Controllers\UserRestoreController::class, 'restore'])->name('users.restore');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity_log.index');
    });
});

require __DIR__.'/auth.php';
