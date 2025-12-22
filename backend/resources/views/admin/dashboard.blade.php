@extends('admin.layout')

@section('title', 'Dashboard')

@section('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('styles')
<style>
    .welcome-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .welcome-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .welcome-avatar {
        width: 48px;
        height: 48px;
        background: #18181b;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }
    
    .welcome-text h3 {
        font-size: 16px;
        font-weight: 600;
        color: #18181b;
        margin-bottom: 4px;
    }
    
    .welcome-text p {
        font-size: 14px;
        color: #71717a;
    }
    
    .welcome-right {
        display: flex;
        gap: 16px;
        align-items: center;
    }
    
    .sign-out-btn {
        padding: 8px 16px;
        background: transparent;
        color: #71717a;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .sign-out-btn:hover {
        background: #f4f4f5;
    }
    
    .filament-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        background: transparent;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
    }
    
    .filament-logo {
        font-family: Georgia, serif;
        font-style: italic;
        font-size: 20px;
        font-weight: 700;
        color: #18181b;
    }
    
    .filament-version {
        font-size: 12px;
        color: #71717a;
    }
    
    .chart-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .chart-header {
        margin-bottom: 24px;
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #18181b;
    }
    
    .chart-container {
        position: relative;
        height: 350px;
    }
    
    .doc-link, .github-link {
        color: #71717a;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .doc-link:hover, .github-link:hover {
        color: #18181b;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<div class="welcome-card">
    <div class="welcome-left">
        <div class="welcome-avatar">A</div>
        <div class="welcome-text">
            <h3>Welcome</h3>
            <p>{{ auth()->user()->name ?? 'admin' }}</p>
        </div>
    </div>
    <div class="welcome-right">
        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="sign-out-btn">
                <span></span>
                Sign out
            </button>
        </form>
        <div class="filament-badge">
            <div>
                <div class="filament-logo">filament</div>
                <div class="filament-version">v4.3.1</div>
            </div>
            <div>
                <a href="#" class="doc-link"> Documentation</a>
                <a href="#" class="github-link"> GitHub</a>
            </div>
        </div>
    </div>
</div>

<div class="chart-card">
    <div class="chart-header">
        <h2 class="chart-title">Perbandingan Skor Asesmen Organisasi</h2>
    </div>
    <div class="chart-container">
        <canvas id="assessmentChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const chartData = @json($chart_data ?? []);
    
    const ctx = document.getElementById('assessmentChart').getContext('2d');
    const assessmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels || [],
            datasets: [{
                label: 'Skor',
                data: chartData.scores || [],
                backgroundColor: [
                    '#1e40af',
                    '#60a5fa',
                    '#93c5fd',
                    '#60a5fa',
                    '#93c5fd',
                    '#1e40af',
                    '#60a5fa',
                    '#93c5fd',
                    '#60a5fa',
                    '#93c5fd'
                ],
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection
