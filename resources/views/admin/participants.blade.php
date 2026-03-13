<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Participants Management</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
    --red: #ff5c6c;
    --red-dim: rgba(255,92,108,0.1);
    --orange: #f59e0b;
    --orange-dim: rgba(245,158,11,0.12);
    --text-primary: #e8eaf2;
    --text-secondary: #8b90a4;
    --text-muted: #4e5368;
    --font: 'Sora', sans-serif;
    --mono: 'JetBrains Mono', monospace;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text-primary);
    min-height: 100vh;
    padding: 40px 24px;
  }

  /* ── Page wrapper ── */
  .page { max-width: 1100px; margin: 0 auto; }

  /* ── Top bar ── */
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

  /* ── Filter tabs ── */
  .filter-tabs {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 4px;
    margin-bottom: 24px;
    display: flex;
    gap: 4px;
    animation: fadeUp .5s ease both;
  }

  .filter-tab {
    flex: 1;
    padding: 8px 16px;
    font-family: var(--font);
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 12px;
    text-align: center;
    transition: all .2s;
    white-space: nowrap;
  }

  .filter-tab--pending { color: var(--orange); background: var(--orange-dim); border: 1px solid rgba(245,158,11,.22); }
  .filter-tab--pending:hover { background: rgba(245,158,11,.22); }
  .filter-tab--reviewed, .filter-tab--pending.active { color: var(--green); background: var(--green-dim); border: 1px solid rgba(34,200,135,.22); }
  .filter-tab--reviewed:hover, .filter-tab--pending.active:hover { background: rgba(34,200,135,.22); }
  .filter-tab--draft { color: var(--red); background: var(--red-dim); border: 1px solid rgba(255,92,108,.22); }
  .filter-tab--draft:hover { background: rgba(255,92,108,.22); }
  .filter-tab--all { color: var(--accent); background: var(--accent-dim); border: 1px solid rgba(79,124,255,.22); }
  .filter-tab--all:hover { background: rgba(79,124,255,.22); }

  /* ── Success message ── */
  .success-msg {
    background: var(--green-dim);
    border: 1px solid rgba(34,200,135,.22);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: fadeUp .4s ease both;
    color: var(--green);
  }

  .success-msg svg { width: 18px; height: 18px; opacity: .9; flex-shrink: 0; }

  /* ── Card shell ── */
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }

  /* ── Table ── */
  table { width: 100%; border-collapse: collapse; }

  thead tr {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }

  thead th {
    padding: 13px 20px;
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

  tbody tr:nth-child(1) { animation-delay: .05s; }
  tbody tr:nth-child(2) { animation-delay: .1s; }
  tbody tr:nth-child(3) { animation-delay: .15s; }
  tbody tr:nth-child(4) { animation-delay: .2s; }
  tbody tr:nth-child(5) { animation-delay: .25s; }
  tbody tr:nth-child(6) { animation-delay: .3s; }
  tbody tr:nth-child(7) { animation-delay: .35s; }
  tbody tr:nth-child(8) { animation-delay: .4s; }

  td {
    padding: 16px 20px;
    font-size: 13.5px;
    color: var(--text-secondary);
    vertical-align: middle;
  }

  /* Participant avatar */
  .avatar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--mono);
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--accent), #7aa4ff);
  }

  .participant-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .participant-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
  }

  .participant-email {
    font-size: 12px;
    color: var(--text-muted);
  }

  /* Description */
  .cell-desc {
    max-width: 280px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
  }

  .cell-title {
    font-weight: 600;
    color: var(--text-primary);
  }

  /* Event pill */
  .event-pill {
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

  .event-pill svg { width: 11px; height: 11px; opacity: .8; }

  /* Date */
  .date {
    font-family: var(--mono);
    font-size: 12.5px;
    color: var(--text-secondary);
  }

  /* Status badges */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 600;
    letter-spacing: .02em;
  }

  .badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .badge-pending {
    background: var(--orange-dim);
    color: var(--orange);
    border: 1px solid rgba(245,158,11,.2);
  }

  .badge-pending::before { 
    background: var(--orange); 
    box-shadow: 0 0 5px rgba(245,158,11,.5); 
  }

  .badge-approved {
    background: var(--green-dim);
    color: var(--green);
    border: 1px solid rgba(34,200,135,.2);
  }

  .badge-approved::before { 
    background: var(--green); 
    box-shadow: 0 0 5px var(--green); 
  }

  .badge-declined {
    background: var(--red-dim);
    color: var(--red);
    border: 1px solid rgba(255,92,108,.2);
  }

  .badge-declined::before { 
    background: var(--red); 
    box-shadow: 0 0 5px rgba(255,92,108,.5); 
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
  }

  .btn-approve {
    color: var(--green);
    background: var(--green-dim);
    border-color: rgba(34,200,135,.2);
  }

  .btn-approve:hover {
    background: rgba(34,200,135,.22);
    border-color: rgba(34,200,135,.4);
  }

  .btn-decline {
    color: var(--red);
    background: var(--red-dim);
    border-color: rgba(255,92,108,.18);
  }

  .btn-decline:hover {
    background: rgba(255,92,108,.18);
    border-color: rgba(255,92,108,.35);
  }

  .btn-edit {
    color: var(--accent);
    background: var(--accent-dim);
    border-color: rgba(79,124,255,.22);
  }

  .btn-edit:hover {
    background: rgba(79,124,255,.22);
    border-color: rgba(79,124,255,.4);
  }

  .btn-delete {
    color: var(--red);
    background: var(--red-dim);
    border-color: rgba(255,92,108,.18);
  }

  .btn-delete:hover {
    background: rgba(255,92,108,.18);
    border-color: rgba(255,92,108,.35);
  }

  .btn-icon svg { width: 12px; height: 12px; }

  .no-actions {
    color: var(--text-muted);
    font-size: 12px;
  }

  /* Empty state */
  .empty {
    padding: 64px 24px;
    text-align: center;
    color: var(--text-muted);
  }

  .empty svg {
    width: 40px; height: 40px;
    margin: 0 auto 16px;
    opacity: .3;
  }

  .empty h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
  }

  .empty p { 
    font-size: 14px;
    margin-bottom: 16px;
  }

  .empty a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
  }

  /* Pagination */
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
</head>
<body>

