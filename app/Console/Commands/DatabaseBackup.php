<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';
    protected $description = 'Backup the database to a file in storage/app/backups';

    public function handle()
    {
        $filename = "backup-" . now()->format('Y-m-d_H-i-s') . ".sql";
        $storagePath = storage_path('app/backups');

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $path = $storagePath . '/' . $filename;
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection === 'mysql') {
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($config['host']),
                escapeshellarg($config['database']),
                escapeshellarg($path)
            );
        } elseif ($connection === 'sqlite') {
            $command = sprintf('cp %s %s', escapeshellarg($config['database']), escapeshellarg($path));
        } else {
            $this->error("Database connection '{$connection}' not supported for backup.");
            return 1;
        }

        $returnVar = null;
        $output = [];
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup successfully created: {$path}");
            
            // Optional: Limit total backups to keep (e.g., last 7)
            $files = glob("{$storagePath}/*.sql");
            if (count($files) > 10) {
                $mtimes = array_map('filemtime', $files);
                array_multisort($mtimes, SORT_ASC, $files);
                unlink($files[0]);
                $this->info("Pruned old backup: " . basename($files[0]));
            }
        } else {
            $this->error("Backup failed with error code: {$returnVar}");
        }
    }
}
