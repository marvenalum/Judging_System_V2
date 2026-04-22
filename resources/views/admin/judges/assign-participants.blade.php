<x-admin-layout>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Participants - {{ $judge->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  /* ----- GLOBAL RESET & BASE (enhanced design) ----- */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: linear-gradient(145deg, #f0f4fc 0%, #e9eef5 100%);
    font-family: 'Sora', sans-serif;
    color: #1a2639;
    line-height: 1.5;
    padding: 2rem;
  }

  /* modern scrollbar */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }
  ::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb {
    background: #9aa9c1;
    border-radius: 10px;
  }
  ::-webkit-scrollbar-thumb:hover {
    background: #6c7a96;
  }

  /* main container */
  .page {
    max-width: 1000px;
    margin: 0 auto;
    animation: fadeSlideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
  }

  @keyframes fadeSlideUp {
    0% {
      opacity: 0;
      transform: translateY(24px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ----- TOPBAR (modern glassmorphic) ----- */
  .topbar {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(12px);
    border-radius: 32px;
    padding: 1.25rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.03), 0 2px 4px rgba(0,0,0,0.02);
    border: 1px solid rgba(255,255,255,0.6);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
  }

  .topbar-left {
    flex: 1;
  }

  .eyebrow {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    color: #4f6f8f;
    background: #eef2fa;
    display: inline-block;
    padding: 0.2rem 0.8rem;
    border-radius: 40px;
    margin-bottom: 0.75rem;
    backdrop-filter: blur(2px);
  }

  .topbar-left h1 {
    font-size: 1.9rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #1f2b48, #2c3e66);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    margin-bottom: 0.25rem;
  }

  .topbar-left p {
    color: #4a5b7a;
    font-size: 0.95rem;
    font-weight: 400;
  }

  .topbar-right .btn-secondary {
    background: rgba(255,255,255,0.9);
    border: 1px solid #cdd9ed;
    padding: 0.6rem 1.4rem;
    border-radius: 60px;
    font-weight: 500;
    font-size: 0.85rem;
    font-family: 'Sora', sans-serif;
    color: #1f2b48;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: blur(4px);
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
  }

  .topbar-right .btn-secondary:hover {
    background: white;
    border-color: #b7c4dd;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
  }

  /* ----- MAIN CARD (glassmorphism + smooth corners) ----- */
  .card {
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(2px);
    border-radius: 36px;
    box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0,0,0,0.02);
    border: 1px solid rgba(255,255,255,0.5);
    overflow: hidden;
    transition: all 0.2s;
  }

  /* form content spacing */
  form {
    display: flex;
    flex-direction: column;
  }

  /* event block styling */
  .event-block {
    margin: 0 1.5rem 1.5rem 1.5rem;
    border-radius: 24px;
    background: #ffffff;
    transition: all 0.2s;
  }

  .event-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #eef2fa;
  }

  .event-title {
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: -0.2px;
    background: linear-gradient(120deg, #1f2b48, #2d3a5e);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .event-badge {
    background: #eef3ff;
    color: #2c5282;
    border-radius: 40px;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.7rem;
    letter-spacing: 0.3px;
  }

  /* participant list - modern checkbox cards */
  .participant-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .participant-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #fafcff;
    border: 1px solid #e6edf6;
    border-radius: 20px;
    padding: 0.9rem 1.2rem;
    transition: all 0.2s ease;
    cursor: pointer;
    backdrop-filter: blur(2px);
  }

  .participant-item:hover {
    background: #ffffff;
    border-color: #cbddea;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.03);
    transform: translateX(3px);
  }

  /* custom checkbox design */
  .custom-check {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .participant-item input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: #3b6ea0;
    cursor: pointer;
    border-radius: 6px;
    transition: transform 0.1s;
  }

  .participant-item input[type="checkbox"]:hover {
    transform: scale(1.05);
  }

  .participant-info {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 0.75rem;
  }

  .participant-name {
    font-weight: 650;
    font-size: 1rem;
    color: #1e2a44;
    letter-spacing: -0.2px;
  }

  .participant-email {
    font-size: 0.8rem;
    color: #6f7c91;
    font-family: 'JetBrains Mono', monospace;
    background: #f0f4fa;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
  }

  /* empty state or separator */
  .no-participants-message {
    background: #f8fafd;
    padding: 1.2rem;
    text-align: center;
    border-radius: 28px;
    color: #5f6f8a;
    font-size: 0.85rem;
    border: 1px dashed #cbdae9;
  }

  /* action bar - footer */
  .action-footer {
    padding: 1.2rem 2rem 2rem 2rem;
    background: rgba(248, 250, 255, 0.9);
    border-top: 1px solid #eef2f8;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
  }

  .btn-primary {
    background: linear-gradient(105deg, #1e2f4b, #14213d);
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 60px;
    font-weight: 600;
    font-size: 0.9rem;
    font-family: 'Sora', sans-serif;
    color: white;
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.2px;
  }

  .btn-primary:hover {
    background: linear-gradient(105deg, #253b5e, #192945);
    transform: translateY(-2px);
    box-shadow: 0 12px 22px -8px rgba(0, 0, 0, 0.2);
  }

  .btn-primary:active {
    transform: translateY(1px);
  }

  /* subtle divider between events */
  .event-divider {
    margin: 0.5rem 0 1.2rem 0;
    height: 1px;
    background: radial-gradient(circle, #dce5f0 30%, transparent);
  }

  /* responsive design */
  @media (max-width: 700px) {
    body {
      padding: 1rem;
    }
    .topbar {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
      padding: 1.2rem;
    }
    .topbar-right {
      width: 100%;
    }
    .btn-secondary {
      display: inline-block;
      width: auto;
    }
    .participant-info {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.3rem;
    }
    .event-block {
      margin: 0 1rem 1.5rem 1rem;
    }
    .participant-item {
      padding: 0.7rem 1rem;
    }
  }

  /* custom badge for "currently assigned" indicator — optional visual improvement */
  .assigned-badge {
    background: #e2f0e8;
    color: #1f6e43;
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 30px;
    padding: 0.2rem 0.7rem;
    margin-left: 0.5rem;
    white-space: nowrap;
  }

  /* loading / subtle animation for checkboxes */
  @keyframes gentlePulse {
    0% { box-shadow: 0 0 0 0 rgba(59,110,160,0.2);}
    100% { box-shadow: 0 0 0 4px rgba(59,110,160,0);}
  }
  .participant-item:focus-within {
    animation: gentlePulse 0.4s ease-out;
  }

  /* extra polish: custom focus rings */
  input:focus-visible, button:focus-visible, a:focus-visible {
    outline: 2px solid #3b6ea0;
    outline-offset: 2px;
    border-radius: 12px;
  }
</style>
</head>
<body>

<div class="page">

  <div class="topbar">
    <div class="topbar-left">
      <div class="eyebrow">✦ Judge Management</div>
      <h1>Assign Participants <span style="font-weight:500;">to {{ $judge->name }}</span></h1>
      <p>Select participants from assigned events • multi-event support</p>
    </div>
    <div class="topbar-right">
      <a href="{{ route('admin.judge.index') }}" class="btn-secondary">← Back to Judges</a>
    </div>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('admin.judges.assign-participants.store', $judge) }}">
      @csrf

      @foreach($events as $event)
        @if(isset($participantsByEvent[$event->id]) && $participantsByEvent[$event->id]->count() > 0)
          <div class="event-block">
            <div class="event-header">
              <div class="event-title">
                <span>📋 {{ $event->event_name }}</span>
                <span class="event-badge">{{ $participantsByEvent[$event->id]->count() }} participants</span>
              </div>
            </div>
            <div class="participant-list">
              @foreach($participantsByEvent[$event->id] as $participant)
                @php
                  $isChecked = isset($currentAssignments[$event->id]) && $currentAssignments[$event->id] == $participant->id;
                @endphp
                <label class="participant-item" for="chk_{{ $event->id }}_{{ $participant->id }}">
                  <div class="custom-check">
                    <input type="checkbox" 
                           name="assignments[{{ $participant->id }}][participant_id]" 
                           value="{{ $participant->id }}" 
                           id="chk_{{ $event->id }}_{{ $participant->id }}"
                           {{ $isChecked ? 'checked' : '' }}>
                  </div>
                  <div class="participant-info">
                    <span class="participant-name">{{ $participant->name }}</span>
                    <span class="participant-email">{{ $participant->email }}</span>
                    @if($isChecked)
                      <span class="assigned-badge">✓ currently assigned</span>
                    @endif
                  </div>
                  <input type="hidden" name="assignments[{{ $participant->id }}][event_id]" value="{{ $event->id }}">
                </label>
              @endforeach
            </div>
          </div>
          <div class="event-divider"></div>
        @else
          <!-- subtle fallback: show that event has no participants (optional) -->
          @if(isset($events) && $events->count() > 0 && isset($participantsByEvent[$event->id]))
            <div class="event-block" style="opacity:0.8;">
              <div class="event-header">
                <div class="event-title">
                  <span>📌 {{ $event->event_name }}</span>
                </div>
              </div>
              <div class="no-participants-message">
                ✨ No participants available for this event yet. 
              </div>
            </div>
          @endif
        @endif
      @endforeach

      <!-- if no events with participants at all -->
      @if($events->isEmpty() || $events->every(fn($e) => !isset($participantsByEvent[$e->id]) || $participantsByEvent[$e->id]->isEmpty()))
        <div class="event-block" style="margin: 2rem; text-align:center;">
          <div class="no-participants-message" style="background: #fef7e0; border-color: #ffe0a3;">
            ⚡ No events with participants found. Please assign events to this judge first.
          </div>
        </div>
      @endif

      <div class="action-footer">
        <button type="submit" class="btn-primary">
          ✨ Assign Selected Participants
        </button>
      </div>
    </form>
  </div>

</div>

<!-- subtle extra: dynamic selection count (optional but pure UI enhancement, no backend required) -->
<script>
  (function() {
    // just a tiny UX: count selected checkboxes and display live count on button? 
    // totally optional but adds delight - no interference with backend
    const form = document.querySelector('form');
    if (!form) return;
    const submitBtn = form.querySelector('.btn-primary');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    
    function updateButtonText() {
      if (!submitBtn) return;
      const checked = Array.from(checkboxes).filter(cb => cb.checked).length;
      if (checked > 0) {
        submitBtn.innerHTML = `✓ Assign Selected Participants (${checked})`;
      } else {
        submitBtn.innerHTML = `✨ Assign Selected Participants`;
      }
    }
    
    if (checkboxes.length) {
      checkboxes.forEach(cb => cb.addEventListener('change', updateButtonText));
      updateButtonText();
    }
    
    // optional: warn if none selected before submit? no conflict, just style
    form.addEventListener('submit', function(e) {
      const checkedAssignments = Array.from(checkboxes).filter(cb => cb.checked).length;
      if (checkedAssignments === 0) {
        // show a nice toast style? but no blocking, just informative
        const warnDiv = document.createElement('div');
        warnDiv.innerText = '⚠️ No participants selected. Please select at least one participant.';
        warnDiv.style.position = 'fixed';
        warnDiv.style.bottom = '20px';
        warnDiv.style.left = '50%';
        warnDiv.style.transform = 'translateX(-50%)';
        warnDiv.style.backgroundColor = '#fef2e0';
        warnDiv.style.color = '#a6611a';
        warnDiv.style.padding = '0.75rem 1.5rem';
        warnDiv.style.borderRadius = '60px';
        warnDiv.style.fontWeight = '500';
        warnDiv.style.fontSize = '0.85rem';
        warnDiv.style.zIndex = '999';
        warnDiv.style.backdropFilter = 'blur(8px)';
        warnDiv.style.border = '1px solid #ffdfa5';
        warnDiv.style.boxShadow = '0 8px 20px rgba(0,0,0,0.1)';
        warnDiv.style.fontFamily = "'Sora', sans-serif";
        document.body.appendChild(warnDiv);
        setTimeout(() => { warnDiv.style.opacity = '0'; setTimeout(() => warnDiv.remove(), 500); }, 2500);
        e.preventDefault();
        return false;
      }
      return true;
    });
  })();
</script>

</body>
</html>
</x-admin-layout>
