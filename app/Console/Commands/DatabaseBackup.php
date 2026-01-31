<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--keep=7 : Number of days to keep backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup and clean old backups';

    protected $backupPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->backupPath = storage_path('app/backups');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Create backups directory if it doesn't exist
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        $this->info('Starting database backup...');

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            $filename = 'backup_auto_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $this->backupPath . '/' . $filename;

            // Try mysqldump first
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                $this->info('Using mysqldump for backup...');
                
                $command = sprintf(
                    '"%s" --host=%s --port=%s --user=%s --password=%s %s > "%s"',
                    $mysqldumpPath,
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    $filepath
                );

                exec($command . ' 2>&1', $output, $returnVar);

                if ($returnVar !== 0) {
                    $this->warn('mysqldump failed, falling back to PHP backup...');
                    $this->createPhpBackup($filepath);
                }
            } else {
                $this->info('Using PHP-based backup...');
                $this->createPhpBackup($filepath);
            }

            if (File::exists($filepath) && File::size($filepath) > 0) {
                $size = $this->formatBytes(File::size($filepath));
                $this->info("Backup created successfully: {$filename} ({$size})");
                Log::info("Scheduled backup created: {$filename} ({$size})");

                // Clean old backups
                $this->cleanOldBackups();

                return Command::SUCCESS;
            } else {
                $this->error('Failed to create backup.');
                Log::error('Scheduled backup failed: Empty or missing backup file');
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Scheduled backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Clean old automatic backups
     */
    protected function cleanOldBackups()
    {
        $keepDays = $this->option('keep');
        $cutoffDate = now()->subDays($keepDays)->timestamp;

        $this->info("Cleaning backups older than {$keepDays} days...");

        $files = File::files($this->backupPath);
        $deletedCount = 0;

        foreach ($files as $file) {
            // Only delete automatic backups (starts with backup_auto_)
            if (str_starts_with($file->getFilename(), 'backup_auto_') && 
                $file->getMTime() < $cutoffDate) {
                File::delete($file);
                $deletedCount++;
                $this->line("  Deleted: {$file->getFilename()}");
            }
        }

        if ($deletedCount > 0) {
            $this->info("Cleaned {$deletedCount} old backup(s).");
            Log::info("Cleaned {$deletedCount} old automatic backup(s).");
        } else {
            $this->info("No old backups to clean.");
        }
    }

    /**
     * Create backup using PHP (fallback method)
     */
    protected function createPhpBackup($filepath)
    {
        $tables = DB::select('SHOW TABLES');
        $database = config('database.connections.mysql.database');
        
        $sql = "-- Database Backup (Automatic)\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . $database . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            
            // Get create table statement
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "-- Table structure for `{$tableName}`\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get table data
            $rows = DB::table($tableName)->get();
            
            if ($rows->count() > 0) {
                $sql .= "-- Data for `{$tableName}`\n";
                
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        if (is_null($value)) {
                            return 'NULL';
                        }
                        return "'" . addslashes($value) . "'";
                    }, (array)$row);
                    
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        File::put($filepath, $sql);
    }

    /**
     * Find mysqldump executable
     */
    protected function findMysqldump()
    {
        $paths = [
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'mysqldump',
        ];

        foreach ($paths as $path) {
            if (File::exists($path) || (stripos(PHP_OS, 'WIN') === false && shell_exec("which {$path}"))) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
