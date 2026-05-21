<!-- resources/views/livewire/notification-bell.blade.php -->
<div class="relative inline-block" x-data="{open: @entangle('showPanel') }">
    <!-- Botón de campanilla -->
    <button type="button"
            class="relative text-gray-600 hover:text-gray-800 focus:outline-none"
            @click="open = !open">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Mini‑ventana de notificaciones -->
    <div x-show="open"
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white border rounded shadow-lg z-50 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-3 border-b dark:border-gray-700 flex justify-between items-center">
            <span class="font-medium text-gray-800 dark:text-gray-200">Notificaciones</span>
            @if($unreadCount > 0)
                <button type="button"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400"
                        wire:click="markAsRead('all')">
                    Marcar todas leídas
                </button>
            @endif
        </div>
        @forelse($notifications as $notif)
            <div class="px-3 py-2 border-b hover:bg-gray-100 dark:hover:bg-gray-700 dark:border-gray-700">
                <strong class="block text-sm text-gray-800 dark:text-gray-200">
                    {{ $notif['data']['evaluator_name'] ?? 'Alguien' }}
                    ({{ $notif['data']['evaluator_role'] ?? '' }})
                </strong>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ $notif['data']['message'] ?? '' }}
                </p>
                <small class="block text-right text-xs text-gray-500 dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
                </small>
                <button type="button"
                        class="mt-1 text-xs text-blue-600 hover:underline"
                        wire:click="markAsRead({{ $notif['id'] }})">
                    Marcar como leída
                </button>
            </div>
        @empty
            <div class="px-3 py-2 text-center text-gray-500 dark:text-gray-400">
                No hay notificaciones.
            </div>
        @endforelse
    </div>
</div>
