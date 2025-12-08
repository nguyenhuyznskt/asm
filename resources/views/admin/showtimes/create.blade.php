@extends('admin.layouts.app')

@section('title', 'Thêm suất chiếu')
@section('page_title', 'Thêm suất chiếu')
@section('page_subtitle', 'Tạo mới lịch chiếu cho phim')

@section('styles')
<style>
    .st-card {
        max-width: 720px;
        background: rgba(15,23,42,0.95);
        border-radius: 18px;
        border: 1px solid #1f2937;
        padding: 20px;
    }
    .st-label {
        display:block;
        font-size:0.7rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#9ca3af;
        margin-bottom:4px;
    }
    .st-input, .st-select {
        width:100%;
        border-radius:12px;
        border:1px solid #374151;
        background:rgba(15,23,42,0.95);
        padding:8px 10px;
        font-size:0.85rem;
        color:#e5e7eb;
        outline:none;
    }
    .st-input:focus, .st-select:focus {
        border-color:#22c55e;
        box-shadow:0 0 0 1px rgba(34,197,94,0.4);
    }
    .st-hint {
        font-size:0.7rem;
        color:#6b7280;
        margin-top:4px;
    }
    .st-btn {
        border-radius:999px;
        font-size:0.75rem;
        padding:6px 12px;
        font-weight:500;
        border:none;
        cursor:pointer;
    }
    .st-btn-primary {
        background:linear-gradient(to right, #22c55e, #0ea5e9);
        color:#020617;
    }
    .st-btn-primary:hover { filter:brightness(1.08); }
    .st-btn-outline {
        border:1px solid #4b5563;
        background:transparent;
        color:#e5e7eb;
    }
    .st-btn-outline:hover {
        background:rgba(31,41,55,0.9);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cinemaSelect = document.getElementById('st-cinema');
    const roomSelect   = document.getElementById('st-room');
    const movieSelect  = document.getElementById('st-movie');
    const dateInput    = document.getElementById('st-date');
    const timeSelect   = document.getElementById('st-start-hour');

    function filterRooms() {
        if (!cinemaSelect || !roomSelect) return;
        const cinemaId = cinemaSelect.value;

        Array.from(roomSelect.options).forEach(opt => {
            if (!opt.dataset.cinema) return; // placeholder
            if (!cinemaId || opt.dataset.cinema === cinemaId) {
                opt.hidden = false;
            } else {
                opt.hidden = true;
                if (opt.selected) opt.selected = false;
            }
        });
    }

    async function loadSlots() {
        if (!movieSelect || !roomSelect || !dateInput || !timeSelect) return;

        const movieId = movieSelect.value;
        const roomId  = roomSelect.value;
        const date    = dateInput.value;

        timeSelect.innerHTML = '';

        if (!movieId || !roomId || !date) {
            const opt = document.createElement('option');
            opt.textContent = 'Chọn phim, phòng và ngày để xem giờ trống';
            opt.disabled = true;
            opt.selected = true;
            timeSelect.appendChild(opt);
            return;
        }

        const url = new URL("{{ route('admin.showtimes.available-slots') }}", window.location.origin);
        url.searchParams.set('movie_id', movieId);
        url.searchParams.set('room_id', roomId);
        url.searchParams.set('show_date', date);

        try {
            const res = await fetch(url.toString());
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();

            const slots = data.slots || [];

            if (slots.length === 0) {
                const opt = document.createElement('option');
                opt.textContent = 'Không còn khung giờ trống trong ngày';
                opt.disabled = true;
                opt.selected = true;
                timeSelect.appendChild(opt);
                return;
            }

            const placeholder = document.createElement('option');
            placeholder.textContent = 'Chọn giờ bắt đầu';
            placeholder.disabled = true;
            placeholder.selected = true;
            timeSelect.appendChild(placeholder);

            slots.forEach(hhmm => {
                const opt = document.createElement('option');
                opt.value = hhmm;
                opt.textContent = hhmm;
                timeSelect.appendChild(opt);
            });

        } catch (e) {
            const opt = document.createElement('option');
            opt.textContent = 'Lỗi tải khung giờ, thử lại';
            opt.disabled = true;
            opt.selected = true;
            timeSelect.appendChild(opt);
        }
    }

    if (cinemaSelect) {
        cinemaSelect.addEventListener('change', () => {
            filterRooms();
            loadSlots();
        });
        filterRooms();
    }

    [movieSelect, roomSelect, dateInput].forEach(el => {
        if (!el) return;
        el.addEventListener('change', loadSlots);
    });
});
</script>
@endsection

@section('content')
<div class="st-card">
    @if($errors->any())
        <div class="mb-4 text-xs text-rose-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.showtimes.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Phim & Rạp --}}
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="st-label">Phim</label>
                <select id="st-movie" name="movie_id" class="st-select">
                    <option value="">-- Chọn phim --</option>
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}" @selected(old('movie_id') == $movie->id)>
                            {{ $movie->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="st-label">Rạp</label>
                <select id="st-cinema" class="st-select">
                    <option value="">-- Tất cả rạp --</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">
                            {{ $cinema->name }}
                        </option>
                    @endforeach
                </select>
                <p class="st-hint">Chọn rạp để lọc danh sách phòng.</p>
            </div>
        </div>

        {{-- Phòng --}}
        <div>
            <label class="st-label">Phòng chiếu</label>
            <select id="st-room" name="room_id" class="st-select">
                <option value="">-- Chọn phòng --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                            data-cinema="{{ $room->cinema_id }}"
                            @selected(old('room_id') == $room->id)>
                        {{ optional($room->cinema)->name }} - Phòng {{ $room->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Ngày (dropdown) + Giờ (dropdown giờ trống) --}}
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="st-label">Ngày chiếu</label>
                @php
                    $today = \Carbon\Carbon::today();
                    $days  = 7; // số ngày cho phép chọn (hôm nay + 6 ngày)
                    $oldDate = old('show_date');
                @endphp
                <select id="st-date" name="show_date" class="st-select">
                    <option value="">-- Chọn ngày --</option>
                    @for($i = 0; $i < $days; $i++)
                        @php
                            $d = $today->copy()->addDays($i);
                            $val = $d->format('Y-m-d');
                        @endphp
                        <option value="{{ $val }}" @selected($oldDate == $val)>
                            {{ $d->format('d/m/Y') }}
                        </option>
                    @endfor
                </select>
                <p class="st-hint">Chỉ cho chọn trong {{ $days }} ngày tới (có thể chỉnh).</p>
            </div>

            <div>
                <label class="st-label">Giờ bắt đầu (chỉ hiện giờ trống)</label>
                <select id="st-start-hour" name="start_hour" class="st-select">
                    <option value="" selected disabled>
                        Chọn phim, phòng và ngày để xem giờ trống
                    </option>
                </select>
                <p class="st-hint">Các giờ đã có phim chiếu sẽ không hiển thị.</p>
            </div>
        </div>

        {{-- Giá vé --}}
        <div>
            <label class="st-label">Giá vé</label>
            <input type="number"
                   name="price"
                   min="0"
                   value="{{ old('price', 80000) }}"
                   class="st-input">
        </div>

        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.showtimes.index') }}"
               class="st-btn st-btn-outline">
                Hủy
            </a>
            <button type="submit" class="st-btn st-btn-primary">
                Tạo suất chiếu
            </button>
        </div>
    </form>
</div>
@endsection
