<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Participant Assignments - {{ $judge->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  /* ----- ENHANCED DESIGN SYSTEM (consistent with judges management) ----- */
  :root {
    --bg: #0a0c12;
    --surface: #11141e;
    --surface-2: #171c28;
    --surface-3: #1f2538;
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

  /* custom scrollbar */
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
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeSlideUp 0.5s ease-out;
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

  /* ----- GLASS TOPBAR (premium) ----- */
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
    gap: 1rem;
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
    font-size: 1.8rem;
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
    font-size: 0.85rem;
  }

  .topbar-right {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .btn-secondary {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border);
    padding: 0.6rem 1.2rem;
    border-radius: 60px;
    font-weight: 500;
    font-size: 0.85rem;
    font-family: var(--font);
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-secondary:hover {
    background: var(--surface-2);
    border-color: var(--accent);
    color: var(--accent);
    transform: translateY(-2px);
  }

  .btn-primary {
    background: linear-gradient(105deg, #2a3f6e, #1e2f54);
    border: none;
    padding: 0.6rem 1.4rem;
    border-radius: 60px;
    font-weight: 600;
    font-size: 0.85rem;
    font-family: var(--font);
    color: white;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  }

  .btn-primary:hover {
    background: linear-gradient(105deg, #3b55a0, #2a3f6e);
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(93, 139, 255, 0.2);
  }

  /* ----- MAIN CARD (frosted deep) ----- */
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
    font-size: 0.75rem;
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
    from {
      opacity: 0;
      transform: translateX(-6px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  tbody tr:hover {
    background: rgba(93, 139, 255, 0.05);
    border-bottom-color: rgba(93, 139, 255, 0.2);
  }

  td {
    padding: 1.1rem 1.2rem 1.1rem 1.5rem;
    vertical-align: middle;
  }

  /* event cell */
  .event-name {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--accent);
    background: var(--accent-dim);
    padding: 0.3rem 1rem;
    border-radius: 40px;
    width: fit-content;
    font-size: 0.85rem;
    border: 0.5px solid rgba(93, 139, 255, 0.2);
  }

  /* participant cell with name + email */
  .participant-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .participant-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
  }
  .participant-email {
    font-family: var(--mono);
    font-size: 0.7rem;
    color: var(--text-muted);
    background: var(--surface-2);
    padding: 0.2rem 0.6rem;
    border-radius: 30px;
    display: inline-block;
    width: fit-content;
  }

  /* status badges */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.3rem 1rem;
    border-radius: 60px;
    font-size: 0.7rem;
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

  /* actions buttons */
  .actions {
    display: flex;
    gap: 8px;
  }

  .btn-icon {
    text-decoration: none;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.45rem 1rem;
    border-radius: 30px;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--surface-2);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    cursor: pointer;
    font-family: var(--font);
  }

  .btn-icon:hover {
    transform: translateY(-2px);
    background: var(--surface);
    border-color: var(--red);
    color: var(--red);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }

  .btn-delete svg {
    width: 14px;
    height: 14px;
    stroke: currentColor;
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
    text-align: center;
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
  .empty a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
  }
  .empty a:hover {
    text-decoration: underline;
    color: #7d9eff;
  }

  /* responsive design */
  @media (max-width: 800px) {
    body {
      padding: 1rem;
    }
    .topbar {
      flex-direction: column;
      align-items: stretch;
    }
    .topbar-right {
      justify-content: flex-start;
    }
    td, th {
      padding: 0.8rem;
    }
    .actions {
      flex-direction: column;
    }
    .event-name {
      font-size: 0.75rem;
      padding: 0.2rem 0.8rem;
    }
    table {
      font-size: 0.85rem;
    }
  }

  /* subtle row stripes for readability */
  tbody tr:nth-child(even) {
    background: rgba(255, 255, 255, 0.01);
  }
</style>
</head>
<body>

<div class="page">

  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">⚖️ Judge Management</div>
      <h1>{{ $judge->name }} — Participant Assignments</h1>
      <p>Current participant assignments for this judge • manage scoring access</p>
    </div>
    <div class="topbar-right">
      <a href="{{ route('admin.judge.index') }}" class="btn-secondary">
        ← Back to Judges
      </a>
      <a href="{{ route('admin.judges.assign-participants', $judge) }}" class="btn-primary">
        + Add Assignments
      </a>
    </div>
  </div>

  <div class="card">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr>
            <th>Event</th>
            <th>Participant</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($assignments as $assignment)
          <tr style="animation-delay: {{ $loop->index * 0.04 }}s">
            <td>
              <div class="event-name">
                {{ $assignment->event->event_name }}
              </div>
            </td>
            <td>
              <div class="participant-info">
                <span class="participant-name">{{ $assignment->participant->name }}</span>
                <span class="participant-email">{{ $assignment->participant->email }}</span>
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
                <form method="POST" action="{{ route('admin.judges.participant-assignments.destroy', [$judge, $assignment]) }}" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Remove this assignment?')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remove
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="empty">
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p>No participant assignments yet. <a href="{{ route('admin.judges.assign-participants', $judge) }}">Assign participants →</a></p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- subtle micro-interaction script for delete button confirmation styling (pure UI enhancement) -->
<script>
  (function() {
    // add focus/blur effects on delete buttons to improve UX
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
      btn.addEventListener('click', function(e) {
        // native confirm is already there; just a visual micro-feedback
        const confirmed = confirm('⚠️ Remove this participant assignment? This action can be reversed by adding again.');
        if (!confirmed) {
          e.preventDefault();
          return false;
        }
        // subtle ripple effect (optional)
        btn.style.transform = 'scale(0.97)';
        setTimeout(() => { btn.style.transform = ''; }, 120);
      });
    });
    console.log('✨ Participant Assignments UI — modern glass design applied');
  })();
</script>

</body>
</html>
</x-admin-layout>
