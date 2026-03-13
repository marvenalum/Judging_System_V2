<x-judge-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categories') }}
    </h2>
</x-slot>

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
    --text-primary: #e8eaf2;
    --text-secondary: #8b90a4;
    --text-muted: #4e5368;
    --font: 'Sora', sans-serif;
    --mono: 'JetBrains Mono', monospace;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  .page { max-width: 1100px; margin: 0 auto; padding: 40px 24px; background: var(--bg); color: var(--text-primary); min-height: 100vh; }

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

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }

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

  td {
    padding: 16px 20px;
    font-size: 13.5px;
    color: var(--text-secondary);
    vertical-align: middle;
  }

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
    max-width: 240px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: var(--text-secondary);
  }

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
    min-width: 36px;
  }

  .weight-bar {
    flex: 1;
    height: 4px;
    background: var(--border);
    border-radius: 99px;
    overflow: hidden;
    max-width: 80px;
  }

  .weight-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), #7aa4ff);
    border-radius: 99px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
  }

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
    background: var(--accent-dim);
    color: var(--accent);
    border-color: rgba(79,124,255,.2);
  }

  .btn-icon:hover {
    background: rgba(79,124,255,.2);
    border-color: rgba(79,124,255,.4);
  }

  .btn-icon svg { width: 12px; height: 12px; }

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

  /* Session Alert */
  .session-alert {
    background: var(--green-dim);
    border: 1px solid rgba(34,200,135,.3);
    color: var(--green);
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 13px;
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

  @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
</style>

<div class="page">
  @if(session('success'))
    <div class="session-alert">
      {{ session('success') }}
    </div>
  @endif

  <!-- Top bar -->
  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Judge Panel</div>
      <h1>Categories</h1>
      <p>View all available categories for judging</p>
    </div>
    <div class="topbar-right">
      <span class="count-badge">{{ $categories->count() }} categories</span>
      <a href="#" class="btn-primary" onclick="location.reload()">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Refresh
      </a>
    </div>
  </div>

  <!-- Table card -->
  <div class="card" style="width: 100%;">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Event</th>
            <th>Weight</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $index => $category)
          <tr style="animation-delay: {{ $index * 0.05 }}s">
            <td>
              <div class="cell-name">
                <span class="dot" style="background:{{ $category->status === 'active' ? '#22c887' : '#4e5368' }};box-shadow:0 0 6px {{ $category->status === 'active' ? '#22c88788' : '#4e536888' }};"></span>
                {{ $category->name }}
              </div>
            </td>
            <td><div class="cell-desc" title="{{ $category->description }}">{{ $category->description ?? 'No description' }}</div></td>
            <td>
              <span class="event-pill">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $category->event->event_name ?? 'N/A' }}
              </span>
            </td>
            <td>
              <div class="weight-wrap">
                <span class="weight-num">{{ $category->percentage_weight ?? $category->weight ?? 0 }}%</span>
                <div class="weight-bar"><div class="weight-fill" style="width:{{ $category->percentage_weight ?? $category->weight ?? 0 }}%"></div></div>
              </div>
            </td>
            <td>
@if($category->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('judge.category.show', $category) }}" class="btn-icon">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  View
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l.75 1.5L12 14l1.25 2.5L15 13v2a1 1 0 001 1h1a1 1 0 001-1v-3"/>
                </svg>
                <p>No categories available. Contact administrator.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <!-- Footer / pagination -->
    <div class="card-footer">
      <span>Showing <strong style="color:var(--text-secondary)">{{ $categories->count() }}</strong> of <strong style="color:var(--text-secondary)">{{ $categories->count() }}</strong> categories</span>
      <div class="pagination">
        <button class="page-btn">‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">›</button>
      </div>
    </div>
  </div>
</div>
</x-judge-layout>