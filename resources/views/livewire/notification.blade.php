<div class="flex duration-650 justify-center fixed z-50 top-0 w-full text-gray-50 ">
    @if ($showNotification)
        <div x-init="setTimeout(() => {
            $wire.hideNotification();
        }, {{ $timeout }});"
            class="flex  px-4 py-4 rounded-b-lg gap-2 drop-shadow-xl 
                    {{ $notificationType == App\Enums\NotificationType::Alert->value ? 'bg-red-600' : '' }}
                    {{ $notificationType == App\Enums\NotificationType::Information->value ? 'bg-indigo-800' : '' }}
                    {{ $notificationType == App\Enums\NotificationType::Warning->value ? 'bg-orange-500' : '' }}">
            @if ($notificationType == App\Enums\NotificationType::Alert->value ? 'bg-red-600' : '')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                </svg>
            @endif
            <span class="text-gray-50">{{ $message }}</span>
        </div>
    @endif
</div>
