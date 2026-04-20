<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Your Notifications</h3>
                        <div class="flex space-x-2">
                            @if($unreadCount > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Mark All as Read
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('notifications.preferences') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Preferences
                            </a>
                        </div>
                    </div>

                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="border rounded-lg p-4 {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-lg {{ $notification->is_read ? 'text-gray-700 dark:text-gray-300' : 'text-gray-900 dark:text-gray-100' }}">
                                                {{ $notification->title }}
                                            </h4>
                                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                                {{ $notification->message }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if(!$notification->is_read)
                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                        Mark Read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No notifications yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>