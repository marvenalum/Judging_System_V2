<x-admin-layout>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Criteria Management</title>
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
    --purple: #a78bfa;
    --pink: #fb7185;
    --cyan: #38bdf8;
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

  /* Page wrapper */
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

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--accent);
    color: #fff;
    font-family: var(--font);
    font-size: 13.5px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, box-shadow .2s, transform .15s;
    box-shadow: 0 0 0 0 var(--accent-glow);
  }

  .btn-primary:hover {
    background: #3f6cef;
    box-shadow: 0 4px 20px var(--accent-glow);
    transform: translateY(-1px);
  }

  .btn-primary svg { width: 15px; height: 15px; flex-shrink: 0; }

  /* Card shell */
  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }

  /* Table */
  table { width: 100%; border-collapse: collapse; }

  thead tr {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }

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

  tbody tr:nth-child(1) { animation-delay: .05s; }
  tbody tr:nth-child(2) { animation-delay: .1s; }
  tbody tr:nth-child(3) { animation-delay: .15s; }
  tbody tr:nth-child(4) { animation-delay: .2s; }
  tbody tr:nth-child(5) { animation-delay: .25s; }
  tbody tr:nth-child(6) { animation-delay: .3s; }
  tbody tr:nth-child(7) { animation-delay: .35s; }
  tbody tr:nth-child(8) { animation-delay: .4s; }

  td {
    padding: 14px 16px;
    font-size: 13.5px;
    color: var(--text-secondary);
    vertical-align: middle;
  }

  /* Name cell */
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

  /* Description */
  .cell-desc {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
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

  /* Category pill */
  .category-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 6px;
    white-space: nowrap;
  }

  .category-pill.style-1 {
    color: var(--green);
    background: var(--green-dim);
    border: 1px solid rgba(34,200,135,.2);
  }

  .category-pill.style-2 {
    color: var(--orange);
    background: var(--orange-dim);
    border: 1px solid rgba(245,158,11,.2);
  }

  .category-pill.style-3 {
    color: var(--purple);
    background: rgba(167,139,250,.12);
    border: 1px solid rgba(167,139,250,.2);
  }

  .category-pill.style-4 {
    color: var(--pink);
    background: var(--red-dim);
    border: 1px solid rgba(255,92,108,.2);
  }

  .category-pill.style-5 {
    color: var(--cyan);
    background: rgba(56,189,248,.12);
    border: 1px solid rgba(56,189,248,.2);
  }

  /* Weight */
  .weight-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .weight-num {
    font-family: var(--mono);
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
    min-width: 45px;
  }

  .weight-bar {
    flex: 1;
    height: 4px;
    background: var(--border);
    border-radius: 99px;
    overflow: hidden;
    max-width: 70px;
  }

  .weight-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), #7aa4ff);
    border-radius: 99px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
  }

  .max-score {
    font-family: var(--mono);
    font-size: 13px;
    color: var(--text-primary);
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

  .badge-active {
    background: var(--green-dim);
    color: var(--green);
    border: 1px solid rgba(34,200,135,.2);
  }

  .badge-active::before { background: var(--green); box-shadow: 0 0 5px var(--green); }

  .badge-inactive {
    background: rgba(255,255,255,.04);
    color: var(--text-muted);
    border: 1px solid var(--border);
  }

  .badge-inactive::before { background: var(--text-muted); }

  /* Score count */
  .score-count {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .score-num {
    font-family: var(--mono);
    font-size: 13px;
    font-weight: 500;
    color: var(--orange);
  }

  .score-label {
    font-size: 11px;
    color: var(--text-muted);
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

  .btn-view {
    color: var(--cyan);
    background: rgba(56,189,248,.12);
    border-color: rgba(56,189,248,.2);
  }

  .btn-view:hover {
    background: rgba(56,189,248,.2);
    border-color: rgba(56,189,248,.4);
  }

  .btn-edit {
    color: var(--accent);
    background: var(--accent-dim);
    border-color: rgba(79,124,255,.2);
  }

  .btn-edit:hover {
    background: rgba(79,124,255,.2);
    border-color: rgba(79,124,255,.4);
  }

  .btn-toggle {
    color: var(--orange);
    background: var(--orange-dim);
    border-color: rgba(245,158,11,.2);
  }

  .btn-toggle:hover {
    background: rgba(245,158,11,.2);
    border-color: rgba(245,158,11,.4);
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

  .empty p { font-size: 14px; }

  /* Footer / pagination strip */
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

  .pagination {
    display: flex;
    gap: 4px;
  }

  .page-btn {
    padding: 5px 11px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text-secondary);
    font-family: var(--mono);
    font-size: 12px;
    cursor: pointer;
    transition: all .15s;
  }

  .page-btn:hover, .page-btn.active {
    background: var(--accent-dim);
    border-color: rgba(79,124,255,.35);
    color: var(--accent);
  }

  /* Alert messages */
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

  .alert-error {
    background: var(--red-dim);
    color: var(--red);
    border: 1px solid rgba(255,92,108,.2);
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
      <h1>Criteria Management</h1>
      <p>Manage and organize your scoring criteria</p>
    </div>
    <div class="topbar-right">
      <span class="count-badge">{{ $criteria->count() }} criteria</span>
      <a href="{{ route('admin.criteria.create') }}" class="btn-primary">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
        </svg>
        Add New Criteria
      </a>
    </div>
  </div>

  <!-- Alert Messages -->
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-error">
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
            <th>Event</th>
            <th>Category</th>
            <th>Max Score</th>
            <th>Weight</th>
            <th>Scores</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

          @forelse($criteria as $index => $criterion)
          <tr style="animation-delay: {{ $index * 0.05 }}s">
            <td>
              <div class="cell-name">
                <span class="dot" style="background:{{ $criterion->status === 'active' ? '#22c887' : '#4e5368' }};box-shadow:0 0 6px {{ $criterion->status === 'active' ? '#22c88788' : '#4e536888' }};"></span>
                {{ $criterion->name }}
              </div>
            </td>
            <td>
              <div class="cell-desc" title="{{ $criterion->description }}">
                {{ $criterion->description ?? 'No description' }}
              </div>
            </td>
            <td>
              <span class="event-pill">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $criterion->event->event_name ?? 'N/A' }}
              </span>
            </td>
            <td>
              @php
                $categoryIndex = ($criterion->category_id ?? 1) % 5;
                $categoryClass = 'style-' . ($categoryIndex + 1);
              @endphp
              <span class="category-pill {{ $categoryClass }}">
                {{ $criterion->category->name ?? 'N/A' }}
              </span>
            </td>
            <td>
              <span class="max-score">{{ $criterion->max_score ?? 100 }}</span>
            </td>
            <td>
              <div class="weight-wrap">
                <span class="weight-num">{{ $criterion->percentage_weight ?? $criterion->weight ?? 0 }}%</span>
                <div class="weight-bar"><div class="weight-fill" style="width:{{ $criterion->percentage_weight ?? $criterion->weight ?? 0 }}%"></div></div>
              </div>
            </td>
            <td>
              <div class="score-count">
                <span class="score-num">{{ $scoreCounts[$criterion->id] ?? 0 }}</span>
                <span class="score-label">score{{ ($scoreCounts[$criterion->id] ?? 0) != 1 ? 's' : '' }}</span>
              </div>
            </td>
            <td>
              @if($criterion->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.criteria.show', $criterion) }}" class="btn-icon btn-view">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  View
                </a>
                <a href="{{ route('admin.criteria.edit', $criterion) }}" class="btn-icon btn-edit">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  Edit
                </a>
                <form action="{{ route('admin.criteria.toggleStatus', $criterion) }}" method="POST" style="display:inline;">
                  @csrf
                  @if($criterion->status === 'active')
                    <button type="submit" class="btn-icon btn-toggle" onclick="return confirm('Are you sure you want to deactivate this criterion?')">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      Deactivate
                    </button>
                  @else
                    <button type="submit" class="btn-icon btn-toggle" onclick="return confirm('Are you sure you want to activate this criterion?')">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      Activate
                    </button>
                  @endif
                </form>
                @if(!isset($scoreCounts[$criterion->id]) || $scoreCounts[$criterion->id] == 0)
                  <form action="{{ route('admin.criteria.destroy', $criterion) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Are you sure you want to delete this criterion?')">
                      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      Delete
                    </button>
                  </form>
                @else
                  <span class="btn-icon" style="color: var(--text-muted); cursor: not-allowed; opacity: 0.5;" title="Cannot delete - scores exist">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                  </span>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p>No criteria found. <a href="{{ route('admin.criteria.create') }}" style="color: var(--accent); text-decoration: none;">Create one</a></p>
              </div>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>

    <!-- Footer / pagination -->
    <div class="card-footer">
      <span>Showing <strong style="color:var(--text-secondary)">{{ $criteria->count() }}</strong> of <strong style="color:var(--text-secondary)">{{ $criteria->count() }}</strong> criteria</span>
      <div class="pagination">
        <button class="page-btn">‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">›</button>
      </div>
    </div>
  </div>

</div>

</body>
</html>
</x-admin-layout>

