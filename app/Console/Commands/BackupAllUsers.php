<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis membackup data seluruh user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai backup data untuk seluruh user...');
        
        $users = \App\Models\User::all();
        $date = date('Y-m-d');
        
        // Buat folder jika belum ada (storage/app/backups/users)
        $directory = storage_path("app/backups/users/{$date}");
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $tablesArr = \Illuminate\Support\Facades\DB::select('SHOW TABLES');

        foreach ($users as $user) {
            $this->info("Membackup user: {$user->name}...");
            $filename = 'backup_user_' . $user->id . '_' . str()->slug($user->name) . '_' . date('H_i_s') . '.sql';
            $path = $directory . '/' . $filename;

            $wheres = [];
            $excludeTables = [];
            
            foreach ($tablesArr as $tableRow) {
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
                'compress' => \Ifsnop\Mysqldump\Mysqldump::NONE,
                'no-data' => false,
                'add-drop-table' => false, 
                'no-create-info' => true,
                'insert-ignore' => true,
                'single-transaction' => true,
                'lock-tables' => false,
                'add-locks' => false,
                'extended-insert' => true,
                'disable-keys' => false,
                'skip-triggers' => false,
                'add-drop-trigger' => false,
                'routines' => false,
                'hex-blob' => true,
                'net_buffer_length' => 819200,
                'exclude-tables' => $excludeTables
            );

            try {
                $dump = new \Ifsnop\Mysqldump\Mysqldump(
                    'mysql:host=' . env('DB_HOST', '127.0.0.1') . ';dbname=' . env('DB_DATABASE', 'laravel'),
                    env('DB_USERNAME', 'root'),
                    env('DB_PASSWORD', ''),
                    $dumpSettings
                );
                
                $dump->setTableWheres($wheres);
                $dump->start($path);
                
                $this->line("  <info>[OK]</info> {$filename} berhasil dibuat.");
            } catch (\Exception $e) {
                $this->error("  [ERROR] Gagal membackup user {$user->name}: " . $e->getMessage());
                \Illuminate\Support\Facades\Log::error("Schedule Backup Failed for user {$user->id}: " . $e->getMessage());
            }
        }
        
        $this->info('Proses backup selesai.');
    }
}
