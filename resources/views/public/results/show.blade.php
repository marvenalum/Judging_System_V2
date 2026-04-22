<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Judging System') }} - Results: {{ $publication->publication_code }}</title>
    <link href="https://fonts.bunny.net/css?family=cabinet-grotesk:400,500,600,700,800|satoshi:400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-gradient-start: #000000;
            --primary-gradient-end: #211e3b;
            --accent-primary: #f3d008;
            --accent-secondary: #8b5cf6;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
        }
        body { font-family: 'Satoshi', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .results-hero { background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .rank-gold { background: linear-gradient(135deg, #ffd700, #ffed4a); }
        .rank-silver { background: linear-gradient(135deg, #c0c0c0, #e5e5e5); }
        .rank-bronze { background: linear-gradient(135deg, #cd7f32, #deb887); }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary mb-3" style="font-family: 'Cabinet Grotesk', sans-serif; background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Official Results
            </h1>
            <div class="results-hero p-5 mx-auto" style="max-width: 800px;">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <h5 class="text-muted mb-1"><i class="bi bi-calendar-event me-2"></i>Event</h5>
                        <h4 class="fw-bold text-dark">{{ $publication->event->event_name }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-muted mb-1"><i class="bi bi-tag me-2"></i>Category</h5>
                        <h4 class="fw-bold text-dark">{{ $publication->category->name }}</h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-muted mb-1"><i class="bi bi-award me-2"></i>Publication</h5>
                        <code class="bg-light p-2 rounded fw-semibold fs-6 d-inline-block">{{ $publication->publication_code }}</code>
                    </div>
                </div>
            </div>
        </div>

        @if($publication->results_data && count($publication->results_data) > 0)
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 results-hero">
                        <div class="card-header bg-gradient text-white text-center py-4">
                            <h3 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2"></i>Final Standings</h3>
                            <p class="mb-0 opacity-75">{{ count($publication->results_data) }} participants ranked</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Participant</th>
                                        <th>Total Score</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publication->results_data as $result)
                                        <tr>
                                            <td>
                                                @if($loop->first)
                                                    <span class="badge rank-gold px-3 py-2 fs-6 fw-bold text-dark shadow">🥇 1st</span>
                                                @elseif($loop->iteration == 2)
                                                    <span class="badge rank-silver px-3 py-2 fs-6 fw-bold text-dark shadow">🥈 2nd</span>
                                                @elseif($loop->iteration == 3)
                                                    <span class="badge rank-bronze px-3 py-2 fs-6 fw-bold text-dark shadow">🥉 3rd</span>
                                                @else
                                                    <span class="badge bg-light text-dark px-3 py-2 fs-6 fw-bold">#{{ $result['rank'] }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $result['participant_name'] }}</div>
                                            </td>
                                            <td>
                                                <div class="h4 fw-bold text-primary mb-0">{{ number_format($result['total_score'], 1) }}</div>
                                                <small class="text-muted">out of {{ $result['max_possible'] }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success fs-5 px-4 py-2 fw-bold">
                                                    {{ $result['percentage'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-light text-center py-4">
                            <small class="text-muted">
                                Published: {{ $publication->published_at->format('F j, Y g:i A') }} | 
                                Results computed for {{ $publication->event->event_name }} - {{ $publication->category->name }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-hourglass-split display-1 text-muted mb-4"></i>
                <h3 class="text-muted">Results not yet available</h3>
                <p class="lead text-muted">This publication is still being prepared.</p>
            </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-house me-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

