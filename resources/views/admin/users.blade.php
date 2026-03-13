<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Users Management</title>
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

  .page { max-width: 1100px; margin: 0 auto; }

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
  tbody tr:nth-child(6) { animation-delay: .3s; }
  tbody tr:nth-child(7) { animation-delay: .35s; }
  tbody tr:nth-child(8) { animation-delay: .4s; }

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

  .cell-email {
    font-family: var(--mono);
    color: var(--text-primary);
    font-size: 13px;
  }

  .role-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 6px;
    white-space: nowrap;
  }

  .role-admin { background: var(--accent-dim); color: var(--accent); border: 1px solid rgba(79,124,255,.22); }
  .role-judge { background: var(--green-dim); color: var(--green); border: 1px solid rgba(34,200,135,.22); }
  .role-participant { background: rgba(255,165,0,.12); color: #f59e0b; border: 1px solid rgba(255,165,0,.22); }

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

  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Admin Panel</div>
      <h1>Users Management</h1>
      <p>Manage system users and their roles</p>
    </div>
    <div class="topbar-right">
      <span class="count-badge">{{ count($users) }} users</span>
      <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Add New User
      </a>
    </div>
  </div>

  <div class="card" style="width: 100%;">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

          @forelse($users as $index => $user)
          <tr style="animation-delay: {{ $index * 0.05 }}s">
            <td>
              <div class="cell-name">
                @php
                  $roleColor = $user->role === 'admin' ? '#4f7cff' : ($user->role === 'judge' ? '#22c887' : '#f59e0b');
                  $roleGlow = $user->role === 'admin' ? '#4f7cff88' : ($user->role === 'judge' ? '#22c88788' : '#f59e0b88');
                @endphp
                <span class="dot" style="background:{{ $roleColor }};box-shadow:0 0 6px {{ $roleGlow }};"></span>
                {{ $user->name }}
              </div>
            </td>
            <td><span class="cell-email">{{ $user->email }}</span></td>
            <td>
              @if($user->role === 'admin')
                <span class="role-pill role-admin">Admin</span>
              @elseif($user->role === 'judge')
                <span class="role-pill role-judge">Judge</span>
              @else
                <span class="role-pill role-participant">Participant</span>
              @endif
            </td>
            <td>
              @if(isset($user->status) && $user->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon btn-edit">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  Edit
                </a>
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p>No users found. <a href="{{ route('admin.users.create') }}" style="color: var(--accent); text-decoration: none;">Create one</a></p>
              </div>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <span>Showing <strong style="color:var(--text-secondary)">{{ count($users) }}</strong> users</span>
      <div class="pagination">
        <button class="page-btn">‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">›</button>
      </div>
    </div>
  </div>

</div>

</body>
</html>
</x-admin-layout>