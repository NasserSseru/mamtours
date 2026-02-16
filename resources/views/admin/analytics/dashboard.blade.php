@extends('layouts.app')

@section('title', 'Analytics Dashboard | MAM TOURS')

@section('content')
<div class="analytics-container">
    <div class="analytics-header">
        <h1><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
        <div class="analytics-controls">
            <select id="daysFilter" onchange="location.href='?days=' + this.value">
                <option value="7" {{ request('days', 30) == 7 ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30" {{ request('days', 30) == 30 ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90" {{ request('days', 30) == 90 ? 'selected' : '' }}>Last 90 Days</option>
                <option value="365" {{ request('days', 30) == 365 ? 'selected' : '' }}>Last Year</option>
            </select>
            <a href="{{ route('admin.analytics.export', ['type' => 'visitors']) }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon visitors">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <h3>Total Visitors</h3>
                <p class="metric-value">{{ number_format($total_visitors) }}</p>
                <small>Unique IP addresses</small>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon users">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="metric-content">
                <h3>Registered Users</h3>
                <p class="metric-value">{{ number_format($unique_users) }}</p>
                <small>Active users</small>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon pageviews">
                <i class="fas fa-eye"></i>
            </div>
            <div class="metric-content">
                <h3>Page Views</h3>
                <p class="metric-value">{{ number_format($total_page_views) }}</p>
                <small>Total visits</small>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon bookings">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-content">
                <h3>Total Bookings</h3>
                <p class="metric-value">{{ number_format($total_bookings) }}</p>
                <small>All bookings</small>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon revenue">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="metric-content">
                <h3>Total Revenue</h3>
                <p class="metric-value">UGX {{ number_format($total_booking_value) }}</p>
                <small>From bookings</small>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon completed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="metric-content">
                <h3>Completed</h3>
                <p class="metric-value">{{ number_format($completed_bookings) }}</p>
                <small>Finished bookings</small>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3>Daily Visitors</h3>
            <canvas id="dailyVisitorsChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Daily Bookings</h3>
            <canvas id="dailyBookingsChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Traffic by Device</h3>
            <canvas id="deviceChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Traffic by Browser</h3>
            <canvas id="browserChart"></canvas>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="analytics-section">
        <h2>Top Pages</h2>
        <table class="analytics-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Visits</th>
                    <th>Avg Duration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($top_pages as $page)
                    <tr>
                        <td>{{ $page['page_url'] ?? 'N/A' }}</td>
                        <td>{{ $page['visits'] ?? 0 }}</td>
                        <td>{{ round($page['avg_duration'] ?? 0) }}s</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <a href="{{ route('admin.analytics.visitors') }}" class="quick-link">
            <i class="fas fa-users"></i>
            <span>View Visitors</span>
        </a>
        <a href="{{ route('admin.analytics.users') }}" class="quick-link">
            <i class="fas fa-user-friends"></i>
            <span>Manage Users</span>
        </a>
        <a href="{{ route('admin.analytics.active-users') }}" class="quick-link">
            <i class="fas fa-user-clock"></i>
            <span>Active Users</span>
        </a>
        <a href="{{ route('admin.analytics.pages') }}" class="quick-link">
            <i class="fas fa-file-alt"></i>
            <span>Page Analytics</span>
        </a>
        <a href="{{ route('admin.analytics.actions') }}" class="quick-link">
            <i class="fas fa-mouse"></i>
            <span>User Actions</span>
        </a>
    </div>
</div>

<style>
.analytics-container {
    padding: 2rem;
    background: #f5f5f5;
    min-height: 100vh;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.analytics-header h1 {
    margin: 0;
    color: #1a2332;
}

.analytics-controls {
    display: flex;
    gap: 1rem;
}

.analytics-controls select,
.analytics-controls .btn {
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.95rem;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.metric-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-4px);
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.metric-icon.visitors { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.metric-icon.users { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.metric-icon.pageviews { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.metric-icon.bookings { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.metric-icon.revenue { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.metric-icon.completed { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }

.metric-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 0.95rem;
    color: #666;
}

.metric-value {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a2332;
}

.metric-content small {
    color: #999;
    font-size: 0.85rem;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chart-card h3 {
    margin: 0 0 1rem 0;
    color: #1a2332;
}

.analytics-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.analytics-section h2 {
    margin: 0 0 1rem 0;
    color: #1a2332;
}

.analytics-table {
    width: 100%;
    border-collapse: collapse;
}

.analytics-table th {
    background: #f5f5f5;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #1a2332;
    border-bottom: 2px solid #ddd;
}

.analytics-table td {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.analytics-table tr:hover {
    background: #f9f9f9;
}

.quick-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.quick-link {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    text-decoration: none;
    color: #1a2332;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.quick-link:hover {
    background: #ff9800;
    color: white;
    transform: translateY(-4px);
}

.quick-link i {
    font-size: 1.5rem;
    display: block;
    margin-bottom: 0.5rem;
}

.quick-link span {
    font-weight: 600;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .analytics-header {
        flex-direction: column;
        gap: 1rem;
    }

    .metrics-grid {
        grid-template-columns: 1fr;
    }

    .charts-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Load chart data
async function loadCharts() {
    const days = new URLSearchParams(window.location.search).get('days') || 30;
    
    // Daily Visitors Chart
    const visitorsData = await fetch(`/admin/analytics/chart-data?type=daily_visitors&days=${days}`).then(r => r.json());
    new Chart(document.getElementById('dailyVisitorsChart'), {
        type: 'line',
        data: {
            labels: Object.keys(visitorsData),
            datasets: [{
                label: 'Visitors',
                data: Object.values(visitorsData),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Daily Bookings Chart
    const bookingsData = await fetch(`/admin/analytics/chart-data?type=daily_bookings&days=${days}`).then(r => r.json());
    new Chart(document.getElementById('dailyBookingsChart'), {
        type: 'bar',
        data: {
            labels: bookingsData.map(b => b.date),
            datasets: [{
                label: 'Bookings',
                data: bookingsData.map(b => b.bookings),
                backgroundColor: '#43e97b'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Device Chart
    const deviceData = await fetch(`/admin/analytics/chart-data?type=traffic_device&days=${days}`).then(r => r.json());
    new Chart(document.getElementById('deviceChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(deviceData),
            datasets: [{
                data: Object.values(deviceData),
                backgroundColor: ['#667eea', '#f093fb', '#4facfe']
            }]
        },
        options: { responsive: true }
    });

    // Browser Chart
    const browserData = await fetch(`/admin/analytics/chart-data?type=traffic_browser&days=${days}`).then(r => r.json());
    new Chart(document.getElementById('browserChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(browserData),
            datasets: [{
                data: Object.values(browserData),
                backgroundColor: ['#fa709a', '#fee140', '#30cfd0', '#330867', '#43e97b']
            }]
        },
        options: { responsive: true }
    });
}

loadCharts();
</script>
@endsection
