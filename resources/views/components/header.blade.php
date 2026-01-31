<!-- Top Navigation -->
<header class="sticky top-0 z-10 bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Left side: Mobile menu + Search -->
        <div class="flex items-center flex-1 gap-4">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Search bar with Autocomplete -->
            <div class="flex-1 max-w-lg" x-data="searchAutocomplete()">
                <form action="{{ route('expense.index') }}" method="GET" class="relative" @submit="onSubmit">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="s" 
                        x-model="query"
                        @input.debounce.300ms="search"
                        @focus="showResults = results.length > 0"
                        @keydown.arrow-down.prevent="moveDown"
                        @keydown.arrow-up.prevent="moveUp"
                        @keydown.enter.prevent="selectCurrent"
                        @keydown.escape="showResults = false"
                        placeholder="Search by account code..." 
                        autocomplete="off"
                        class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                    >
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-primary-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                    
                    <!-- Autocomplete Dropdown -->
                    <div 
                        x-show="showResults && results.length > 0" 
                        @click.away="showResults = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 max-h-60 overflow-auto"
                        style="display: none;">
                        <ul class="py-1">
                            <template x-for="(result, index) in results" :key="index">
                                <li 
                                    @click="selectResult(result)"
                                    @mouseenter="selectedIndex = index"
                                    :class="{ 'bg-primary-50 text-primary-700': selectedIndex === index }"
                                    class="px-4 py-2 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="result.account_code"></p>
                                            <p class="text-xs text-gray-500 truncate" x-text="result.expense_class || 'N/A'"></p>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 text-xs text-gray-500">
                            Press <kbd class="px-1 py-0.5 bg-gray-200 rounded text-gray-600">Enter</kbd> to search or click a result
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div x-show="loading" class="absolute inset-y-0 right-10 flex items-center pr-3">
                        <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <script>
                function searchAutocomplete() {
                    return {
                        query: '{{ request("s") }}',
                        results: [],
                        showResults: false,
                        loading: false,
                        selectedIndex: -1,

                        async search() {
                            if (this.query.length < 1) {
                                this.results = [];
                                this.showResults = false;
                                return;
                            }

                            this.loading = true;
                            
                            try {
                                const response = await fetch(`{{ route('expense.autocomplete') }}?q=${encodeURIComponent(this.query)}`);
                                this.results = await response.json();
                                this.showResults = this.results.length > 0;
                                this.selectedIndex = -1;
                            } catch (error) {
                                console.error('Search error:', error);
                                this.results = [];
                            } finally {
                                this.loading = false;
                            }
                        },

                        selectResult(result) {
                            this.query = result.account_code;
                            this.showResults = false;
                            this.$el.querySelector('form').submit();
                        },

                        moveDown() {
                            if (this.selectedIndex < this.results.length - 1) {
                                this.selectedIndex++;
                            }
                        },

                        moveUp() {
                            if (this.selectedIndex > 0) {
                                this.selectedIndex--;
                            }
                        },

                        selectCurrent() {
                            if (this.selectedIndex >= 0 && this.results[this.selectedIndex]) {
                                this.selectResult(this.results[this.selectedIndex]);
                            } else {
                                this.$el.querySelector('form').submit();
                            }
                        },

                        onSubmit(e) {
                            this.showResults = false;
                        }
                    }
                }
            </script>
        </div>

        <!-- Right side: DateTime + Update notification + User menu -->
        <div class="flex items-center gap-3 ml-4">
            <!-- Date and Time Display -->
            <div class="hidden md:flex items-center gap-3 text-sm" x-data="headerClock()" x-init="startClock()">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-600 font-medium" x-text="date">--</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-primary-50 rounded-lg border border-primary-100">
                    <div class="h-2 w-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-primary-700 font-semibold tabular-nums" x-text="time">--:--:--</span>
                </div>
            </div>

            <!-- Compact DateTime for smaller screens -->
            <div class="hidden sm:flex md:hidden items-center gap-1.5 px-2.5 py-1.5 bg-gray-50 rounded-lg border border-gray-200 text-xs" x-data="headerClock()" x-init="startClock()">
                <div class="h-1.5 w-1.5 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-600 font-medium tabular-nums" x-text="time">--:--:--</span>
            </div>
        </div>

        <!-- Right side actions -->
        <div class="flex items-center gap-3">
            <!-- Update Available Notification (super_admin only) -->
            @auth
                @if(auth()->user()->hasRole('super_admin'))
                    @php
                        $updateInfo = \Illuminate\Support\Facades\Cache::get('update_info', []);
                        $hasUpdate = $updateInfo['update_available'] ?? false;
                    @endphp
                    @if($hasUpdate)
                        <a href="{{ route('update.index') }}" class="relative p-2 text-green-600 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 rounded-lg bg-green-50 hover:bg-green-100 transition-colors" title="Update Available">
                            <span class="sr-only">Update available</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                        </a>
                    @endif
                @endif
            @endauth

            <!-- User menu -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    type="button"
                    class="flex items-center gap-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 rounded-lg px-2 py-1.5 hover:bg-gray-50">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset(auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="h-8 w-8 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-medium text-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <span class="hidden md:block text-gray-700 font-medium whitespace-nowrap">{{ auth()->user()->name ?? 'User' }}</span>
                    <svg 
                        class="h-4 w-4 text-gray-400 flex-shrink-0 transition-transform duration-200" 
                        :class="{ 'rotate-180': open }"
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div 
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                    style="display: none;">
                    <div class="py-1">
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Your Profile</a>
                        @if(auth()->user()->hasRole('super_admin'))
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">Settings</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Clock Script -->
    <script>
        function headerClock() {
            return {
                time: '--:--:--',
                date: '--',
                
                startClock() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },
                
                updateTime() {
                    const now = new Date();
                    
                    // Format time (12-hour format with seconds)
                    let hours = now.getHours();
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const seconds = now.getSeconds().toString().padStart(2, '0');
                    const period = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    this.time = hours + ':' + minutes + ':' + seconds + ' ' + period;
                    
                    // Format date
                    const options = { 
                        weekday: 'short', 
                        month: 'short', 
                        day: 'numeric'
                    };
                    this.date = now.toLocaleDateString('en-US', options);
                }
            }
        }
    </script>
</header>
