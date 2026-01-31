@extends('layouts.modern')

@section('title', 'System Updates')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">System Updates</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">System Updates</h1>
            <p class="mt-1 text-sm text-gray-500">Manage application updates from GitHub</p>
        </div>
        <form action="{{ route('update.check') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-secondary inline-flex items-center gap-2" id="checkBtn">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Check for Updates
            </button>
        </form>
    </div>
@endsection

@section('content')
    <!-- Update In Progress Banner -->
    <div id="updateProgress" class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6" style="display: none;">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-blue-800">Update In Progress</h3>
                <p class="mt-1 text-sm text-blue-700" id="currentStep">Starting update...</p>
                
                <!-- Live Log -->
                <div class="mt-4 bg-white rounded-lg border border-blue-200 p-4 max-h-60 overflow-y-auto" id="liveLog">
                    <p class="text-sm text-gray-500">Waiting for updates...</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Current Version Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Current Version</p>
                    <p class="text-xl font-semibold text-gray-900 font-mono">{{ $updateInfo['current_commit'] ?? 'unknown' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 {{ $updateInfo['update_available'] ? 'bg-green-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 {{ $updateInfo['update_available'] ? 'text-green-600' : 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Latest Version</p>
                    <p class="text-xl font-semibold text-gray-900 font-mono">{{ $updateInfo['latest_commit'] ?? $updateInfo['current_commit'] ?? 'unknown' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Last Checked</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $updateInfo['last_checked'] ?? 'Never' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Available Card -->
    @if($updateInfo['update_available'])
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-green-800">Update Available!</h3>
                    <p class="mt-1 text-sm text-green-700">
                        A new version is available. You are 
                        <strong>{{ $updateInfo['commits_behind'] }}</strong> commit(s) behind.
                    </p>
                    
                    @if(!empty($updateInfo['changelog']))
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-green-800 mb-2">Changelog:</h4>
                            <div class="bg-white rounded-lg border border-green-200 divide-y divide-green-100 max-h-60 overflow-y-auto">
                                @foreach($updateInfo['changelog'] as $commit)
                                    <div class="px-4 py-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-mono text-green-600">{{ $commit['hash'] }}</span>
                                            <span class="text-xs text-gray-500">{{ $commit['date'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-800 mt-1">{{ $commit['message'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">by {{ $commit['author'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form action="{{ route('update.apply') }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to apply this update?\n\nThis will:\n1. Backup your database\n2. Put the app in maintenance mode\n3. Pull latest code from GitHub\n4. Run migrations\n5. Rebuild caches\n\nProceed?');">
                            @csrf
                            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Apply Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">You're up to date!</h3>
                    <p class="text-sm text-gray-600">
                        @if($updateInfo['error'])
                            <span class="text-red-600">{{ $updateInfo['error'] }}</span>
                        @else
                            You are running the latest version.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Update Log (if just updated) -->
    @if(session('update_log'))
        <div class="card mb-6">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700">Update Log</h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @foreach(session('update_log') as $log)
                        <div class="flex items-start gap-3">
                            @if($log['status'] === 'success')
                                <span class="flex-shrink-0 h-6 w-6 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            @elseif($log['status'] === 'warning')
                                <span class="flex-shrink-0 h-6 w-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </span>
                            @elseif($log['status'] === 'error')
                                <span class="flex-shrink-0 h-6 w-6 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            @elseif($log['status'] === 'skipped')
                                <span class="flex-shrink-0 h-6 w-6 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </span>
                            @else
                                <span class="flex-shrink-0 h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </span>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $log['step'] }}</p>
                                <p class="text-sm text-gray-600">{{ $log['message'] }}</p>
                                @if(isset($log['output']) && !empty($log['output']))
                                    <pre class="mt-2 text-xs bg-gray-100 p-2 rounded overflow-x-auto">{{ $log['output'] }}</pre>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Update History -->
    @if(!empty($updateHistory))
        <div class="card">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700">Update History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated By</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($updateHistory as $history)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history['date'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                                        {{ $history['version'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $history['user'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $hasError = collect($history['log'] ?? [])->contains('status', 'error');
                                    @endphp
                                    @if($hasError)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Success
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Info Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">How Updates Work</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Updates are pulled from your GitHub repository's <strong>main</strong> branch</li>
                        <li>A database backup is automatically created before updating</li>
                        <li>The app goes into maintenance mode during updates</li>
                        <li>Composer dependencies and migrations are run automatically</li>
                        <li>Updates are checked automatically every hour</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let pollInterval = null;
    let lastLogCount = 0;
    let wasInProgress = false;

    function checkUpdateStatus() {
        fetch('{{ route("update.status") }}')
            .then(response => response.json())
            .then(data => {
                const progressDiv = document.getElementById('updateProgress');
                const checkBtn = document.getElementById('checkBtn');
                const currentStep = document.getElementById('currentStep');
                const liveLog = document.getElementById('liveLog');

                if (data.in_progress || data.status === 'running') {
                    wasInProgress = true;
                    progressDiv.style.display = 'block';
                    if (checkBtn) checkBtn.disabled = true;
                    
                    currentStep.textContent = 'Current step: ' + (data.step || 'Processing...');
                    
                    // Update live log
                    if (data.log && data.log.length > 0) {
                        let logHtml = '';
                        data.log.forEach(function(entry) {
                            let statusIcon = '';
                            let statusClass = '';
                            
                            if (entry.status === 'success') {
                                statusIcon = '<svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                                statusClass = 'text-green-700';
                            } else if (entry.status === 'warning') {
                                statusIcon = '<svg class="h-4 w-4 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
                                statusClass = 'text-yellow-700';
                            } else if (entry.status === 'error') {
                                statusIcon = '<svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                                statusClass = 'text-red-700';
                            } else if (entry.status === 'running') {
                                statusIcon = '<svg class="h-4 w-4 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                                statusClass = 'text-blue-700';
                            } else {
                                statusIcon = '<svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>';
                                statusClass = 'text-gray-600';
                            }
                            
                            logHtml += '<div class="flex items-start gap-2 py-1 ' + statusClass + '">' +
                                statusIcon +
                                '<div><span class="font-medium">' + entry.step + ':</span> ' + entry.message + '</div>' +
                                '</div>';
                        });
                        liveLog.innerHTML = logHtml;
                        
                        // Auto-scroll to bottom
                        if (data.log.length > lastLogCount) {
                            liveLog.scrollTop = liveLog.scrollHeight;
                            lastLogCount = data.log.length;
                        }
                    }
                } else if ((data.status === 'completed' || data.status === 'failed') && wasInProgress) {
                    // Update finished AND we were actually watching an update in progress
                    // Only reload once
                    clearInterval(pollInterval);
                    pollInterval = null;
                    window.location.reload();
                } else {
                    // No update in progress, stop polling
                    progressDiv.style.display = 'none';
                    if (checkBtn) checkBtn.disabled = false;
                    
                    // Stop polling if nothing is happening
                    if (pollInterval && !wasInProgress) {
                        clearInterval(pollInterval);
                        pollInterval = null;
                    }
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
    }

    // Start polling on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check immediately
        checkUpdateStatus();
        
        // Then poll every 2 seconds only if needed
        pollInterval = setInterval(checkUpdateStatus, 2000);
        
        // Stop polling after 10 seconds if no update is in progress
        setTimeout(function() {
            if (pollInterval && !wasInProgress) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }, 10000);
    });
</script>
@endsection