<div class="page">

  <!-- Top bar -->
  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Admin Panel</div>
      <h1>Participants Management</h1>
      <p>Review and manage participant applications</p>
    </div>
    <div class="topbar-right">
      <span class="count-badge">{{ $submissions->count() }} applications</span>
    </div>
  </div>

  <!-- Success message -->
  @if(session('success'))
  <div class="success-msg">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
  </div>
  @endif

  <!-- Filter tabs -->
  <div class="filter-tabs">
    <a href="{{ route('admin.participants.index', ['status' => 'pending']) }}" 
       class="filter-tab {{ $status === 'pending' ? 'active' : '' }} filter-tab--pending">Pending</a>
    <a href="{{ route('admin.participants.index', ['status' => 'reviewed']) }}" 
       class="filter-tab {{ $status === 'reviewed' ? 'active' : '' }} filter-tab--reviewed">Approved</a>
    <a href="{{ route('admin.participants.index', ['status' => 'draft']) }}" 
       class="filter-tab {{ $status === 'draft' ? 'active' : '' }} filter-tab--draft">Declined</a>
    <a href="{{ route('admin.participants.index', ['status' => 'all']) }}" 
       class="filter-tab {{ $status === 'all' ? 'active' : '' }} filter-tab--all">All</a>
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
              <div style="display: flex; align-items: center; gap: 12px;">
                <div class="avatar">{{ strtoupper(substr($submission->participant->name ?? 'U', 0, 2)) }}</div>
                <div class="participant-info">
                  <div class="participant-name">{{ $submission->participant->name ?? 'Unknown' }}</div>
                  <div class="participant-email">{{ $submission->participant->email ?? '' }}</div>
                </div>
              </div>
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
              @if($submission->status === 'pending')
                <span class="badge badge-pending">{{ ucfirst($submission->status) }}</span>
              @elseif($submission->status === 'reviewed')
                <span class="badge badge-approved">Approved</span>
              @elseif($submission->status === 'draft')
                <span class="badge badge-declined">Declined</span>
              @else
                <span class="badge badge-pending">{{ ucfirst($submission->status) }}</span>
              @endif
            </td>
            <td><span class="date">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}</span></td>
            <td>
              <div class="actions">
                @if($submission->status === 'pending')
                  <form method="POST" action="{{ route('admin.participants.approve', $submission) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-icon btn-approve" title="Approve">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                      </svg>
                      Approve
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.participants.decline', $submission) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-icon btn-decline" title="Decline" onclick="return confirm('Decline this application?')">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                      Decline
                    </button>
                  </form>
                @endif
                
                {{-- Edit and Delete always available --}}
                <a href="{{ route('admin.participants.edit', $submission) }}" class="btn-icon" style="color: var(--accent); background: var(--accent-dim); border-color: rgba(79,124,255,.22);" title="Edit">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Edit
                </a>
                
                <form method="POST" action="{{ route('admin.participants.destroy', $submission) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this submission? This action cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-icon" style="color: var(--red); background: var(--red-dim); border-color: rgba(255,92,108,.18);" title="Delete">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
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
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 0 2 11-4 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3>No applications found</h3>
                <p>There are no participant applications matching this filter.</p>
                <a href="{{ route('admin.participants.index') }}">Reset filters</a>
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

</body>
</html>
</x-admin-layout>
