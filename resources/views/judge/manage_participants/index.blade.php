@extends('layouts.judge')


@section( 'content')
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
    --yellow: #f59e0b;
    --yellow-dim: rgba(245,158,11,0.12);
    --red: #ff5c6c;
    --red-dim: rgba(255,92,108,0.1);
    --text-primary: #e8eaf2;
    --text-secondary: #8b90a4;
    --text-muted: #4e5368;
    --font: 'Sora', sans-serif;
    --mono: 'JetBrains Mono', monospace;
  }

  .page { max-width: 1200px; margin: 0 auto; padding: 1.5rem; }

  .topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 2rem;
    gap: 1rem;
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
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -.4px;
    margin: 0;
  }

  .topbar-left p {
    font-size: 14px;
    color: var(--text-secondary);
    margin-top: 4px;
  }

  .topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }

  .count-badge {
    font-family: var(--mono);
    font-size: 12px;
    color: var(--text-muted);
    background: var(--surface-2);
    border: 1px solid var(--border);
    padding: 8px 16px;
    border-radius: 10px;
    white-space: nowrap;
  }

  .search-container {
    position: relative;
    flex: 1;
    min-width: 250px;
    max-width: 350px;
  }

  .search-container input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 14px;
    transition: all .2s;
  }

  .search-container input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
  }

  .search-container::before {
    content: '🔍';
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 16px;
  }

  .event-filter {
    min-width: 200px;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--accent);
    color: #fff;
    font-family: var(--font);
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 2px 8px var(--accent-glow);
    white-space: nowrap;
  }

  .btn-primary:hover {
    background: #3f6cef;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px var(--accent-glow);
  }

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
  }

  .table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  table { 
    width: 100%; 
    border-collapse: collapse; 
    min-width: 800px;
  }

  thead tr {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }

  thead th {
    padding: 16px 24px;
    text-align: left;
    font-family: var(--mono);
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
  }

  thead th:last-child { text-align: right; }

  tbody tr {
    border-bottom: 1px solid var(--border);
    transition: all .15s;
  }

  tbody tr:hover { 
    background: var(--border-hover); 
  }

  tbody tr:last-child { border-bottom: none; }

  td {
    padding: 20px 24px;
    font-size: 14px;
    color: var(--text-secondary);
    vertical-align: middle;
  }

  .cell-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .dot {
    width: 8px; 
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 8px currentColor;
  }

  .participant-dot { background: var(--yellow); }

  .cell-email {
    font-family: var(--mono);
    color: var(--text-primary);
    font-size: 14px;
  }

  .role-participant { 
    background: var(--yellow-dim); 
    color: var(--yellow); 
    border: 1px solid rgba(245,158,11,.22); 
  }

  .status-badge, .score-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
  }

  .status-reviewed { 
    background: var(--green-dim); 
    color: var(--green); 
    border: 1px solid rgba(34,200,135,.2); 
  }

  .status-pending { 
    background: var(--accent-dim); 
    color: var(--accent); 
    border: 1px solid rgba(79,124,255,.2); 
  }

  .score-badge {
    background: var(--surface-2);
    color: var(--text-primary);
    border: 1px solid var(--border);
  }

  .score-progress {
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
  }

  .score-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--green), var(--yellow), var(--red));
    border-radius: 3px;
    transition: width .3s ease;
  }

  .actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .btn-score {
    padding: 8px 20px;
    background: var(--green-dim);
    color: var(--green);
    border: 1px solid rgba(34,200,135,.3);
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
  }

  .btn-score:hover {
    background: var(--green);
    color: #fff;
    box-shadow: 0 4px 12px var(--green-dim);
    transform: translateY(-1px);
  }

  .empty {
    padding: 4rem 2rem;
    text-align: center;
    color: var(--text-muted);
  }

  .empty svg {
    width: 48px; 
    height: 48px;
    margin: 0 auto 1.5rem;
    opacity: .4;
  }

  .empty h3 { 
    font-size: 1.25rem; 
    font-weight: 600; 
    color: var(--text-secondary);
    margin-bottom: 1rem;
  }

  .card-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface-2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    color: var(--text-muted);
  }

  @media (max-width: 768px) {
    .topbar { flex-direction: column; align-items: stretch; gap: 1.5rem; }
    .topbar-right { justify-content: center; flex-wrap: wrap; }
    .search-container, .event-filter { min-width: 100%; }
  }
