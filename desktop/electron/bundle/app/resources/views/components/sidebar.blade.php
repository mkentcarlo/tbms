<!-- Desktop Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:w-64 lg:flex-col">
    <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-5 pb-4 overflow-y-auto">
        <div class="flex items-center flex-shrink-0 px-4">
            <h1 class="text-2xl font-bold text-primary-600">TBMS</h1>
        </div>
        <div class="mt-5 flex-grow flex flex-col">
            <nav class="flex-1 px-2 space-y-1" aria-label="Sidebar">
                <!-- Dashboard -->
                <a href="/" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('/') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('/') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- Expenses -->
                <a href="{{ route('expense.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('expenses*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('expenses*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Expenses
                </a>

                <!-- Offices Dropdown -->
                <div x-data="{ open: {{ request()->is('offices*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        @click="open = !open"
                        class="group flex items-center w-full px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('offices*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('offices*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="flex-1 text-left">Manage Offices</span>
                        <svg 
                            class="h-5 w-5 flex-shrink-0 transition-transform duration-200" 
                            :class="{ 'rotate-180': open }"
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="pl-4 space-y-1"
                        style="display: {{ request()->is('offices*') ? 'block' : 'none' }};">
                        <a href="{{ route('office.office_groups') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.office_groups') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.office_groups') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Office Groups
                        </a>
                        <a href="{{ route('office.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.index') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Offices
                        </a>
                        <a href="{{ route('office.expense_classes') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.expense_classes') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.expense_classes') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Expense Classes
                        </a>
                    </div>
                </div>

                <!-- Allotments -->
                <a href="{{ route('allotment.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('allotments*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('allotments*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Allotments
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('reports*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('reports*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Reports
                </a>

                @auth
                    @if(auth()->user()->hasRole('admin'))
                        <!-- Admin Section -->
                        <div class="pt-4 mt-4 border-t border-gray-200">
                            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</p>
                        </div>

                        <!-- Users -->
                        <a href="{{ route('users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('users*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('users*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>

                        <!-- Roles -->
                        <a href="{{ route('roles.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('roles*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('roles*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Roles
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </div>
</div>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-40 w-64 lg:hidden"
     style="display: none;">
    <div class="flex flex-col h-full bg-white border-r border-gray-200 shadow-lg">
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-primary-600">TBMS</h1>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto pt-5 pb-4">
            <nav class="flex-1 px-2 space-y-1" aria-label="Sidebar">
                <!-- Dashboard -->
                <a href="/" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('/') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('/') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- Expenses -->
                <a href="{{ route('expense.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('expenses*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('expenses*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Expenses
                </a>

                <!-- Offices Dropdown -->
                <div x-data="{ open: {{ request()->is('offices*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        @click="open = !open"
                        class="group flex items-center w-full px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('offices*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('offices*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="flex-1 text-left">Manage Offices</span>
                        <svg 
                            class="h-5 w-5 flex-shrink-0 transition-transform duration-200" 
                            :class="{ 'rotate-180': open }"
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div 
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="pl-4 space-y-1"
                        style="display: {{ request()->is('offices*') ? 'block' : 'none' }};">
                        <a href="{{ route('office.office_groups') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.office_groups') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.office_groups') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Office Groups
                        </a>
                        <a href="{{ route('office.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.index') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Offices
                        </a>
                        <a href="{{ route('office.expense_classes') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('office.expense_classes') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('office.expense_classes') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Expense Classes
                        </a>
                    </div>
                </div>

                <!-- Allotments -->
                <a href="{{ route('allotment.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('allotments*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('allotments*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Allotments
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-lg {{ request()->is('reports*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ request()->is('reports*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Reports
                </a>
            </nav>
        </div>
    </div>
</div>
