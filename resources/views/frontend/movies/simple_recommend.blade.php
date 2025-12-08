@extends('frontend.layouts.app')

@section('title', 'Gợi ý phim cho bạn')

@push('styles')
<style>
    .recommend-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding: 24px 0 40px;
    }

    .recommend-header {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 22px;
    }

    .recommend-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid rgba(148,163,184,0.35);
        background: rgba(15,23,42,0.9);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #9ca3af;
    }

    .recommend-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: radial-gradient(circle at center, #22c55e, #15803d);
        box-shadow: 0 0 12px rgba(34,197,94,0.9);
    }

    .recommend-title {
        font-size: clamp(24px, 3vw, 30px);
        font-weight: 800;
        letter-spacing: 0.02em;
        background-image: linear-gradient(to right, #22c55e, #0ea5e9, #6366f1);
        -webkit-background-clip: text;
        color: transparent;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .recommend-title span.emoji {
        font-size: 22px;
        filter: drop-shadow(0 0 10px rgba(34,197,94,0.7));
    }

    .recommend-subtitle {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
        max-width: 520px;
    }

    .recommend-subtitle span.highlight {
        color: #e5e7eb;
        font-weight: 500;
    }

    .recommend-info-strip {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 11px;
    }

    .info-chip {
        border-radius: 999px;
        padding: 4px 10px;
        border: 1px solid rgba(55,65,81,0.9);
        background: rgba(15,23,42,0.92);
        color: #9ca3af;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .info-chip-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .16em;
        color: #6b7280;
    }

    /* CARD */
    .movie-card {
        position: relative;
        border-radius: 18px;
        border: 1px solid rgba(148,163,184,0.3);
        background: rgba(15,23,42,0.96);
        padding: 12px;
        display: flex;
        gap: 12px;
        overflow: hidden;
        opacity: 0;
        transform: translateY(8px);
        transition:
            transform .18s ease-out,
            box-shadow .18s ease-out,
            border-color .18s ease-out,
            opacity .22s ease-out;
        backdrop-filter: blur(14px);
    }

    .movie-card.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .movie-card.is-visible:hover {
        transform: translateY(-3px);
        border-color: rgba(148,163,184,0.7);
        box-shadow: 0 14px 38px rgba(15,23,42,0.9);
    }

    .movie-poster-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .movie-poster {
        width: 86px;
        height: 125px;
        border-radius: 13px;
        object-fit: cover;
        background: #020617;
        border: 1px solid rgba(15,23,42,0.9);
        box-shadow:
            0 10px 25px rgba(15,23,42,0.95),
            0 0 0 1px rgba(15,23,42,1);
    }

    .movie-poster-badge {
        position: absolute;
        bottom: 6px;
        left: 6px;
        padding: 3px 7px;
        border-radius: 999px;
        background: rgba(15,23,42,0.96);
        border: 1px solid rgba(148,163,184,0.6);
        font-size: 9px;
        color: #e5e7eb;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .movie-poster-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 6px rgba(34,197,94,0.9);
    }

    .movie-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .movie-title-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .movie-title {
        font-size: 15px;
        font-weight: 600;
        color: #e5e7eb;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .movie-age-badge {
        flex-shrink: 0;
        margin-left: auto;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 7px;
        border-radius: 999px;
        border: 1px solid rgba(156,163,175,0.7);
        color: #e5e7eb;
        background: rgba(15,23,42,0.9);
    }

    .movie-meta {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 6px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .movie-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(17,24,39,0.85);
        border: 1px solid rgba(55,65,81,0.9);
    }

    .movie-meta svg {
        width: 10px;
        height: 10px;
    }

    .movie-desc {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 6px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .movie-footer {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .movie-footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: #6b7280;
    }

    .movie-footer-left a {
        color: #9ca3af;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .movie-footer-left a:hover {
        color: #e5e7eb;
    }

    .movie-footer-left svg {
        width: 12px;
        height: 12px;
    }

    .btn-book {
        border-radius: 999px;
        padding: 7px 16px;
        font-size: 11px;
        font-weight: 600;
        background-image: linear-gradient(135deg, #6366f1, #22c55e);
        border: none;
        color: white;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow:
            0 10px 25px rgba(79,70,229,0.45),
            0 0 0 1px rgba(15,23,42,1);
        position: relative;
        overflow: hidden;
    }

    .btn-book::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            120deg,
            rgba(255,255,255,0.25),
            transparent 45%,
            transparent 55%,
            rgba(255,255,255,0.2)
        );
        transform: translateX(-120%);
        transition: transform .55s cubic-bezier(.22,.61,.36,1);
    }

    .btn-book:hover::before {
        transform: translateX(120%);
    }

    .btn-book span.icon {
        display: inline-flex;
        padding: 3px 6px;
        border-radius: 999px;
        background: rgba(15,23,42,0.38);
        font-size: 10px;
    }

    .btn-book span.label {
        position: relative;
        z-index: 1;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    /* EMPTY STATE */
    .recommend-empty {
        margin-top: 18px;
        padding: 16px 14px;
        border-radius: 16px;
        border: 1px dashed rgba(55,65,81,0.9);
        background: rgba(15,23,42,0.9);
        font-size: 13px;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .recommend-empty-icon {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid rgba(55,65,81,1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top, rgba(148,163,184,0.2), rgba(15,23,42,1));
        font-size: 16px;
    }

    .recommend-empty strong {
        color: #e5e7eb;
        font-weight: 500;
    }

    @media (max-width: 480px) {
        .movie-card {
            padding: 10px;
        }
        .movie-poster {
            width: 78px;
            height: 110px;
        }
        .movie-desc {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="recommend-wrapper">
    <div class="recommend-header">
        <div class="recommend-pill">
            <span class="recommend-pill-dot"></span>
            <span>Dành riêng cho bạn</span>
        </div>

        <h1 class="recommend-title">
            Gợi ý phim 
            <span class="emoji">🎬</span>
        </h1>

        <p class="recommend-subtitle">
            @if($favoriteGenreId)
                Dựa trên thể loại <span class="highlight">mày hay xem nhất</span> và độ hot của phim trong rạp.
            @else
                Dựa trên những phim <span class="highlight">đang hot & mới nhất</span> trong rạp.
            @endif
        </p>

        <div class="recommend-info-strip">
            <div class="info-chip">
                <span class="info-chip-label">Chế độ</span>
                <span>AI pick phim hợp gu</span>
            </div>
            <div class="info-chip">
                <span class="info-chip-label">Cập nhật</span>
                <span>Liên tục theo lượt đặt</span>
            </div>
            <div class="info-chip">
                <span class="info-chip-label">Mẹo</span>
                <span>Nhấn “Đặt vé ngay” để chọn suất</span>
            </div>
        </div>
    </div>

    @if($recommendedMovies->isEmpty())
        <div class="recommend-empty">
            <div class="recommend-empty-icon">🍿</div>
            <div>
                <strong>Hiện chưa có phim để gợi ý.</strong><br>
                Mày quay lại sau hoặc thử xem danh sách phim đang chiếu ở mục <strong>Phim</strong> nhé.
            </div>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($recommendedMovies as $movie)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img
                            src="{{ $movie->poster_url ?? 'https://via.placeholder.com/200x300?text=No+Poster' }}"
                            alt="{{ $movie->title }}"
                            class="movie-poster"
                        >
                        @if(isset($movie->total_bookings) && $movie->total_bookings > 0)
                            <div class="movie-poster-badge">
                                <span class="movie-poster-badge-dot"></span>
                                {{ $movie->total_bookings }} lượt đặt
                            </div>
                        @endif
                    </div>

                    <div class="movie-content">
                        <div>
                            <div class="movie-title-row">
                                <div class="movie-title">
                                    {{ $movie->title }}
                                </div>
                                @if(!empty($movie->age_rating))
                                    <div class="movie-age-badge">
                                        {{ $movie->age_rating }}
                                    </div>
                                @endif
                            </div>

                            <div class="movie-meta">
                                @if($movie->genre)
                                    <span>
                                        {{-- icon tag --}}
                                        <svg viewBox="0 0 20 20" fill="none">
                                            <path d="M3 10.5V4.8A1.8 1.8 0 0 1 4.8 3h5.7L17 9.5l-6 6-7-5z" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="7" cy="7" r="1" fill="#9ca3af"/>
                                        </svg>
                                        {{ $movie->genre->name }}
                                    </span>
                                @endif

                                @if($movie->release_date)
                                    <span>
                                        {{-- icon calendar --}}
                                        <svg viewBox="0 0 20 20" fill="none">
                                            <rect x="3" y="4" width="14" height="13" rx="2" stroke="#9ca3af" stroke-width="1.2"/>
                                            <path d="M7 3v3M13 3v3M3 8h14" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round"/>
                                        </svg>
                                        Khởi chiếu: {{ $movie->release_date->format('d/m/Y') }}
                                    </span>
                                @endif

                                @if($movie->rating)
                                    <span>
                                        {{-- icon star --}}
                                        <svg viewBox="0 0 20 20" fill="none">
                                            <path d="M10 2.5 12 7l4.9.4-3.7 3.2 1.1 4.7L10 13.7 5.7 15.3 6.8 10.6 3.1 7.4 8 7z" fill="#facc15" stroke="#eab308" stroke-width=".7"/>
                                        </svg>
                                        {{ number_format($movie->rating, 1) }}/10
                                    </span>
                                @endif
                            </div>

                            @if(!empty($movie->short_description))
                                <p class="movie-desc">
                                    {{ $movie->short_description }}
                                </p>
                            @endif
                        </div>

                        <div class="movie-footer">
                            <div class="movie-footer-left">
                                
                            </div>

                            <a href="{{ route('movies.show', $movie->slug) }}">
                                <button class="btn-book">
                                    <span class="label">Đặt vé ngay</span>
                                    <span class="icon">▶</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.movie-card');

        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('is-visible');
            }, 80 * index);
        });
    });
</script>
@endpush
