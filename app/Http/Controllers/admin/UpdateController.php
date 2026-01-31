<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\ApplyUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class UpdateController extends Controller
{
    protected $basePath;

    public function __construct()
    {
        $this->middleware('auth');
        $this->basePath = base_path();
    }

    /**
     * Display update status page
     */
    public function index()
    {
        $updateInfo = $this->getUpdateInfo();
        $updateHistory = $this->getUpdateHistory();
        
        return view('dashboard.updates.index', compact('updateInfo', 'updateHistory'));
    }

    /**
     * Check for updates
     */
    public function check()
    {
        $updateInfo = $this->checkForUpdates(true);
        
        if ($updateInfo['update_available']) {
            return redirect()->route('update.index')->with('success', 'New update available!');
        }
        
        return redirect()->route('update.index')->with('info', 'You are running the latest version.');
    }

    /**
     * Apply update (dispatches background job)
     */
    public function apply(Request $request)
    {
        // Check if update is already in progress
        if (Cache::get('update_in_progress', false)) {
            return redirect()->route('update.index')->with('warning', 'An update is already in progress.');
        }

        // Dispatch the update job
        $userId = auth()->id();
        $userName = auth()->user()->name ?? 'System';

        // For sync queue driver, run immediately
        // For other drivers (database, redis), it will run in background
        ApplyUpdate::dispatch($userId, $userName);

        return redirect()->route('update.index')->with('info', 'Update started! This page will refresh automatically.');
    }

    /**
     * Get update status (AJAX endpoint)
     */
    public function status()
    {
        $inProgress = Cache::get('update_in_progress', false);
        $status = Cache::get('update_status', []);
        
        $currentStatus = $status['status'] ?? 'idle';
        
        // Clear completed/failed status after reading to prevent stale status
        if (($currentStatus === 'completed' || $currentStatus === 'failed') && !$inProgress) {
            // Check if the status is old (more than 30 seconds)
            $completedAt = $status['completed_at'] ?? null;
            if ($completedAt) {
                $completedTime = strtotime($completedAt);
                if ($completedTime && (time() - $completedTime) > 30) {
                    // Clear stale status
                    Cache::forget('update_status');
                    $currentStatus = 'idle';
                    $status = [];
                }
            }
        }
        
        return response()->json([
            'in_progress' => $inProgress,
            'status' => $currentStatus,
            'step' => $status['step'] ?? null,
            'log' => $status['log'] ?? [],
            'completed_at' => $status['completed_at'] ?? null,
        ]);
    }

    /**
     * Get update info from cache or check
     */
    public function getUpdateInfo()
    {
        return Cache::remember('update_info', 3600, function () {
            return $this->checkForUpdates();
        });
    }

    /**
     * Check for updates from GitHub
     */
    protected function checkForUpdates($force = false)
    {
        if ($force) {
            Cache::forget('update_info');
        }

        $info = [
            'current_version' => $this->getCurrentVersion(),
            'current_commit' => $this->getCurrentCommit(),
            'latest_commit' => null,
            'update_available' => false,
            'commits_behind' => 0,
            'changelog' => [],
            'last_checked' => now()->toDateTimeString(),
            'error' => null,
        ];

        try {
            $gitPath = $this->findGit();
            
            if (!$gitPath) {
                $info['error'] = 'Git not found on system.';
                return $info;
            }

            // Get remote URL to extract owner/repo
            chdir($this->basePath);
            $remoteUrl = trim(shell_exec("\"{$gitPath}\" config --get remote.origin.url 2>&1"));
            
            if (empty($remoteUrl) || strpos($remoteUrl, 'fatal') !== false) {
                $info['error'] = 'No Git remote configured.';
                return $info;
            }

            // Get latest remote commit
            $output = [];
            exec("\"{$gitPath}\" ls-remote origin main 2>&1", $output, $returnVar);
            
            if ($returnVar !== 0 || empty($output)) {
                // Try 'master' branch
                exec("\"{$gitPath}\" ls-remote origin master 2>&1", $output, $returnVar);
            }

            if (!empty($output) && isset($output[0])) {
                $parts = preg_split('/\s+/', $output[0]);
                $info['latest_commit'] = substr($parts[0], 0, 7);
                
                // Check if update available
                if ($info['current_commit'] !== $info['latest_commit']) {
                    $info['update_available'] = true;
                    
                    // Get commit count difference
                    $countOutput = [];
                    exec("\"{$gitPath}\" rev-list HEAD..origin/main --count 2>&1", $countOutput, $countReturn);
                    
                    if ($countReturn === 0 && isset($countOutput[0]) && is_numeric($countOutput[0])) {
                        $info['commits_behind'] = (int)$countOutput[0];
                    }

                    // Get changelog (recent commits)
                    $info['changelog'] = $this->getChangelog($gitPath);
                }
            }

        } catch (\Exception $e) {
            $info['error'] = $e->getMessage();
            Log::error('Update check failed: ' . $e->getMessage());
        }

        Cache::put('update_info', $info, 3600);
        
        return $info;
    }

    /**
     * Get changelog from GitHub
     */
    protected function getChangelog($gitPath)
    {
        $changelog = [];
        
        try {
            // Fetch latest
            exec("\"{$gitPath}\" fetch origin main 2>&1");
            
            // Get recent commits from origin/main that we don't have
            $output = [];
            exec("\"{$gitPath}\" log HEAD..origin/main --pretty=format:\"%h|%s|%an|%ar\" 2>&1", $output, $returnVar);
            
            if ($returnVar === 0 && !empty($output)) {
                foreach (array_slice($output, 0, 10) as $line) {
                    $parts = explode('|', $line);
                    if (count($parts) >= 4) {
                        $changelog[] = [
                            'hash' => $parts[0],
                            'message' => $parts[1],
                            'author' => $parts[2],
                            'date' => $parts[3],
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Could not get changelog: ' . $e->getMessage());
        }

        return $changelog;
    }

    /**
     * Get current version from VERSION file or git
     */
    protected function getCurrentVersion()
    {
        $versionFile = $this->basePath . '/VERSION';
        
        if (File::exists($versionFile)) {
            return trim(File::get($versionFile));
        }

        return config('app.version', '1.0.0');
    }

    /**
     * Get current git commit hash
     */
    protected function getCurrentCommit()
    {
        $gitPath = $this->findGit();
        
        if ($gitPath) {
            chdir($this->basePath);
            $commit = trim(shell_exec("\"{$gitPath}\" rev-parse --short HEAD 2>&1"));
            
            if ($commit && strpos($commit, 'fatal') === false) {
                return $commit;
            }
        }

        return 'unknown';
    }

    /**
     * Update version info after successful update
     */
    protected function updateVersionInfo()
    {
        $gitPath = $this->findGit();
        
        if ($gitPath) {
            chdir($this->basePath);
            $commit = trim(shell_exec("\"{$gitPath}\" rev-parse --short HEAD 2>&1"));
            
            // Update VERSION file
            File::put($this->basePath . '/VERSION', $commit);
        }
    }

    /**
     * Log update to history
     */
    protected function logUpdate($log)
    {
        $historyFile = storage_path('app/update_history.json');
        $history = [];
        
        if (File::exists($historyFile)) {
            $history = json_decode(File::get($historyFile), true) ?? [];
        }

        $history[] = [
            'date' => now()->toDateTimeString(),
            'version' => $this->getCurrentCommit(),
            'log' => $log,
            'user' => auth()->user()->name ?? 'System',
        ];

        // Keep only last 20 updates
        $history = array_slice($history, -20);

        File::put($historyFile, json_encode($history, JSON_PRETTY_PRINT));
    }

    /**
     * Get update history
     */
    protected function getUpdateHistory()
    {
        $historyFile = storage_path('app/update_history.json');
        
        if (File::exists($historyFile)) {
            $history = json_decode(File::get($historyFile), true) ?? [];
            return array_reverse($history);
        }

        return [];
    }

    /**
     * Find Git executable
     */
    protected function findGit()
    {
        $paths = [
            'C:\\Program Files\\Git\\bin\\git.exe',
            'C:\\Program Files (x86)\\Git\\bin\\git.exe',
            'C:\\laragon\\bin\\git\\bin\\git.exe',
            '/usr/bin/git',
            '/usr/local/bin/git',
            'git',
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                return $path;
            }
            
            // Check if in PATH
            if (stripos(PHP_OS, 'WIN') !== false) {
                $result = trim(shell_exec("where {$path} 2>nul"));
            } else {
                $result = trim(shell_exec("which {$path} 2>/dev/null"));
            }
            
            if (!empty($result) && strpos($result, 'not found') === false) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Find Composer executable
     */
    protected function findComposer()
    {
        $paths = [
            'C:\\ProgramData\\ComposerSetup\\bin\\composer.bat',
            'C:\\laragon\\bin\\composer\\composer.bat',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
            'composer',
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        // Check if in PATH
        $result = trim(shell_exec("composer --version 2>&1"));
        if (strpos($result, 'Composer') !== false) {
            return 'composer';
        }

        return null;
    }

    /**
     * Find NPM executable
     */
    protected function findNpm()
    {
        $paths = [
            'C:\\Program Files\\nodejs\\npm.cmd',
            'C:\\laragon\\bin\\nodejs\\npm.cmd',
            '/usr/local/bin/npm',
            '/usr/bin/npm',
            'npm',
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        // Check if in PATH
        $result = trim(shell_exec("npm --version 2>&1"));
        if (preg_match('/^\d+\.\d+/', $result)) {
            return 'npm';
        }

        return null;
    }
}
