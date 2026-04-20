<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Assignments - {{ $judge->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&amp;family=JetBrains+Mono:wght@400;500&amp;display=swap" rel="stylesheet">
<!-- Same styles as participant-assignments.blade.php -->
<style>
  :root {
    --bg: #0a0c12;
    --surface: #11141e;
    --surface-2: #171c28;
    --border: #232838;
    --border-hover: #2f3548;
    --accent: #5d8bff;
    --accent-dim: rgba(93, 139, 255, 0.12);
    --accent-glow: rgba(93, 139, 255, 0.25);
    --green: #2ad18b;
    --green-dim: rgba(42, 209, 139, 0.12);
    --red: #ff6b7c;
    --red-dim: rgba(255, 107, 124, 0.1);
    --text-primary: #edf2fa;
    --text-secondary: #9aa3bf;
    --text-muted: #555e7a;
    --font: 'Sora', sans-serif;
    --mono: 'JetBrains Mono', monospace;
    --transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: radial-gradient(circle at 10% 20%, #0c0f18, #080a10);
    font-family: var(--font);
    color: var(--text-primary);
    line-height: 1.5;
    padding: 2rem;
    min-height: 100vh;
  }

  /* custom scroll */
  ::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  ::-webkit-scrollbar-track {
    background: var(--surface);
    border-radius: 12px;
  }
  ::-webkit-scrollbar-thumb {
    background: #2e354a;
    border-radius: 12px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
  }

  /* main container animation */
  .page {
    max-width: 1400px;
    margin: 0 auto;
    animation: fadeSlideUp 0.55s ease-out;
  }

  @keyframes fadeSlideUp {
    0% {
      opacity: 0;
      transform: translateY(28px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ----- GLASS TOPBAR (premium feel) ----- */
  .topbar {
    background: rgba(17, 20, 30, 0.75);
    backdrop-filter: blur(16px);
    border-radius: 2rem;
    padding: 1.25rem 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(93, 139, 255, 0.2);
    box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.4);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
  }

  .topbar-left .eyebrow {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 700;
    color: var(--accent);
    background: var(--accent-dim);
    display: inline-block;
    padding: 0.2rem 0.9rem;
    border-radius: 40px;
    margin-bottom: 0.75rem;
    border: 0.5px solid rgba(93, 139, 255, 0.3);
  }

  .topbar-left h1 {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #ffffff, #b9c8ff);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    margin-bottom: 0.35rem;
  }

  .topbar-left p {
    color: var(--text-secondary);
    font-size: 0.9rem;
  }

  /* ----- CARD MODERN (frosted deep) ----- */
  .card {
    background: rgba(17, 20, 30, 0.65);
    backdrop-filter: blur(8px);
    border-radius: 1.75rem;
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    transition: var(--transition);
  }

  /* table styling */
  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead th {
    text-align: left;
    padding: 1.2rem 1.2rem 0.9rem 1.5rem;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border);
    background: rgba(23, 28, 40, 0.5);
  }

  tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid rgba(35, 40, 56, 0.5);
    animation: rowFade 0.3s ease backwards;
  }

  @keyframes rowFade {
    from { opacity: 0; transform: translateX(-6px);}
    to { opacity: 1; transform: translateX(0);}
  }

  tbody tr:hover {
    background: rgba(93, 139, 255, 0.05);
    border-bottom-color: rgba(93, 139, 255, 0.2);
  }

  td {
    padding: 1rem 1.2rem 1rem 1.5rem;
    vertical-align: middle;
  }

  /* cell content */
  .cell-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--text-primary);
  }

  /* status badges */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.25rem 0.9rem;
    border-radius: 60px;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .badge-active {
    background: var(--green-dim);
    color: var(--green);
    border: 0.5px solid rgba(42, 209, 139, 0.3);
  }
  .badge-active::before {
    content: "●";
    font-size: 0.7rem;
    color: var(--green);
  }
  .badge-inactive {
    background: var(--red-dim);
    color: var(--red);
    border: 0.5px solid rgba(255, 107, 124, 0.3);
  }
  .badge-inactive::before {
    content: "○";
    font-size: 0.7rem;
  }

  /* action buttons (modern minimal) */
  .actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .btn-icon {
    text-decoration: none;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.45rem 0.9rem;
    border-radius: 30px;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--surface-2);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    letter-spacing: 0.2px;
  }

  .btn-icon:hover {
    transform: translateY(-2px);
    background: var(--surface);
    border-color: var(--accent);
    color: var(--accent);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
  }

  .btn-delete:hover {
    border-color: var(--red);
    color: var(--red);
    background: var(--red-dim);
  }

  /* empty state */
  .empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    gap: 1rem;
    color: var(--text-muted);
  }
  .empty svg {
    width: 60px;
    height: 60px;
    opacity: 0.5;
    stroke: currentColor;
  }
  .empty p {
    font-size: 0.9rem;
  }

  /* pagination */
  .card-footer {
    padding: 1.2rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: rgba(0, 0, 0, 0.2);
    font-size: 0.85rem;
  }

  .pagination {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .pagination span, .pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.3rem 0.85rem;
    border-radius: 30px;
    background: var(--surface-2);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.8rem;
    transition: var(--transition);
    border: 1px solid var(--border);
  }
  .pagination a:hover {
    background: var(--accent-dim);
    border-color: var(--accent);
    color: var(--accent);
  }
  .pagination .active span {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
  }

  /* responsive */
  @media (max-width: 900px) {
    body {
      padding: 1rem;
    }
    td, th {
      padding: 0.8rem;
    }
    .actions {
      flex-direction: column;
      gap: 6px;
    }
  }
</style>
</head>
<body>

<div class="page">

  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Judge Management</div>
      <h1>{{ $judge->name }} - Event Assignments</h1>
      <p>Current event assignments for this judge</p>
    </div>
    <div class="topbar-right">
      <a href="{{ route('admin.judge.index') }}" class="btn-secondary">← Back to Judges</a>
      <a href="{{ route('admin.judges.assign-events', $judge) }}" class="btn-primary">
        Add Assignments
      </a>
    </div>
  </div>

  <div class="card">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Event</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assignments as $assignment)
          <tr>
            <td>
              <div class="cell-name">
                <span style="color: var(--accent); font-weight: 500;">{{ $assignment->event->event_name }}</span>
              </div>
            </td>
            <td>
              @if($assignment->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.judges.assignments.destroy', [$judge, $assignment]) }}" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Remove this assignment?')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p>No event assignments. <a href="{{ route('admin.judges.assign-events', $judge) }}" style="color: var(--accent);">Assign some</a></p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if(method_exists($assignments, 'links'))
    <div class="card-footer">
      <span>Showing {{ $assignments->count() }} of {{ $assignments->total() }} assignments</span>
      <div class="pagination">
        {{ $assignments->links() }}
      </div>
    </div>
    @endif
  </div>

</div>

</body>
</html>
</x-admin-layout>
