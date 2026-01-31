<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    protected $backupPath;

    public function __construct()
    {
        $this->middleware('auth');
        $this->backupPath = storage_path('app/backups');
        
        // Create backups directory if it doesn't exist
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Display list of backups
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        return view('dashboard.backups.index', compact('backups'));
    }

    /**
     * Create a new backup
     */
    public function create(Request $request)
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $this->backupPath . '/' . $filename;

            // Try mysqldump first (faster and more reliable)
            $mysqldumpPath = $this->findMysqldump();
            
            if ($mysqldumpPath) {
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
                    // Fallback to PHP-based backup
                    $this->createPhpBackup($filepath);
                }
            } else {
                // Use PHP-based backup
                $this->createPhpBackup($filepath);
            }

            if (File::exists($filepath) && File::size($filepath) > 0) {
                return redirect()->route('backup.index')->with('success', 'Database backup created successfully: ' . $filename);
            } else {
                return redirect()->route('backup.index')->with('error', 'Failed to create backup. Please check server configuration.');
            }

        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;

        if (!File::exists($filepath)) {
            return redirect()->route('backup.index')->with('error', 'Backup file not found.');
        }

        return response()->download($filepath);
    }

    /**
     * Delete a backup file
     */
    public function delete($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;

        if (!File::exists($filepath)) {
            return redirect()->route('backup.index')->with('error', 'Backup file not found.');
        }

        File::delete($filepath);

        return redirect()->route('backup.index')->with('success', 'Backup deleted successfully.');
    }

    /**
     * Restore from a backup file
     */
    public function restore($filename)
    {
        $filepath = $this->backupPath . '/' . $filename;

        if (!File::exists($filepath)) {
            return redirect()->route('backup.index')->with('error', 'Backup file not found.');
        }

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);

            // Try mysql command first
            $mysqlPath = $this->findMysql();

            if ($mysqlPath) {
                $command = sprintf(
                    '"%s" --host=%s --port=%s --user=%s --password=%s %s < "%s"',
                    $mysqlPath,
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    $filepath
                );

                exec($command . ' 2>&1', $output, $returnVar);

                if ($returnVar === 0) {
                    return redirect()->route('backup.index')->with('success', 'Database restored successfully from: ' . $filename);
                }
            }

            // Fallback to PHP-based restore
            $this->restorePhpBackup($filepath);
            
            return redirect()->route('backup.index')->with('success', 'Database restored successfully from: ' . $filename);

        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Get list of backup files
     */
    protected function getBackupFiles()
    {
        $files = File::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'size_raw' => $file->getSize(),
                    'date' => date('M d, Y H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        // Sort by date descending (newest first)
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return $backups;
    }

    /**
     * Create backup using PHP (fallback method)
     */
    protected function createPhpBackup($filepath)
    {
        $tables = DB::select('SHOW TABLES');
        $database = config('database.connections.mysql.database');
        
        $sql = "-- Database Backup\n";
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
     * Restore backup using PHP (fallback method)
     */
    protected function restorePhpBackup($filepath)
    {
        $sql = File::get($filepath);
        
        // Remove comments
        $sql = preg_replace('/^--.*$/m', '', $sql);
        
        // Split into statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        foreach ($statements as $statement) {
            if (!empty($statement) && $statement !== 'SET FOREIGN_KEY_CHECKS=0' && $statement !== 'SET FOREIGN_KEY_CHECKS=1') {
                try {
                    DB::unprepared($statement);
                } catch (\Exception $e) {
                    // Log but continue
                    \Log::warning('Backup restore warning: ' . $e->getMessage());
                }
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
            'mysqldump', // System PATH
        ];

        foreach ($paths as $path) {
            if (File::exists($path) || (stripos(PHP_OS, 'WIN') === false && shell_exec("which {$path}"))) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Find mysql executable
     */
    protected function findMysql()
    {
        $paths = [
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysql.exe',
            'C:\\xampp\\mysql\\bin\\mysql.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysql.exe',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql',
            'mysql', // System PATH
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
