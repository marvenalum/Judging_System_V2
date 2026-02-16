<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ $event->event_name }}</h2>
        <p class="page-subtitle">View and manage the details of this event</p>
    </x-slot>

    <style>
        .page-title { font-family: 'Cabinet Grotesk', sans-serif; font-size: 2rem; font-weight: 800; color: #1a1d29; }
        .page-subtitle { color: #6b7280; font-size: 1rem; margin-bottom: 1.5rem; }

        .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 2rem; margin-bottom: 2rem; }
        .card-header { font-weight: 700; font-size: 1.25rem; margin-bottom: 1rem; color: #1a1d29; }

        .btn-primary { background: #0a4d68; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: 0.3s; }
        .btn-primary:hover { background: #088395; }

        .btn-secondary { background: #6b7280; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: 0.3s; }
        .btn-secondary:hover { background: #4b5563; }

        .btn-danger { background: #ef4444; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: 0.3s; }
        .btn-danger:hover { background: #b91c1c; }

        .event-info p { margin-bottom: 0.5rem; font-size: 0.9375rem; color: #1a1d29; }
        .event-info span.status { padding: 0.25rem 0.5rem; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }

        .status-upcoming { background: #bfdbfe; color: #1e40af; }
        .status-ongoing { background: #d1fae5; color: #065f46; }
        .status-ended { background: #e5e7eb; color: #374151; }

        @media (max-width: 768px) {
            .page-title { font-size: 1.5rem; }
            .page-subtitle { font-size: 0.875rem; }
            .event-info p { font-size: 0.875rem; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('admin.event.edit', $event) }}" class="btn-primary">Edit Event</a>
                <a href="{{ route('admin.event.index') }}" class="btn-secondary">Back to Events</a>
                <form method="POST" action="{{ route('admin.event.destroy', $event) }}" onsubmit="return confirm('Are you sure you want to delete this event?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Delete Event</button>
                </form>
            </div>

            <!-- Event Details Card -->
            <div class="card event-info">
                <div class="card-header">Event Information</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong>ID:</strong> {{ $event->id }}</p>
                        <p><strong>Name:</strong> {{ $event->event_name }}</p>
                        <p><strong>Description:</strong> {{ $event->event_description }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($event->event_type) }}</p>
                        <p><strong>Start Date:</strong> {{ $event->start_date->format('Y-m-d H:i') }}</p>
                        <p><strong>End Date:</strong> {{ $event->end_date->format('Y-m-d H:i') }}</p>
                        <p><strong>Status:</strong>
                            <span class="status status-{{ $event->event_status }}">
                                {{ ucfirst($event->event_status) }}
                            </span>
                        </p>
                        <p><strong>Created At:</strong> {{ $event->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Updated At:</strong> {{ $event->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
