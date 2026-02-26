<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 tracking-tight">Manage Events</h2>
                <p class="mt-0.5 text-sm text-slate-500">Oversee and manage all scheduled events</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
            </span>
        </div>
    </x-slot>

    {{-- Inline styles for custom tokens not in Tailwind base --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

        .events-root {
            font-family: 'DM Sans', sans-serif;
        }
        .mono {
            font-family: 'DM Mono', monospace;
        }

        /* Subtle row shimmer on hover */
        .event-row {
            transition: background 0.15s ease;
        }
        .event-row:hover {
            background: #f8fafc;
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .status-pill::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .status-upcoming  { background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; }
        .status-upcoming::before  { background:#3B82F6; box-shadow:0 0 0 2px #BFDBFE; }
        .status-ongoing   { background:#F0FDF4; color:#15803D; border:1px solid #BBF7D0; }
        .status-ongoing::before   { background:#22C55E; box-shadow:0 0 0 2px #BBF7D0; animation: pulse-dot 1.5s infinite; }
        .status-completed { background:#F8FAFC; color:#64748B; border:1px solid #E2E8F0; }
        .status-completed::before { background:#94A3B8; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Action buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s, transform 0.1s;
            color: #64748B;
        }
        .action-btn:hover { transform: translateY(-1px); }
        .action-btn-view:hover  { background: #F0FDF4; color: #16A34A; }
        .action-btn-edit:hover  { background: #EFF6FF; color: #2563EB; }
        .action-btn-del:hover   { background: #FEF2F2; color: #DC2626; }

        /* Avatar initial badge */
        .avatar-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            flex-shrink: 0;
        }

        /* Subtle card border glow on success */
        .toast-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 3px solid #22c55e;
        }
    </style>

    <div class="events-root py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Toast --}}
            @if(session('success'))
                <div class="toast-success flex items-center gap-3 px-5 py-3.5 rounded-xl border border-green-200 shadow-sm">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Toolbar --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 tracking-tight">All Events</h3>
                        <p class="text-xs text-slate-400 mt-0.5">View, edit, and manage every event in your system</p>
                    </div>
                    <a href="{{ route('admin.event.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-medium rounded-xl shadow-sm transition-all duration-150 hover:-translate-y-px">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Event
                    </a>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/60">
                                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Event</th>
                                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Description</th>
                                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Duration</th>
                                <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-3.5 text-right text-[11px] font-semibold text-slate-500 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($events as $event)
                                @php
                                    $colors = [
                                        'bg-violet-100 text-violet-700',
                                        'bg-sky-100 text-sky-700',
                                        'bg-amber-100 text-amber-700',
                                        'bg-rose-100 text-rose-700',
                                        'bg-teal-100 text-teal-700',
                                        'bg-indigo-100 text-indigo-700',
                                    ];
                                    $color = $colors[$loop->index % count($colors)];
                                @endphp
                                <tr class="event-row">
                                    {{-- Event Name --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar-badge {{ $color }}">
                                                {{ strtoupper(substr($event->event_name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900 leading-tight">{{ $event->event_name }}</div>
                                                <div class="text-xs text-slate-400 mt-0.5 mono">{{ ucfirst($event->event_type) }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Description --}}
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-slate-500 leading-snug line-clamp-2">
                                            {{ $event->event_description ? Str::limit($event->event_description, 70) : '—' }}
                                        </p>
                                    </td>

                                    {{-- Dates --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="mono text-xs text-slate-700 font-medium">{{ $event->start_date->format('d M Y') }}</span>
                                            <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                                {{ $event->end_date->format('d M Y') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-pill status-{{ $event->event_status }}">
                                            {{ ucfirst($event->event_status) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-1">
                                            {{-- View --}}
                                            <a href="{{ route('admin.event.show', $event) }}"
                                               class="action-btn action-btn-view bg-slate-50 border border-slate-200"
                                               title="View">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.event.edit', $event) }}"
                                               class="action-btn action-btn-edit bg-slate-50 border border-slate-200"
                                               title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('admin.event.destroy', $event) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-btn-del bg-slate-50 border border-slate-200"
                                                        onclick="return confirm('Delete this event? This cannot be undone.')"
                                                        title="Delete">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mb-4">
                                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-semibold text-slate-900 mb-1">No events yet</h3>
                                        <p class="text-sm text-slate-400 mb-5">Create your first event to get started</p>
                                        <a href="{{ route('admin.event.create') }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-medium rounded-xl transition-all duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Create Event
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>