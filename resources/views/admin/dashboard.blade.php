{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Tổng quan hệ thống rạp phim')

@section('styles')
<style>
    /* Wrapper chung */
    .dash-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Header */
    .dash-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
    }

    .dash-title-block {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .dash-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        background: linear-gradient(to right, rgba(56,189,248,0.16), rgba(34,197,94,0.16));
        border: 1px solid rgba(148,163,184,0.3);
        color: #a5b4fc;
    }

    .dash-main-title {
        font-size: 1.25rem;
        font-weight: 700;
        background: linear-gradient(to right, #22c55e, #0ea5e9);
        -webkit-background-clip: text;
        color: transparent;
    }

    .dash-subtext {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .dash-header-right {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.6rem;
    }

    .dash-range-pill {
        border-radius: 999px;
        padding: 6px 10px;
        border: 1px solid #1f2937;
        background: rgba(15,23,42,0.9);
        font-size: 0.75rem;
        color: #e5e7eb;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .dash-refresh-btn {
        border-radius: 999px;
        padding: 6px 10px;
        border: 1px solid #1f2937;
        background: radial-gradient(circle at top left, rgba(56,189,248,0.25), rgba(15,23,42,1));
        font-size: 0.75rem;
        color: #e5e7eb;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
    }

    .dash-refresh-btn:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
        box-shadow: 0 10px 25px rgba(15,23,42,0.8);
    }

    /* Card & chart */
    .dash-card {
        border-radius: 18px;
        border: 1px solid rgba(15,23,42,1);
        background:
            radial-gradient(circle at top left, rgba(56,189,248,0.12), transparent 55%),
            radial-gradient(circle at bottom right, rgba(52,211,153,0.12), transparent 55%),
            rgba(15,23,42,0.92);
        box-shadow: 0 18px 45px rgba(15,23,42,0.85);
        padding: 16px 16px 14px;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .dash-card::before {
        content: '';
        position: absolute;
        inset-inline: -40%;
        top: -70%;
        height: 150%;
        background: radial-gradient(circle at top, rgba(148,163,184,0.14), transparent 60%);
        opacity: 0;
        transition: opacity .2s ease;
        pointer-events: none;
    }

    .dash-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59,130,246,0.7);
        box-shadow: 0 20px 60px rgba(15,23,42,0.95);
    }

    .dash-card:hover::before {
        opacity: 1;
    }

    .dash-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 6px;
    }

    .dash-card-main-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #e5e7eb;
    }

    .dash-card-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .dash-card-chip {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid rgba(75,85,99,0.8);
        background: rgba(15,23,42,0.85);
        color: #9ca3af;
    }

    .dash-card-sub {
        font-size: 0.75rem;
        color: #9ca3af;
    }

    .dash-section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .dash-section-desc {
        font-size: 0.72rem;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .dash-chart-wrapper {
        height: 260px;
        position: relative;
    }

    .dash-chart-wrapper canvas {
        position: relative;
        z-index: 2;
    }

    .dash-chart-glow {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top, rgba(59,130,246,0.1), transparent 60%);
        opacity: 0;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .dash-card:hover .dash-chart-glow {
        opacity: 1;
    }

    /* Grid layouts */
    .dash-grid-cards {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .dash-grid-cards {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .dash-grid-2 {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (min-width: 1024px) {
        .dash-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* fade-in animation cho section */
    .dash-fade {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity .35s ease, transform .35s ease;
    }
    .dash-fade.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<div class="dash-wrapper">
    {{-- Header --}}
    <div class="dash-header dash-fade">
        <div class="dash-title-block">
            {{-- <span class="dash-pill">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Realtime overview
            </span> --}}
            <div class="dash-main-title">
                Thống kê rạp phim Laravel
            </div>
            <div class="dash-subtext">
                Theo dõi nhanh số lượng phim, rạp, lượt đặt vé và hành vi xem phim của khách hàng.
            </div>
        </div>

        <div class="dash-header-right">
            <span class="dash-range-pill">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                30 ngày gần nhất (demo)
            </span>
            <button type="button" class="dash-refresh-btn" id="dash-refresh-btn">
                <span class="inline-block rotate-0" id="dash-refresh-icon">⟳</span>
                Làm mới biểu đồ
            </button>
        </div>
    </div>

    {{-- Cards tổng quan --}}
    <div class="dash-grid-cards dash-fade">
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-card-label">Tổng số phim</div>
                    <div class="dash-card-main-value">{{ $totalMovies }}</div>
                    <div class="dash-card-sub">
                        Toàn bộ phim đang quản lý trong hệ thống.
                    </div>
                </div>
                <span class="dash-card-chip">
                    🎬 Movies
                </span>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-card-label">Tổng số rạp</div>
                    <div class="dash-card-main-value">{{ $totalCinemas }}</div>
                    <div class="dash-card-sub">
                        Số lượng rạp đang hoạt động.
                    </div>
                </div>
                <span class="dash-card-chip">
                    🏙️ Cinemas
                </span>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-card-label">Tổng số đặt vé</div>
                    <div class="dash-card-main-value">{{ $totalBookings }}</div>
                    <div class="dash-card-sub">
                        Tổng lượt booking đã ghi nhận trong DB.
                    </div>
                </div>
                <span class="dash-card-chip">
                    🎟️ Bookings
                </span>
            </div>
        </div>
    </div>

    {{-- Hàng 1: Thể loại & Số lượng phim theo tháng --}}
    <div class="dash-grid-2 dash-fade">
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-section-title">Tỉ lệ thể loại phim</div>
                    <p class="dash-section-desc">
                        Biểu đồ tròn thể hiện % số phim của từng thể loại.
                    </p>
                </div>
            </div>
            <div class="dash-chart-wrapper">
                <div class="dash-chart-glow"></div>
                <canvas id="genrePieChart"></canvas>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-section-title">Số lượng phim theo tháng</div>
                    <p class="dash-section-desc">
                        Dựa trên ngày khởi chiếu (<code class="text-[10px] text-slate-400">release_date</code>).
                    </p>
                </div>
            </div>
            <div class="dash-chart-wrapper">
                <div class="dash-chart-glow"></div>
                <canvas id="moviePerMonthChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Hàng 2: Số phim / rạp & khách xem / rạp --}}
    <div class="dash-grid-2 dash-fade">
        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-section-title">Số phim đang chiếu theo rạp</div>
                    <p class="dash-section-desc">
                        Đếm số phim có suất chiếu ở mỗi rạp (distinct movie_id).
                    </p>
                </div>
            </div>
            <div class="dash-chart-wrapper">
                <div class="dash-chart-glow"></div>
                <canvas id="moviesByCinemaChart"></canvas>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <div>
                    <div class="dash-section-title">Lượt đặt vé theo rạp</div>
                    <p class="dash-section-desc">
                        Số booking được tạo từ các suất chiếu thuộc từng rạp.
                    </p>
                </div>
            </div>
            <div class="dash-chart-wrapper">
                <div class="dash-chart-glow"></div>
                <canvas id="bookingsByCinemaChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Hàng 3: khách xem theo phim (top 10) --}}
    <div class="dash-card dash-fade">
        <div class="dash-card-header">
            <div>
                <div class="dash-section-title">Top phim được đặt vé nhiều nhất</div>
                <p class="dash-section-desc">
                    Top 10 phim có số lượt đặt vé cao nhất (dựa trên bảng <code class="text-[10px] text-slate-400">bookings</code>).
                </p>
            </div>
        </div>
        <div class="dash-chart-wrapper">
            <div class="dash-chart-glow"></div>
            <canvas id="bookingsByMovieChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Fade-in từng section
    const fadeEls = document.querySelectorAll('.dash-fade');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('show');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    fadeEls.forEach(el => io.observe(el));

    // Nút refresh (chỉ animate lại chart chứ không reload page)
    const refreshBtn  = document.getElementById('dash-refresh-btn');
    const refreshIcon = document.getElementById('dash-refresh-icon');

    if (refreshBtn && refreshIcon) {
        refreshBtn.addEventListener('click', () => {
            refreshIcon.style.transition = 'transform .5s ease';
            refreshIcon.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                refreshIcon.style.transform = 'rotate(0deg)';
            }, 520);

            // Nếu muốn sau này làm reload dữ liệu AJAX thì hook ở đây
        });
    }

    // ====== Data từ server (PHP -> JS) ======
    const genreLabels            = @json($genreLabels);
    const genreCounts            = @json($genreCounts);

    const moviesMonthLabels      = @json($moviesMonthLabels);
    const moviesMonthCounts      = @json($moviesMonthCounts);

    const cinemaMovieLabels      = @json($cinemaMovieLabels);
    const cinemaMovieCounts      = @json($cinemaMovieCounts);

    const cinemaBookingLabels    = @json($cinemaBookingLabels);
    const cinemaBookingCounts    = @json($cinemaBookingCounts);

    const movieBookingLabels     = @json($movieBookingLabels);
    const movieBookingCounts     = @json($movieBookingCounts);

    // ====== Global Chart.js options cho dark mode đẹp hơn ======
    Chart.defaults.color = '#e5e7eb';
    Chart.defaults.font.family = 'system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", ui-sans-serif';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.pointStyle = 'circle';

    const gridColor   = 'rgba(55,65,81,0.45)';
    const borderColor = '#1f2937';

    // helper tạo màu
    const palette = [
        '#22c55e', '#0ea5e9', '#a855f7', '#f97316', '#facc15',
        '#ec4899', '#14b8a6', '#8b5cf6', '#fb7185', '#38bdf8'
    ];

    function getColors(len) {
        const colors = [];
        for (let i = 0; i < len; i++) {
            colors.push(palette[i % palette.length]);
        }
        return colors;
    }

    // Animation chung
    const chartAnimations = {
        tension: {
            duration: 750,
            easing: 'easeOutQuad',
            from: 0.2,
            to: 0.4,
            loop: false
        }
    };

    // ====== 1. Pie chart: tỉ lệ thể loại ======
    const genreCtx = document.getElementById('genrePieChart');
    if (genreCtx && genreLabels.length) {
        new Chart(genreCtx, {
            type: 'pie',
            data: {
                labels: genreLabels,
                datasets: [{
                    data: genreCounts,
                    backgroundColor: getColors(genreLabels.length).map(c => c + 'CC'),
                    borderColor: borderColor,
                    borderWidth: 2,
                }]
            },
            options: {
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 800
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#e5e7eb', font: { size: 11 } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(148,163,184,0.5)',
                        borderWidth: 1,
                        callbacks: {
                            label: (ctx) => {
                                const total   = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const value   = ctx.parsed;
                                const percent = total ? (value / total * 100).toFixed(1) : 0;
                                return `${ctx.label}: ${value} phim (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ====== 2. Line chart: số phim theo tháng ======
    const movieMonthCtx = document.getElementById('moviePerMonthChart');
    if (movieMonthCtx && moviesMonthLabels.length) {
        const ctx = movieMonthCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(14,165,233,0.45)');
        gradient.addColorStop(1, 'rgba(14,165,233,0.03)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: moviesMonthLabels,
                datasets: [{
                    label: 'Số phim',
                    data: moviesMonthCounts,
                    borderColor: '#0ea5e9',
                    backgroundColor: gradient,
                    tension: 0.32,
                    fill: true,
                    pointRadius: 3.2,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#0ea5e9',
                    pointBorderWidth: 0,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', font: { size: 10 } },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: '#9ca3af', stepSize: 1, precision: 0 },
                        grid: { color: gridColor }
                    }
                },
                animation: chartAnimations,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(148,163,184,0.5)',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    // ====== 3. Bar chart: số phim theo rạp ======
    const moviesByCinemaCtx = document.getElementById('moviesByCinemaChart');
    if (moviesByCinemaCtx && cinemaMovieLabels.length) {
        new Chart(moviesByCinemaCtx, {
            type: 'bar',
            data: {
                labels: cinemaMovieLabels,
                datasets: [{
                    label: 'Số phim',
                    data: cinemaMovieCounts,
                    backgroundColor: getColors(cinemaMovieLabels.length).map(c => c + 'CC'),
                    borderColor: getColors(cinemaMovieLabels.length),
                    borderWidth: 1.5,
                    borderRadius: 6,
                    maxBarThickness: 18,
                }]
            },
            options: {
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', stepSize: 1, precision: 0 },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: '#e5e7eb', font: { size: 10 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(148,163,184,0.5)',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    // ====== 4a. Bar chart: khách xem theo rạp ======
    const bookingCinemaCtx = document.getElementById('bookingsByCinemaChart');
    if (bookingCinemaCtx && cinemaBookingLabels.length) {
        new Chart(bookingCinemaCtx, {
            type: 'bar',
            data: {
                labels: cinemaBookingLabels,
                datasets: [{
                    label: 'Lượt đặt vé',
                    data: cinemaBookingCounts,
                    backgroundColor: getColors(cinemaBookingLabels.length).map(c => c + 'CC'),
                    borderColor: getColors(cinemaBookingLabels.length),
                    borderWidth: 1.5,
                    borderRadius: 6,
                    maxBarThickness: 26,
                }]
            },
            options: {
                maintainAspectRatio: false,
                indexAxis: 'x',
                scales: {
                    x: {
                        ticks: { color: '#e5e7eb', font: { size: 10 } },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { color: '#9ca3af', stepSize: 1, precision: 0 },
                        grid: { color: gridColor }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(148,163,184,0.5)',
                        borderWidth: 1,
                    }
                }
            }
        });
    }

    // ====== 4b. Horizontal bar: khách xem theo phim ======
    const bookingMovieCtx = document.getElementById('bookingsByMovieChart');
    if (bookingMovieCtx && movieBookingLabels.length) {
        new Chart(bookingMovieCtx, {
            type: 'bar',
            data: {
                labels: movieBookingLabels,
                datasets: [{
                    label: 'Lượt đặt vé',
                    data: movieBookingCounts,
                    backgroundColor: getColors(movieBookingLabels.length).map(c => c + 'CC'),
                    borderColor: getColors(movieBookingLabels.length),
                    borderWidth: 1.5,
                    borderRadius: 6,
                    maxBarThickness: 20,
                }]
            },
            options: {
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        ticks: { color: '#9ca3af', stepSize: 1, precision: 0 },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: '#e5e7eb', font: { size: 10 } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.95)',
                        borderColor: 'rgba(148,163,184,0.5)',
                        borderWidth: 1,
                    }
                }
            }
        });
    }
});
</script>
@endsection
