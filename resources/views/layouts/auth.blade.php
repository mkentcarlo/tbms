@php
    use App\Models\AppSetting;
    $appName = AppSetting::appName();
    $appLogo = AppSetting::appLogo();
    $appDescription = AppSetting::appDescription();
    $loginBackground = AppSetting::loginBackground();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ $appName }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .auth-bg {
            @if($loginBackground)
            background: url('{{ asset($loginBackground) }}') center center / cover no-repeat;
            @else
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            @endif
            position: relative;
            overflow: hidden;
        }
        @if($loginBackground)
        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }
        @else
        .auth-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse-bg 15s ease-in-out infinite;
        }
        .auth-bg::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 50%);
            animation: pulse-bg 20s ease-in-out infinite reverse;
        }
        @keyframes pulse-bg {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }
        @endif
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .fade-in-up-delay-1 {
            animation: fadeInUp 0.8s ease-out 0.1s forwards;
            opacity: 0;
        }
        .fade-in-up-delay-2 {
            animation: fadeInUp 0.8s ease-out 0.2s forwards;
            opacity: 0;
        }
        .fade-in-up-delay-3 {
            animation: fadeInUp 0.8s ease-out 0.3s forwards;
            opacity: 0;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .time-glow {
            text-shadow: 0 0 20px rgba(255,255,255,0.5), 0 0 40px rgba(255,255,255,0.3);
        }
        .pulse-dot {
            animation: pulseDot 1s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        .scale-in {
            animation: scaleIn 0.5s ease-out forwards;
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    @yield('styles')
</head>
<body class="h-full">
    <div class="min-h-full flex auth-bg">
        <!-- Left side - Decorative -->
        <div class="hidden lg:flex lg:flex-1 lg:flex-col lg:justify-center lg:items-center lg:p-12 relative z-10" x-data="clockWidget()" x-init="startClock()">
            <!-- Main Content Card -->
            <div class="text-center text-white max-w-lg">
                <!-- Logo & Brand -->
                <div class="fade-in-up mb-10">
                    @if($appLogo)
                        <div class="floating inline-block">
                            <img src="{{ asset($appLogo) }}" alt="{{ $appName }}" class="h-28 w-auto mx-auto drop-shadow-2xl">
                        </div>
                    @else
                        <div class="floating inline-block">
                            <div class="h-28 w-28 mx-auto rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-2xl border border-white/20">
                                <svg class="h-14 w-14 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    @endif
                    <h1 class="text-4xl font-bold mt-6 drop-shadow-lg tracking-tight">{{ $appName }}</h1>
                    @if($appDescription)
                        <p class="text-lg text-white/80 mt-2 drop-shadow">{{ $appDescription }}</p>
                    @endif
                </div>

                <!-- Divider -->
                <div class="fade-in-up-delay-1 flex items-center justify-center gap-4 mb-10">
                    <div class="h-px w-16 bg-gradient-to-r from-transparent to-white/40"></div>
                    <div class="h-2 w-2 rounded-full bg-white/60"></div>
                    <div class="h-px w-16 bg-gradient-to-l from-transparent to-white/40"></div>
                </div>

                <!-- Clock Display -->
                <div class="fade-in-up-delay-2 bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/10">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="h-2 w-2 bg-emerald-400 rounded-full pulse-dot"></div>
                        <span class="text-xs text-white/60 uppercase tracking-[0.2em] font-medium">Local Time</span>
                    </div>
                    <div class="time-glow flex items-baseline justify-center">
                        <span class="text-7xl font-bold tracking-tight tabular-nums" x-text="time">--:--</span>
                        <span class="text-3xl font-light text-white/70 ml-1 tabular-nums" x-text="seconds">--</span>
                        <span class="text-lg font-semibold text-white/50 ml-2" x-text="period">--</span>
                    </div>
                    <div class="mt-5 flex items-center justify-center gap-2 text-white/70">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium" x-text="date">Loading...</span>
                    </div>
                </div>

                <!-- Security badges -->
                <div class="fade-in-up-delay-3 mt-10 flex items-center justify-center gap-6">
                    <div class="flex items-center gap-2 text-white/70">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-xs font-medium">Secure</span>
                    </div>
                    <div class="h-4 w-px bg-white/30"></div>
                    <div class="flex items-center gap-2 text-white/70">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-xs font-medium">Encrypted</span>
                    </div>
                    <div class="h-4 w-px bg-white/30"></div>
                    <div class="flex items-center gap-2 text-white/70">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-medium">Protected</span>
                    </div>
                </div>
            </div>
            
            <!-- Decorative elements -->
            <div class="absolute bottom-10 left-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute top-16 right-16 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 left-8 w-20 h-20 bg-blue-300/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-1/4 right-12 w-24 h-24 bg-indigo-400/10 rounded-full blur-2xl"></div>
        </div>

        <!-- Right side - Login form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:w-[480px] xl:w-[520px]">
            <div class="mx-auto w-full max-w-sm lg:max-w-md">
                <!-- Mobile logo and time -->
                <div class="lg:hidden text-center mb-6 fade-in-up" x-data="clockWidget()" x-init="startClock()">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        @if($appLogo)
                            <img src="{{ asset($appLogo) }}" alt="{{ $appName }}" class="h-12 w-auto">
                        @else
                            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <h2 class="text-xl font-bold text-white">{{ $appName }}</h2>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <div class="h-1.5 w-1.5 bg-emerald-400 rounded-full pulse-dot"></div>
                        <span class="text-white/90 text-sm font-medium tabular-nums" x-text="time + ':' + seconds + ' ' + period"></span>
                        <span class="text-white/50">•</span>
                        <span class="text-white/70 text-xs" x-text="shortDate">Loading...</span>
                    </div>
                </div>

                <div class="glass-card rounded-2xl shadow-2xl p-8 sm:p-10 slide-in-right">
                    @yield('header')

                    @if(session('status'))
                        <div class="mt-4 rounded-lg bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        @yield('content')
                    </div>
                </div>

                <!-- Footer -->
                <p class="mt-6 text-center text-sm text-white/70 lg:text-blue-200">
                    &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Clock Widget Script -->
    <script>
        function clockWidget() {
            return {
                time: '--:--',
                seconds: '--',
                period: '--',
                date: 'Loading...',
                shortDate: 'Loading...',
                
                startClock() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },
                
                updateTime() {
                    const now = new Date();
                    
                    // Format time
                    let hours = now.getHours();
                    const minutes = now.getMinutes().toString().padStart(2, '0');
                    const secs = now.getSeconds().toString().padStart(2, '0');
                    const period = hours >= 12 ? 'PM' : 'AM';
                    
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    
                    this.time = hours + ':' + minutes;
                    this.seconds = secs;
                    this.period = period;
                    
                    // Format full date
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    };
                    this.date = now.toLocaleDateString('en-US', options);
                    
                    // Format short date for mobile
                    const shortOptions = { 
                        month: 'short', 
                        day: 'numeric',
                        year: 'numeric'
                    };
                    this.shortDate = now.toLocaleDateString('en-US', shortOptions);
                }
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
