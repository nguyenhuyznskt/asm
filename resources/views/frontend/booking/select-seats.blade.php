@extends('frontend.layouts.app')

@section('title', 'Chọn ghế - ' . $showtime->movie->title)

@section('content')

    <h1 class="text-2xl font-bold mb-3">Chọn ghế - {{ $showtime->movie->title }}</h1>

    <p class="text-sm mb-4 text-slate-300">
        Rạp: {{ $showtime->room->cinema->name }} – {{ $showtime->room->name }} <br>
        Suất: {{ $showtime->start_time->format('d/m/Y H:i') }} <br>
        Giá vé: {{ number_format($showtime->price) }}đ
    </p>

    <div class="bg-slate-900 p-4 rounded-xl mb-6">

        <p class="text-center text-xs text-slate-400 mb-2">Màn hình</p>
        <div class="h-1 bg-slate-500 mb-4"></div>
        @if ($errors->any())
        <div class="mb-4 text-red-400 text-sm">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif
    
    @if (session('success'))
        <div class="mb-4 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    
        <form method="POST" action="{{ route('booking.store', $showtime) }}">
            @csrf
            <div class="flex justify-center">
                <div class="space-y-2">

                    @foreach($showtime->room->seats->groupBy('row') as $row => $seats)

                        <div class="flex items-center gap-2">
                            <span class="w-6 text-xs text-slate-400">{{ $row }}</span>

                            @foreach($seats as $seat)
                                @php $isBooked = in_array($seat->id, $bookedSeatIds); @endphp

                                <label
                                    class="seat-label w-7 h-7 text-[10px] flex items-center justify-center rounded cursor-pointer
                                           {{ $isBooked ? 'bg-red-600 opacity-60 cursor-not-allowed' : 'bg-slate-700 hover:bg-emerald-500' }}"
                                    data-seat="{{ $seat->id }}">
                                    @unless($isBooked)
                                        <input type="checkbox" name="seats[]" value="{{ $seat->id }}" class="hidden seat-checkbox">
                                    @endunless
                                    {{ $seat->number }}
                                </label>

                            @endforeach
                        </div>

                    @endforeach

                </div>
            </div>

            <div class="mt-6 grid md:grid-cols-3 gap-4">

                <div class="md:col-span-2">
                    <input type="text" name="customer_name" required placeholder="Họ tên"
                        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 mb-2">

                    <input type="email" name="customer_email" placeholder="Email"
                        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700 mb-2">

                    <input type="text" name="customer_phone" placeholder="Số điện thoại"
                        class="w-full px-3 py-2 rounded bg-slate-900 border border-slate-700">
                </div>
                

                <div>
                    <button type="submit"
                        class="w-full px-4 py-2 rounded bg-emerald-500 hover:bg-emerald-600 font-semibold">
                        Xác nhận đặt vé
                    </button>
                </div>

            </div>
            {{-- Combo bắp nước --}}
@if(isset($combos) && $combos->count())
<div class="mt-6">
    <h2 class="text-lg font-semibold mb-2">Combo bắp nước</h2>

    @foreach($combos as $combo)
        <div class="flex items-center gap-3 bg-slate-900 border border-slate-800 rounded-lg p-3 mb-2">
            <div class="flex-1">
                <div class="font-medium text-sm">{{ $combo->name }}</div>
                @if($combo->description)
                    <div class="text-xs text-slate-400">{{ $combo->description }}</div>
                @endif
                <div class="text-sm text-emerald-400 mt-1">
                    {{ number_format($combo->price) }}đ
                </div>
            </div>

            <div class="flex items-center gap-1">
                <button type="button"
                        class="combo-minus px-2 py-1 text-lg bg-slate-800 rounded"
                        data-id="{{ $combo->id }}">
                    -
                </button>

                <input type="number"
                       name="combo[{{ $combo->id }}]"
                       value="0"
                       min="0"
                       class="combo-qty w-12 text-center px-2 py-1 bg-slate-900 border border-slate-700 rounded text-sm"
                       data-price="{{ $combo->price }}"
                       data-id="{{ $combo->id }}">

                <button type="button"
                        class="combo-plus px-2 py-1 text-lg bg-slate-800 rounded"
                        data-id="{{ $combo->id }}">
                    +
                </button>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- Tổng tiền --}}
