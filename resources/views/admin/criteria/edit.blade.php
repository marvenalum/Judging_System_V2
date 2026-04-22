<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Criterion - Admin Panel</title>
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

  .page { max-width: 1200px; margin: 0 auto; }

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

  .btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-family: var(--font);
    font-size: 13.5px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
  }

  .btn-primary {
    background: var(--accent);
    color: #fff;
  }

  .btn-primary:hover {
    background: #3f6cef;
    box-shadow: 0 4px 20px var(--accent-glow);
    transform: translateY(-1px);
  }

  .btn-secondary {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border);
  }

  .btn-secondary:hover {
    background: var(--surface-2);
    border-color: var(--border-hover);
  }

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }

  .form-section {
    padding: 32px;
  }

  .form-row {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 8px;
  }

  .form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 12px 16px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 14px;
    font-family: var(--font);
    transition: border-color .2s, box-shadow .2s;
  }

  .form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-dim);
  }

  .form-textarea {
    resize: vertical;
    min-height: 100px;
    font-family: var(--mono);
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }

  .btn-group {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 32px;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 24px;
    font-size: 13.5px;
    font-weight: 500;
  }

  .alert-error {
    background: var(--red-dim);
    color: var(--red);
    border: 1px solid rgba(255,92,108,.2);
  }

  @keyframes fadeDown { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

  @media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .btn-group { flex-direction: column; }
  }
</style>
</head>
<body>

<div class="page">
  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">Admin Panel</div>
      <h1>Edit Criterion</h1>
      <p>Update scoring criteria for your event</p>
    </div>
  </div>

  @if($errors->any())
    <div class="alert alert-error">
      <strong>Please fix the following errors:</strong>
      <ul style="margin-top: 8px; padding-left: 20px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <form action="{{ route('admin.criteria.update', $criteria) }}" method="POST" class="form-section">
      @csrf
      @method('PUT')

      <div class="form-row">
        <label for="name" class="form-label">Criterion Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $criteria->name) }}" class="form-input" required>
      </div>

      <div class="form-row">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-textarea" placeholder="Optional description of this criterion...">{{ old('description', $criteria->description) }}</textarea>
      </div>

      <div class="form-grid">
        <div class="form-row">
          <label for="event_id" class="form-label">Event *</label>
          <select name="event_id" id="event_id" class="form-select" required>
            <option value="">Select an event</option>
            @foreach($events as $event)
              <option value="{{ $event->id }}" {{ old('event_id', $criteria->event_id) == $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-row">
          <label for="category_id" class="form-label">Category *</label>
          <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select a category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id', $criteria->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-grid">
        <div class="form-row">
          <label for="max_score" class="form-label">Maximum Score</label>
          <input type="number" name="max_score" id="max_score" value="{{ old('max_score', $criteria->max_score) }}" step="0.01" min="0" class="form-input">
        </div>

        <div class="form-row">
          <label for="percentage_weight" class="form-label">Percentage Weight (%)</label>
          <input type="number" name="percentage_weight" id="percentage_weight" value="{{ old('percentage_weight', $criteria->percentage_weight) }}" step="0.01" min="0" max="100" class="form-input">
        </div>
      </div>

      <div class="form-row">
        <label for="status" class="form-label">Status *</label>
        <select name="status" id="status" class="form-select" required>
          <option value="active" {{ old('status', $criteria->status) == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $criteria->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <div class="btn-group">
        <a href="{{ route('admin.criteria.index') }}" class="btn-secondary">Cancel</a>
        <button type="submit" class="btn-primary">Update Criterion</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
