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
            $binaryPath = 'mysqldump';
            
            // On Windows, if mysqldump is not in PATH, try common locations
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $commonPaths = [
                    'C:\xampp\mysql\bin\mysqldump.exe',
                    'D:\xampp\mysql\bin\mysqldump.exe',
                    'C:\laragon\bin\mysql\mysql-8.x.x\bin\mysqldump.exe', // generic
                    'C:\laragon\bin\mysql\mysql-5.x.x\bin\mysqldump.exe',
                ];
                
                // Also check Laragon's path dynamically if available
                $laragonPath = 'C:\laragon\bin\mysql';
                if (is_dir($laragonPath)) {
                    $dirs = glob($laragonPath . '\*', GLOB_ONLYDIR);
                    if ($dirs) {
                        foreach($dirs as $dir) {
                           $fullPath = $dir . '\bin\mysqldump.exe';
                           if (file_exists($fullPath)) $commonPaths[] = $fullPath;
                        }
                    }
                }

                foreach ($commonPaths as $cp) {
                    if (file_exists($cp)) {
                        $binaryPath = '"' . $cp . '"';
                        break;
                    }
                }
            }

            $passwordPart = $config['password'] ? '--password=' . escapeshellarg($config['password']) : '';
            
            $command = sprintf(
                '%s --user=%s %s --host=%s %s > %s',
                $binaryPath,
                escapeshellarg($config['username']),
                $passwordPart,
                escapeshellarg($config['host']),
                escapeshellarg($config['database']),
                escapeshellarg($path)
            );
        } elseif ($connection === 'sqlite') {
            // Windows 'copy' instead of 'cp'
            $cmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'copy' : 'cp';
            $command = sprintf('%s %s %s', $cmd, escapeshellarg($config['database']), escapeshellarg($path));
        } else {
            $this->error("Database connection '{$connection}' not supported for backup.");
            return 1;
        }

        $returnVar = null;
        $output = [];
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup successfully created: {$path}");
            
            // Optional: Limit total backups to keep (e.g., last 10)
            $files = glob("{$storagePath}/*.sql");
            if (count($files) > 10) {
                $mtimes = array_map('filemtime', $files);
                array_multisort($mtimes, SORT_ASC, $files);
                unlink($files[0]);
                $this->info("Pruned old backup: " . basename($files[0]));
            }
        } else {
            $this->error("Backup failed with error code: {$returnVar}");
            if ($connection === 'mysql' && $returnVar === 1) {
                $this->error("Note: 'mysqldump' might not be in your system PATH or accessible.");
            }
        }
    }
}