<div class="mt-6 bg-slate-900 border border-slate-800 rounded-lg p-3">
<h2 class="text-lg font-semibold mb-2">Tổng tiền</h2>

<p class="text-sm text-slate-300">
    Vé: <span id="ticket-count">0</span> x {{ number_format($showtime->price) }}đ
    = <span id="ticket-total">0</span>đ
</p>

<p class="text-sm text-slate-300 mt-1">
    Combo: <span id="combo-total">0</span>đ
</p>

<p class="text-base font-bold mt-2">
    Tổng cộng: <span id="grand-total">0</span>đ
</p>

<input type="hidden" id="ticket-price" value="{{ $showtime->price }}">
</div>


        </form>

    </div>



    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ===== TÍNH TỔNG TIỀN & ĐỔI MÀU GHẾ =====
        const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
        const ticketPrice = parseInt(document.getElementById('ticket-price').value || '0');
    
        const ticketCountEl = document.getElementById('ticket-count');
        const ticketTotalEl = document.getElementById('ticket-total');
        const comboTotalEl  = document.getElementById('combo-total');
        const grandTotalEl  = document.getElementById('grand-total');
    
        const comboQtyInputs = document.querySelectorAll('.combo-qty');
        const comboPlusBtns  = document.querySelectorAll('.combo-plus');
        const comboMinusBtns = document.querySelectorAll('.combo-minus');
    
        function formatNumber(n) {
            return n.toLocaleString('vi-VN');
        }
    
        function calcTicketTotal() {
            const count = Array.from(seatCheckboxes).filter(cb => cb.checked).length;
            const total = count * ticketPrice;
    
            ticketCountEl.textContent = count;
            ticketTotalEl.textContent = formatNumber(total) + 'đ';
    
            return total;
        }
    
        function calcComboTotal() {
            let total = 0;
            comboQtyInputs.forEach(input => {
                const qty   = parseInt(input.value || '0');
                const price = parseInt(input.dataset.price || '0');
                total += qty * price;
            });
    
            comboTotalEl.textContent = formatNumber(total) + 'đ';
            return total;
        }
    
        function updateGrandTotal() {
            const t = calcTicketTotal();
            const c = calcComboTotal();
            grandTotalEl.textContent = formatNumber(t + c) + 'đ';
        }
    
        // ===== ĐỔI MÀU GHẾ KHI CHỌN/BỎ =====
        seatCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const label = cb.closest('.seat-label');
                if (label) {
                    if (cb.checked) {
                        label.classList.remove('bg-slate-700', 'hover:bg-emerald-500');
                        label.classList.add('bg-emerald-500', 'text-slate-950', 'font-semibold');
                    } else {
                        label.classList.remove('bg-emerald-500', 'text-slate-950', 'font-semibold');
                        label.classList.add('bg-slate-700');
                    }
                }
    
                updateGrandTotal();
            });
        });
    
        // ===== NÚT + / - COMBO =====
        comboPlusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const input = document.querySelector(`.combo-qty[data-id="${id}"]`);
                if (!input) return;
                input.value = parseInt(input.value || '0') + 1;
                updateGrandTotal();
            });
        });
    
        comboMinusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const input = document.querySelector(`.combo-qty[data-id="${id}"]`);
                if (!input) return;
                const current = parseInt(input.value || '0');
                input.value = current > 0 ? current - 1 : 0;
                updateGrandTotal();
            });
        });
    
        comboQtyInputs.forEach(input => {
            input.addEventListener('input', () => {
                if (parseInt(input.value || '0') < 0) input.value = 0;
                updateGrandTotal();
            });
        });
    
        // tính lần đầu
        updateGrandTotal();
    });
    </script>
    @endpush
    
    
    



@endsection