<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Ifsnop\Mysqldump as IMysqldump;
use Exception;

class BackupController extends Controller
{


    public function downloadUser($userId)
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $filename = 'backup_user_' . $user->id . '_' . str()->slug($user->name) . '_' . date('Y_m_d_H_i_s') . '.sql';
            $path = storage_path('app/' . $filename);

            // Identify tables to include with WHERE clause, and tables to exclude
            $tables = DB::select('SHOW TABLES');
            $wheres = [];
            $excludeTables = [];
            
            foreach ($tables as $tableRow) {
                $table = current((array)$tableRow);
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'user_id')) {
                    $wheres[$table] = 'user_id = ' . $user->id;
                } else if ($table === 'users') {
                    $wheres[$table] = 'id = ' . $user->id;
                } else {
                    $excludeTables[] = $table;
                }
            }

            $dumpSettings = array(
                'compress' => IMysqldump\Mysqldump::NONE,
                'no-data' => false,
                'add-drop-table' => false, 
                'no-create-info' => true,
                'insert-ignore' => true, // Gunakan INSERT IGNORE untuk menghindari duplicate error
                'single-transaction' => true,
                'lock-tables' => false,  // Matikan lock agar tidak error table not locked
                'add-locks' => false,    // Matikan lock agar tidak error table not locked
                'extended-insert' => true,
                'disable-keys' => false,
                'skip-triggers' => false,
                'add-drop-trigger' => false,
                'routines' => false,
                'hex-blob' => true,
                'net_buffer_length' => 819200,
                'exclude-tables' => $excludeTables
            );

            $dump = new IMysqldump\Mysqldump(
                'mysql:host=' . env('DB_HOST', '127.0.0.1') . ';dbname=' . env('DB_DATABASE', 'laravel'),
                env('DB_USERNAME', 'root'),
                env('DB_PASSWORD', ''),
                $dumpSettings
            );
            
            $dump->setTableWheres($wheres);
            $dump->start($path);

            return Response::download($path, $filename, [
                'Content-Type' => 'application/sql',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('User Backup failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal membackup database pengguna: ' . $e->getMessage());
        }
    }
}
