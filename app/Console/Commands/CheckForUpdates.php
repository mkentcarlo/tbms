<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CheckForUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates from GitHub';

    protected $basePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->basePath = base_path();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for updates...');

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
                $this->error('Git not found on system.');
                $info['error'] = 'Git not found on system.';
                Cache::put('update_info', $info, 3600);
                return Command::FAILURE;
            }

            $this->line("Current version: {$info['current_commit']}");

            // Get latest remote commit
            chdir($this->basePath);
            
            // Fetch first
            exec("\"{$gitPath}\" fetch origin main 2>&1", $fetchOutput, $fetchReturn);
            
            $output = [];
            exec("\"{$gitPath}\" ls-remote origin main 2>&1", $output, $returnVar);
            
            if ($returnVar !== 0 || empty($output)) {
                // Try 'master' branch
                exec("\"{$gitPath}\" ls-remote origin master 2>&1", $output, $returnVar);
            }

            if (!empty($output) && isset($output[0])) {
                $parts = preg_split('/\s+/', $output[0]);
                $info['latest_commit'] = substr($parts[0], 0, 7);
                
                $this->line("Latest version: {$info['latest_commit']}");
                
                // Check if update available
                if ($info['current_commit'] !== $info['latest_commit']) {
                    $info['update_available'] = true;
                    
                    // Get commit count difference
                    $countOutput = [];
                    exec("\"{$gitPath}\" rev-list HEAD..origin/main --count 2>&1", $countOutput, $countReturn);
                    
                    if ($countReturn === 0 && isset($countOutput[0]) && is_numeric($countOutput[0])) {
                        $info['commits_behind'] = (int)$countOutput[0];
                    }

                    // Get changelog
                    $changelogOutput = [];
                    exec("\"{$gitPath}\" log HEAD..origin/main --pretty=format:\"%h|%s|%an|%ar\" 2>&1", $changelogOutput, $changelogReturn);
                    
                    if ($changelogReturn === 0 && !empty($changelogOutput)) {
                        foreach (array_slice($changelogOutput, 0, 10) as $line) {
                            $parts = explode('|', $line);
                            if (count($parts) >= 4) {
                                $info['changelog'][] = [
                                    'hash' => $parts[0],
                                    'message' => $parts[1],
                                    'author' => $parts[2],
                                    'date' => $parts[3],
                                ];
                            }
                        }
                    }

                    $this->newLine();
                    $this->info("Update available! You are {$info['commits_behind']} commit(s) behind.");
                    
                    if (!empty($info['changelog'])) {
                        $this->newLine();
                        $this->line('Recent changes:');
                        foreach ($info['changelog'] as $commit) {
                            $this->line("  [{$commit['hash']}] {$commit['message']} - {$commit['author']}");
                        }
                    }
                    
                    Log::info("Update available: {$info['commits_behind']} commit(s) behind.");
                } else {
                    $this->info('You are running the latest version.');
                }
            } else {
                $this->warn('Could not fetch remote version.');
                $info['error'] = 'Could not fetch remote version.';
            }

        } catch (\Exception $e) {
            $this->error('Error checking for updates: ' . $e->getMessage());
            $info['error'] = $e->getMessage();
            Log::error('Update check failed: ' . $e->getMessage());
        }

        // Cache the result
        Cache::put('update_info', $info, 3600);

        return $info['update_available'] ? Command::SUCCESS : Command::SUCCESS;
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
                $result = trim(shell_exec("where git 2>nul"));
            } else {
                $result = trim(shell_exec("which git 2>/dev/null"));
            }
            
            if (!empty($result) && strpos($result, 'not found') === false) {
                return 'git';
            }
        }

        return null;
    }
}
