@extends('frontend.layouts.app')

@section('title', $movie->title)

@section('content')

<div class="grid md:grid-cols-3 gap-6">

    <div>
        <img src="{{ $movie->poster_url }}" class="w-full rounded-xl border border-slate-800">
    </div>

    <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-2">{{ $movie->title }}</h1>

        <p class="text-sm text-slate-300 mb-4">
            Thời lượng: {{ $movie->duration_minutes }} phút • {{ $movie->age_rating }}<br>
            Khởi chiếu: {{ optional($movie->release_date)->format('d/m/Y') }}
        </p>

        <p class="text-sm text-slate-200 mb-4">
            {{ $movie->description }}
        </p>

        <h2 class="text-xl font-semibold mb-3">Suất chiếu</h2>

        @forelse($showtimesByDate as $date => $showtimes)

            <p class="text-sm text-slate-400 mb-1">
                Ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
            </p>

            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($showtimes as $showtime)
                    <a href="{{ route('booking.select-seats', $showtime) }}"
                       class="px-3 py-1 rounded bg-slate-800 hover:bg-emerald-500 text-xs">
                        {{ $showtime->start_time->format('H:i') }}
                        • {{ $showtime->room->cinema->name }}
                        – {{ $showtime->room->name }}
                    </a>
                @endforeach
            </div>

        @empty
            <p class="text-slate-400">Chưa có suất chiếu nào.</p>
        @endforelse
    </div>

</div>


<h2 class="text-xl font-semibold mt-8 mb-4">Đánh giá phim</h2>

@if(session('success'))
    <div class="bg-emerald-500 text-white px-3 py-2 rounded mb-3 text-sm">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('movies.comment', $movie->id) }}" method="POST"
      class="bg-slate-900 p-4 rounded-xl border border-slate-800 space-y-3">
    @csrf

    <input type="text" name="name" placeholder="Tên của bạn"
           class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm" required>

    <textarea name="content" rows="3" placeholder="Nội dung bình luận"
              class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm" required></textarea>

    {{-- rating: dùng hidden + sao clickable --}}
    <input type="hidden" name="rating" id="rating-input" value="5">

    <div class="flex items-center gap-2">
        <label class="text-sm">Đánh giá:</label>

        <div id="star-wrapper" class="flex gap-1">
            @for($i = 1; $i <= 5; $i++)
                <button type="button"
                        class="star-btn text-xl"
                        data-value="{{ $i }}">
                    ★
                </button>
            @endfor
        </div>
    </div>

    <button class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded">
        Gửi đánh giá
    </button>
</form>

<h2 class="text-xl font-semibold mt-8 mb-4">Bình luận</h2>

@forelse($movie->comments()->latest()->get() as $c)
    <div class="border border-slate-800 bg-slate-900 p-3 rounded mb-3">
        <div class="flex items-center justify-between">
            <strong>{{ $c->name }}</strong>
            <span class="text-yellow-400">
                {{ str_repeat('⭐', $c->rating) }}
            </span>
        </div>

        <p class="text-sm text-slate-300 mt-1">{{ $c->content }}</p>

        <p class="text-xs text-slate-500 mt-1">{{ $c->created_at->diffForHumans() }}</p>
    </div>
@empty
    <p class="text-slate-400 text-sm">Chưa có bình luận nào.</p>
@endforelse

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');

    function updateStars(value) {
        starBtns.forEach(btn => {
            const v = parseInt(btn.dataset.value);
            if (v <= value) {
                btn.classList.add('text-yellow-400');
                btn.classList.remove('text-slate-500');
            } else {
                btn.classList.remove('text-yellow-400');
                btn.classList.add('text-slate-500');
            }
        });
    }

    // mặc định 5 sao
    updateStars(parseInt(ratingInput.value) || 5);

    starBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const value = parseInt(btn.dataset.value);
            ratingInput.value = value;
            updateStars(value);
        });
    });
});
</script>
@endpush
