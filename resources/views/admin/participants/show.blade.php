<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Submission Details - Admin Panel</title>
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

  .page { max-width: 800px; margin: 0 auto; }

  .topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
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
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 500;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .2s;
  }

  .btn-primary {
    background: var(--accent);
    color: white;
  }

  .btn-primary:hover { background: #5a84ff; }

  .btn-secondary {
    background: var(--surface-2);
    color: var(--text-secondary);
    border: 1px solid var(--border);
  }

  .btn-secondary:hover { background: var(--surface); border-color: var(--border-hover); }

  .btn-approve { background: var(--green-dim); color: var(--green); border-color: rgba(34,200,135,.2); }
  .btn-approve:hover { background: rgba(34,200,135,.22); }

  .btn-decline { background: var(--red-dim); color: var(--red); border-color: rgba(255,92,108,.18); }
  .btn-decline:hover { background: rgba(255,92,108,.18); }

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
  }

  .meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .meta-card {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
  }

  .meta-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    font-size: 13.5px;
  }

  .meta-label { 
    color: var(--text-muted); 
    font-weight: 500;
    font-family: var(--mono);
    font-size: 11px;
    letter-spacing: .05em;
    text-transform: uppercase;
  }

  .meta-value { 
    color: var(--text-primary); 
    font-weight: 600;
    font-size: 15px;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    font-family: var(--mono);
  }

  .status-pending { background: var(--orange-dim); color: var(--orange); }
  .status-reviewed { background: var(--green-dim); color: var(--green); }
  .status-draft { background: var(--red-dim); color: var(--red); }

  .content-section {
    margin-bottom: 24px;
  }

  .content-section h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border);
  }

  .content-text {
    line-height: 1.6;
    color: var(--text-secondary);
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    font-size: 14.5px;
  }

  .actions-grid {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    flex-wrap: wrap;
  }

  @media (max-width: 768px) {
    .actions-grid { justify-content: center; }
    .meta-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<div class="page">
  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Admin Panel</div>
      <h1>Submission Details</h1>
      <p>View participant application details</p>
    </div>
    <div>
      <a href="{{ route('admin.participants.edit', $submission) }}" class="btn btn-secondary" title="Edit">
        ✏️ Edit
      </a>
      <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">
        ← Back to List
      </a>
    </div>
  </div>

  <div class="card">
    <!-- Submission Meta -->
    <div class="meta-grid">
      <div class="meta-card">
        <div class="meta-row">
          <span class="meta-label">Submission ID</span>
          <span class="meta-value">#{{ $submission->id }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Participant</span>
          <span class="meta-value">{{ $submission->participant->name ?? 'Unknown' }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Email</span>
          <span class="meta-value">{{ $submission->participant->email ?? 'N/A' }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Event</span>
          <span class="meta-value">{{ $submission->event->event_name ?? 'N/A' }}</span>
        </div>
      </div>
      <div class="meta-card">
        <div class="meta-row">
          <span class="meta-label">Status</span>
          <span class="meta-value">
            <span class="status-badge status-{{ $submission->status }}">
              {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
            </span>
          </span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Submitted</span>
          <span class="meta-value">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="meta-row">
          <span class="meta-label">Updated</span>
          <span class="meta-value">{{ $submission->updated_at->format('M d, Y h:i A') }}</span>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="content-section">
      <h3>Submission Title</h3>
      <div class="content-text">{{ $submission->title }}</div>
    </div>

    <div class="content-section">
      <h3>Description</h3>
      <div class="content-text">{!! nl2br(e($submission->description)) !!}</div>
    </div>

    @if($submission->status === 'pending')
    <div class="actions-grid">
      <form action="{{ route('admin.participants.approve', $submission) }}" method="POST" style="display:inline;" onsubmit="return confirm('Approve this submission?')">
        @csrf
        <button type="submit" class="btn btn-approve">✅ Approve Submission</button>
      </form>
      <form action="{{ route('admin.participants.decline', $submission) }}" method="POST" style="display:inline;" onsubmit="return confirm('Decline this submission?')">
        @csrf
        <button type="submit" class="btn btn-decline">❌ Decline Submission</button>
      </form>
    </div>
    @endif
  </div>
</div>

</body>
</html>
</x-admin-layout>

