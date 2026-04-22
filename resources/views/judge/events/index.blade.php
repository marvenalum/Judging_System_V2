
@extends('layouts.judge')

@section('header')
    <h2 class="page-title">My Assigned Events</h2>
@endsection
@section( 'content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dark themed event management design -->
            <div style="background: #0d0f14; color: #e8eaf2; padding: 40px 24px; font-family: 'Sora', sans-serif;">
                
                <!-- Embedded CSS from criteria template -->
                <style>
                  :root {
                    --bg: #0d0f14;
                    --surface: #13161e;
                    --surface-2: #1a1e28;
                    --border: #232736;
                    --border-hover: #2e3347;
                    --accent: #4f7cff;
                    --accent-dim: rgba(79,124,255,0.12);
                    --accent-glow: rgba(79,124,255,0.3);
                    --green: #22c887;
                    --green-dim: rgba(34,200,135,0.12);
                    --cyan: #38bdf8;
                    --text-primary: #e8eaf2;
                    --text-secondary: #8b90a4;
                    --text-muted: #4e5368;
                    --font: 'Sora', sans-serif;
                    --mono: 'JetBrains Mono', monospace;
                  }

                  * { margin: 0; padding: 0; box-sizing: border-box; }

                  .page { max-width: 1200px; margin: 0 auto; }

                  /* Top bar */
                  .topbar {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    margin-bottom: 32px;
                    animation: fadeDown .45s ease both;
                  }

                  .topbar-left .eyebrow {
                    font-family: var(--mono);
                    font-size: 11px;
                    font-weight: 500;
                    letter-spacing: .15em;
                    color: var(--accent);
                    text-transform: uppercase;
                    margin-bottom: 6px;
                  }

                  .topbar-left h1 {
                    font-size: 26px;
                    font-weight: 700;
                    color: var(--text-primary);
                    letter-spacing: -.4px;
                  }

                  .topbar-left p {
                    font-size: 13px;
                    color: var(--text-secondary);
                    margin-top: 4px;
                  }

                  .topbar-right {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                  }

                  .count-badge {
                    font-family: var(--mono);
                    font-size: 12px;
                    color: var(--text-muted);
                    background: var(--surface-2);
                    border: 1px solid var(--border);
                    padding: 6px 14px;
                    border-radius: 8px;
                  }

                  /* Card shell */
                  .card {
                    background: var(--surface);
                    border: 1px solid var(--border);
                    border-radius: 16px;
                    overflow: hidden;
                    animation: fadeUp .5s ease both;
                  }

                  /* Table styles */
                  table { width: 100%; border-collapse: collapse; }

                  thead tr { background: var(--surface-2); border-bottom: 1px solid var(--border); }

                  thead th {
                    padding: 13px 16px;
                    text-align: left;
                    font-family: var(--mono);
                    font-size: 10.5px;
                    font-weight: 500;
                    letter-spacing: .12em;
                    text-transform: uppercase;
                    color: var(--text-muted);
                    white-space: nowrap;
                  }

                  thead th:last-child { text-align: right; }

                  tbody tr {
                    border-bottom: 1px solid var(--border);
                    transition: background .15s;
                    animation: fadeRow .35s ease both;
                  }

                  tbody tr:last-child { border-bottom: none; }

                  tbody tr:hover { background: rgba(255,255,255,.025); }

                  td { padding: 14px 16px; font-size: 13.5px; color: var(--text-secondary); vertical-align: middle; }

                  /* Event specific */
                  .cell-name {
                    font-weight: 600;
                    color: var(--text-primary);
                    font-size: 14px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                  }

                  .dot {
                    width: 6px; height: 6px;
                    border-radius: 50%;
                    flex-shrink: 0;
                  }

                  .cell-desc {
                    max-width: 200px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    color: var(--text-secondary);
                  }

                  .date-pill, .event-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 12px;
                    font-weight: 500;
                    color: var(--accent);
                    background: var(--accent-dim);
                    border: 1px solid rgba(79,124,255,.22);
                    padding: 4px 10px;
                    border-radius: 6px;
                    white-space: nowrap;
                  }

                  .status-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 4px 10px;
                    border-radius: 6px;
                    font-size: 11.5px;
                    font-weight: 600;
                    letter-spacing: .02em;
                  }

                  .status-badge::before {
                    content: '';
                    width: 5px; height: 5px;
                    border-radius: 50%;
                    flex-shrink: 0;
                  }

                  .status-upcoming {
                    background: rgba(79,124,255,.12);
                    color: var(--accent);
                    border: 1px solid rgba(79,124,255,.2);
                  }

                  .status-upcoming::before { background: var(--accent); }

                  .status-ongoing {
                    background: var(--green-dim);
                    color: var(--green);
                    border: 1px solid rgba(34,200,135,.2);
                  }

                  .status-ongoing::before { background: var(--green); }

                  .status-completed {
                    background: rgba(167,139,250,.12);
                    color: #a78bfa;
                    border: 1px solid rgba(167,139,250,.2);
                  }

                  .status-completed::before { background: #a78bfa; }

                  .count-pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    font-size: 12px;
                    font-weight: 500;
                    padding: 4px 10px;
                    border-radius: 6px;
                  }

                  .count-pill.categories {
                    color: var(--cyan);
                    background: rgba(56,189,248,.12);
                    border: 1px solid rgba(56,189,248,.2);
                  }

                  /* Actions */
                  .actions {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 6px;
                  }

                  .btn-icon {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 6px 12px;
                    font-family: var(--font);
                    font-size: 12px;
                    font-weight: 500;
                    border-radius: 7px;
                    cursor: pointer;
                    text-decoration: none;
                    border: 1px solid transparent;
                    transition: background .15s, border-color .15s, color .15s;
                    background: transparent;
                    color: inherit;
                  }

                  .btn-view { color: var(--cyan); background: rgba(56,189,248,.12); border-color: rgba(56,189,248,.2); }
                  .btn-view:hover { background: rgba(56,189,248,.2); border-color: rgba(56,189,248,.4); }

                  .btn-edit { color: var(--accent); background: var(--accent-dim); border-color: rgba(79,124,255,.2); }
                  .btn-edit:hover { background: rgba(79,124,255,.2); border-color: rgba(79,124,255,.4); }

                  .btn-delete { color: #ff5c6c; background: rgba(255,92,108,.1); border-color: rgba(255,92,108,.18); }
                  .btn-delete:hover { background: rgba(255,92,108,.18); border-color: rgba(255,92,108,.35); }

                  /* Empty state */
                  .empty {
                    padding: 64px 24px;
                    text-align: center;
                    color: var(--text-muted);
                  }

                  .empty svg { width: 40px; height: 40px; margin: 0 auto 16px; opacity: .3; }

                  /* Footer */
                  .card-footer {
                    padding: 14px 20px;
                    border-top: 1px solid var(--border);
                    background: var(--surface-2);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 12px;
                    color: var(--text-muted);
                  }

                  /* Alerts */
                  .alert {
                    padding: 12px 16px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    font-size: 13.5px;
                    font-weight: 500;
                    animation: fadeDown .3s ease both;
                  }

                  .alert-success {
                    background: var(--green-dim);
                    color: var(--green);
                    border: 1px solid rgba(34,200,135,.2);
                  }

                  /* Animations */
                  @keyframes fadeDown {
                    from { opacity: 0; transform: translateY(-12px); }
                    to   { opacity: 1; transform: translateY(0); }
                  }
                  @keyframes fadeUp {
                    from { opacity: 0; transform: translateY(14px); }
                    to   { opacity: 1; transform: translateY(0); }
                  }
                  @keyframes fadeRow {
                    from { opacity: 0; transform: translateX(-8px); }
                    to   { opacity: 1; transform: translateX(0); }
                  }
                </style>

                <div class="page">

                  <!-- Top bar -->
                  <div class="topbar">
                    <div class="topbar-left">
                      <div class="eyebrow">Judge Panel</div>
                      <h1>Event Management</h1>
                      <p>Review your assigned events and judging assignments</p>
                    </div>
                    <div class="topbar-right">
                      <span class="count-badge">{{ $events->count() }} events</span>
                    </div>
                  </div>

                  <!-- Alert Messages -->
                  @if(session('success'))
                    <div class="alert alert-success">
                      {{ session('success') }}
                    </div>
                  @endif

                  @if(session('error'))
                    <div class="alert" style="background: rgba(255,92,108,.1); color: #ff5c6c; border: 1px solid rgba(255,92,108,.2);">
                      {{ session('error') }}
                    </div>
                  @endif

                  <!-- Table card -->
                  <div class="card">
                    <div style="overflow-x:auto;">
                      <table>
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th>Categories</th>
                            <th>Participants</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>

                          @forelse($events as $index => $event)
                          <tr style="animation-delay: {{ $index * 0.05 }}s">
                            <td>
                              <div class="cell-name">
                                <span class="dot" style="background:{{ $event->event_status === 'ongoing' ? '#22c887' : ($event->event_status === 'upcoming' ? '#4f7cff' : '#4e5368') }};box-shadow:0 0 6px {{ $event->event_status === 'ongoing' ? '#22c88788' : ($event->event_status === 'upcoming' ? '#4f7cff88' : '#4e536888') }};"></span>
                                {{ $event->event_name }}
                              </div>
                            </td>
                            <td>
                              <div class="cell-desc" title="{{ $event->event_description }}">
                                {{ $event->event_description ?? 'No description' }}
                              </div>
                            </td>
                            <td>
                              <span class="date-pill">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $event->start_date->format('M j') }} - {{ $event->end_date->format('M j') }}
                              </span>
                            </td>
                            <td>
                              @if($event->event_status === 'upcoming')
                                <span class="status-badge status-upcoming">Upcoming</span>
                              @elseif($event->event_status === 'ongoing')
                                <span class="status-badge status-ongoing">Ongoing</span>
                              @elseif(strtolower($event->event_status) === 'completed')
                                <span class="status-badge status-completed">Completed</span>
                              @else
                                <span class="status-badge" style="background: rgba(255,255,255,.04); color: var(--text-muted); border: 1px solid var(--border);">Inactive</span>
                              @endif
                            </td>
                            <td>
                              <span class="count-pill categories">
                                {{ \App\Models\Category::where('event_id', $event->id)->count() ?? 0 }} categories
                              </span>
                            </td>
                            <td>
                              <span class="count-pill" style="color: var(--orange); background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.2);">
{{ \App\Models\Submission::where('event_id', $event->id)->where('status', 'reviewed')->count() }} participants
                              </span>
                            </td>
                            <td>
                              <div class="actions">
                                <a href="{{ route('admin.event.show', $event) }}" class="btn-icon btn-view">
                                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                  View
                                </a>
                                <a href="{{ route('judge.event.edit', $event) }}" class="btn-icon btn-edit">
                                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                  Edit
                                </a>
                                <form action="{{ route('judge.event.destroy', $event) }}" method="POST" style="display:inline;">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Are you sure you want to delete this event?')">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                          @empty
                          <tr>
                            <td colspan="7">
                              <div class="empty">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <p>No assigned events found. Contact admin for assignments.</p>
                              </div>
                            </td>
                          </tr>
                          @endforelse

                        </tbody>
                      </table>
                    </div>

                    <!-- Footer / pagination -->
                    <div class="card-footer">
                      <span>Showing <strong style="color:var(--text-secondary)">{{ $events->count() }}</strong> of <strong style="color:var(--text-secondary)">{{ isset($events->total) ? $events->total() : $events->count() }}</strong> events</span>
                      <div>
                        @if(method_exists($events, 'hasPages') && $events->hasPages())
                          {{ $events->links() }}
                        @else
                          <div class="pagination" style="display:flex;gap:4px;">
                            <span style="padding:5px 11px;border-radius:6px;border:1px solid var(--border);color:var(--text-muted);">‹</span>
                            <span style="padding:5px 11px;border-radius:6px;background:var(--accent-dim);border:1px solid rgba(79,124,255,.35);color:var(--accent);">1</span>
                            <span style="padding:5px 11px;border-radius:6px;border:1px solid var(--border);color:var(--text-muted);">›</span>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>

                </div>
            </div>
        </div>
    </div>
@endsection
