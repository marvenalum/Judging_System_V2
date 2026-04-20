<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Judges Management | Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  /* ----- ENHANCED DESIGN SYSTEM (inspired by modern admin dashboards) ----- */
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
    --orange: #ffb347;
    --orange-dim: rgba(255, 179, 71, 0.1);
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

  .topbar-right .count-badge {
    background: var(--surface-2);
    padding: 0.5rem 1.2rem;
    border-radius: 60px;
    font-weight: 600;
    font-size: 0.85rem;
    border: 1px solid var(--border);
    color: var(--accent);
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

  .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--green);
    box-shadow: 0 0 8px var(--green);
    display: inline-block;
  }

  .cell-email {
    font-family: var(--mono);
    font-size: 0.8rem;
    color: var(--text-secondary);
    background: var(--surface-2);
    padding: 0.2rem 0.6rem;
    border-radius: 30px;
    display: inline-block;
  }

  /* role pills */
  .role-pill {
    display: inline-block;
    padding: 0.25rem 0.9rem;
    border-radius: 40px;
    font-size: 0.75rem;
    font-weight: 600;
    background: var(--accent-dim);
    color: var(--accent);
    border: 0.5px solid rgba(93, 139, 255, 0.3);
    letter-spacing: 0.3px;
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

  .count-badge {
    background: var(--surface-2);
    padding: 0.25rem 0.7rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-secondary);
    border: 1px solid var(--border);
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

  .btn-edit:hover {
    border-color: var(--orange);
    color: var(--orange);
    background: var(--orange-dim);
  }

  .btn-assign {
    color: var(--green);
    background: var(--green-dim);
    border-color: rgba(42, 209, 139, 0.2);
  }
  .btn-assign:hover {
    background: rgba(42, 209, 139, 0.2);
    border-color: var(--green);
    color: #3be09a;
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

  /* card footer + pagination */
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

  /* pagination styling (assuming links use standard classes) */
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

  /* responsive design */
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
    .btn-icon {
      justify-content: center;
    }
    .card-footer {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  /* badge for events count style */
  .event-count-pill {
    background: #20273b;
    font-weight: 600;
  }
</style>
</head>
<body>

<div class="page">

  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">⚖️ Admin Panel</div>
      <h1>Judges Management</h1>
      <p>Manage judges, assign events and participants for scoring — full control panel</p>
    </div>
    <div class="topbar-right">
      <span class="count-badge">👥 {{ $judges->total() }} judges</span>
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
            <th>Event Assignments</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($judges as $index => $judge)
          <tr style="animation-delay: {{ $index * 0.04 }}s">
            <td>
              <div class="cell-name">
                <span class="dot" style="background: #2ad18b; box-shadow:0 0 8px #2ad18b88;"></span>
                {{ $judge->name }}
              </div>
            </td>
            <td><span class="cell-email">{{ $judge->email }}</span></td>
            <td><span class="role-pill role-judge">⚡ Judge</span></td>
            <td>
              @if($judge->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <span class="count-badge event-count-pill">{{ $judge->judgeAssignments()->count() }} events</span>
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.judges.assign-events', $judge) }}" class="btn-icon btn-edit">
                  📅 Events
                </a>
                <a href="{{ route('admin.judges.assignments', $judge) }}" class="btn-icon btn-edit">
                  🔗 Assignments
                </a>
                <a href="{{ route('admin.judges.assign-participants', $judge) }}" class="btn-icon btn-assign">
                  👥 Participants
                </a>
                <a href="{{ route('admin.judges.participant-assignments', $judge) }}" class="btn-icon btn-assign">
                  📋 Participant Assign
                </a>
                <a href="{{ route('admin.users.edit', $judge) }}" class="btn-icon btn-edit">
                  ✏️ Edit
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656 .126-1.283 .356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p>No judges found. Create a new judge to start.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <span>✨ Showing <strong style="color: var(--accent);">{{ $judges->count() }}</strong> of <strong style="color: var(--text-primary);">{{ $judges->total() }}</strong> judges</span>
      <div class="pagination">
        {{ $judges->links() }}
      </div>
    </div>
  </div>

</div>

<!-- optional micro-interaction: hover tooltip for action buttons (purely design enhancement) -->
<script>
  (function() {
    // adds subtle glow effect on status pills on hover? optional UI delight
    const badges = document.querySelectorAll('.badge-active, .badge-inactive');
    badges.forEach(b => {
      b.style.transition = 'all 0.2s';
      b.addEventListener('mouseenter', () => {
        b.style.filter = 'brightness(1.1) drop-shadow(0 0 3px currentColor)';
      });
      b.addEventListener('mouseleave', () => {
        b.style.filter = 'none';
      });
    });
    // add small console greeting to show dynamic design (no backend change)
    console.log('✨ Enhanced Judges Management UI — modern glassmorphic design');
  })();
</script>

</body>
</html>
</x-admin-layout>
