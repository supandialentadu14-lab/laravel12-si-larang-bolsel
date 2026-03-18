<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $path = storage_path('app/backups');
        $backups = [];

        try {
            if (File::exists($path)) {
                $files = File::files($path);
                foreach ($files as $file) {
                    if ($file->getExtension() === 'sql') {
                        $backups[] = [
                            'name' => $file->getFilename(),
                            'size' => round($file->getSize() / 1024, 2) . ' KB',
                            'at' => date('Y-m-d H:i:s', $file->getMTime()),
                            'raw_at' => $file->getMTime()
                        ];
                    }
                }
                // Sort by newest
                usort($backups, fn($a, $b) => $b['raw_at'] <=> $a['raw_at']);
            }
        } catch (\Exception $e) {
            // Log error or provide message but don't crash
            report($e);
            session()->flash('error', 'Gagal memuat daftar cadangan: ' . $e->getMessage());
        }

        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        
        try {
            Artisan::call('db:backup');
            $output = Artisan::output();
            
            if (str_contains($output, 'failed')) {
                return back()->with('error', 'Gagal membuat cadangan database. ' . $output);
            }
            
            return back()->with('success', 'Cadangan database berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $this->authorizeAdmin();
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($path);
    }

    public function destroy($filename)
    {
        $this->authorizeAdmin();
        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            return back()->with('success', 'File cadangan berhasil dihapus.');
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    protected function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