</style>

<div class="page">
  <!-- Top Bar -->
  
    <div class="topbar-right">
      <span class="count-badge">{{ $submissions->total() }} applications</span>
      
      <!-- Search -->
      <div class="search-container">
        <form method="GET" action="{{ route('judge.manage_participants.index') }}">
          @csrf
          <input 
            type="text" 
            name="search" 
            placeholder="Search by name, email, title or description..."
            value="{{ request('search') }}"
          >
        </form>
      </div>

      <!-- Event Filter -->
      <div class="event-filter">
        <form method="GET" action="{{ route('judge.manage_participants.index') }}" style="display: flex; gap: 8px;">
          @csrf
          <select name="event_id" onchange="this.form.submit()" style="flex: 1; padding: 12px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); color: var(--text-primary);">
            <option value="">All Events</option>
            @foreach($events ?? [] as $event)
              <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                {{ $event->event_name }}
              </option>
            @endforeach
          </select>
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
        </form>
      </div>

      <a href="{{ route('judge.review-scores') }}" class="btn-primary">
        📊 Review All Scores
      </a>
    </div>
  </div>
<!-- Table card -->
  <div class="card" style="width: 100%;">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Participant</th>
            <th>Event</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($submissions as $index => $submission)
          <tr style="animation-delay: {{ $index * 0.05 }}s">
            <td>
              <div class="cell-name">
                <span class="dot participant-dot"></span>
                {{ $submission->participant->name ?? 'Unknown' }}
              </div>
              <div class="cell-email">{{ $submission->participant->email ?? '' }}</div>
            </td>
            <td>
              <span class="event-pill">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $submission->event->event_name ?? 'N/A' }}
              </span>
            </td>
            <td><div class="cell-title" title="{{ $submission->title }}">{{ $submission->title }}</div></td>
            <td><div class="cell-desc" title="{{ $submission->description }}">{{ $submission->description ? substr($submission->description, 0, 60) . (strlen($submission->description) > 60 ? '...' : '') : 'No description' }}</div></td>
            <td>
              @php
                $status = $submission->status;
                $statusClass = match($status) {
                  'reviewed', 'approved' => 'status-reviewed',
                  'pending' => 'status-pending',
                  default => 'status-pending'
                };
                $statusIcon = match($status) {
                  'reviewed', 'approved' => '🟢',
                  'pending' => '🟡',
                  default => '🔄'
                };
                $statusLabel = match($status) {
                  'reviewed', 'approved' => 'Approved',
                  'pending' => 'Pending',
                  default => ucfirst($status)
                };
              @endphp
              <span class="status-badge {{ $statusClass }}">
                {{ $statusIcon }} {{ $statusLabel }}
              </span>
            </td>
            <td><span class="date">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}</span></td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.participants.edit', $submission) }}" class="btn-score" style="background: var(--accent-dim); color: var(--accent); border-color: rgba(79,124,255,.3);" title="Edit application">
                  ✏️ Edit
                </a>
                <a href="#" class="btn-score" style="background: var(--red-dim); color: var(--red); border-color: rgba(255,92,108,.3);" title="Delete application" onclick="event.preventDefault(); if(confirm('Delete this application?')) { window.location.href = '{{ route('admin.participants.destroy', $submission) }}'; } ">
                  🗑️ Delete
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 0 2 11-4 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3>No Applications Available</h3>
                <p>No approved participant applications found. Check event submissions or wait for applications to be approved.</p>
                <a href="{{ route('judge.dashboard') }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">
                  ← Back to Dashboard
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination footer -->
    @if($submissions instanceof \Illuminate\Pagination\LengthAwarePaginator && $submissions->hasPages())
    <div class="card-footer">
      <span>Showing <strong style="color:var(--text-secondary)">{{ $submissions->count() }}</strong> of <strong style="color:var(--text-secondary)">{{ $submissions->total() }}</strong> applications</span>
      <div style="display:flex;gap:4px;">
        {{ $submissions->onEachSide(1)->links() }}
      </div>
    </div>
    @endif

  </div>
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: var(--green-dim); color: var(--green); padding: 16px 24px; border-radius: 12px; border: 1px solid rgba(34,200,135,.3); z-index: 10000; max-width: 400px; box-shadow: 0 8px 32px rgba(34,200,135,.2);">
  {{ session('success') }}
</div>
@endif
@endsection
