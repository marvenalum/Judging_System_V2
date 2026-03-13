<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Submission - Admin Panel</title>
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

  .page { max-width: 700px; margin: 0 auto; }

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

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px;
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 14px 16px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: var(--font);
    font-size: 14px;
    color: var(--text-primary);
    transition: border-color .2s;
  }

  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
  }

  .form-group textarea {
    resize: vertical;
    min-height: 120px;
  }

  .form-group select {
    cursor: pointer;
  }

  .meta-info {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
  }

  .meta-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 13px;
  }

  .meta-label { color: var(--text-muted); }
  .meta-value { color: var(--text-primary); font-weight: 500; }
</style>
</head>
<body>

<div class="page">
  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Admin Panel</div>
      <h1>Edit Submission</h1>
    </div>
    <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Back to list
    </a>
  </div>

  <div class="card">
    <!-- Submission Meta Info -->
    <div class="meta-info">
      <div class="meta-row">
        <span class="meta-label">Participant:</span>
        <span class="meta-value">{{ $submission->participant->name ?? 'Unknown' }} ({{ $submission->participant->email ?? '' }})</span>
      </div>
      <div class="meta-row">
        <span class="meta-label">Event:</span>
        <span class="meta-value">{{ $submission->event->event_name ?? 'N/A' }}</span>
      </div>
      <div class="meta-row">
        <span class="meta-label">Submitted:</span>
        <span class="meta-value">{{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'N/A' }}</span>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.participants.update', $submission) }}">
      @csrf
      @method('PATCH')

      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $submission->title) }}" required>
        @error('title')
          <p style="color: #ff5c6c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" required>{{ old('description', $submission->description) }}</textarea>
        @error('description')
          <p style="color: #ff5c6c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="pending" {{ old('status', $submission->status) === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="reviewed" {{ old('status', $submission->status) === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
          <option value="draft" {{ old('status', $submission->status) === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
        @error('status')
          <p style="color: #ff5c6c; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
        @enderror
      </div>

      <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update Submission</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
</x-admin-layout>
