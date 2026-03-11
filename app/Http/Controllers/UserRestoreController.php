<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserRestoreController extends Controller
{
    public function restore(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:txt,sql,zip,octet-stream|max:50000' // allow sql, sometimes recognized as txt
        ]);

        try {
            $file = $request->file('backup_file');
            $sql = file_get_contents($file->getRealPath());

            if (!$sql) {
                return back()->with('error', 'File backup kosong atau tidak bisa dibaca.');
            }

            // Note: Since the SQL generated via downloadUser explicitly filters tables by user_id = $userId, 
            // when it restores it, the SQL instructions will just overwrite/update/insert the matched rows.
            // However, mysqldump generates INSERT IGNORE INTO. We will replace it with REPLACE INTO
            // and also flush existing user records so records that didn't exist in the backup are wiped.
            
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Bersihkan data user saat ini di semua tabel yang punya user_id
            $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            foreach ($tables as $tableRow) {
                $table = current((array)$tableRow);
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'user_id')) {
                    \Illuminate\Support\Facades\DB::table($table)->where('user_id', $user->id)->delete();
                }
            }

            // 2. Modifikasi sql dari mysqldump: ganti INSERT / INSERT IGNORE jadi REPLACE
            // Ini menjamin tabel yg tidak kita kosongkan (seperti 'users' id tertentu) akan benar-benar di-update nilainya
            $sql = str_replace('INSERT IGNORE INTO', 'REPLACE INTO', $sql);
            $sql = str_replace('INSERT INTO', 'REPLACE INTO', $sql);

            // 3. Eksekusi
            \Illuminate\Support\Facades\DB::unprepared($sql);

            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Data milik ' . $user->name . ' berhasil dipulihkan dari file backup!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            \Illuminate\Support\Facades\Log::error('Restore failed for user ' . $user->id . ': ' . $e->getMessage());
            return back()->with('error', 'Gagal memulihkan database pengguna: ' . $e->getMessage());
        }
    }
}
