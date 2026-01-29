@props(['id', 'title', 'size' => 'md'])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-7xl',
    ];
@endphp

<div data-modal="{{ $id }}" 
     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center"
     style="display: none;">
    
    <!-- Backdrop -->
    <div data-modal-backdrop
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
    </div>

    <!-- Modal -->
    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all {{ $sizeClasses[$size] }} w-full mx-4 my-8 z-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <button data-modal-close class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4">
            {{ $slot }}
        </div>
        
        <!-- Footer (optional) -->
        @if(isset($footer))
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
