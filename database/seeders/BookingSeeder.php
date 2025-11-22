<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Showtime;
use Illuminate\Database\Seeder;


class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        // Lấy tất cả suất chiếu, kèm phòng & danh sách ghế
        $showtimes = Showtime::with('room.seats')->get();

        foreach ($showtimes as $showtime) {

            // danh sách id ghế trong phòng này
            $seatIds = $showtime->room->seats->pluck('id')->toArray();

            // ghế đã book trong suất này
            $bookedSeatIds = [];

            // mỗi suất chiếu tạo ngẫu nhiên 0–3 booking
            $bookingCount = rand(0, 3);

            for ($i = 0; $i < $bookingCount; $i++) {

                // chọn ngẫu nhiên 1–4 ghế cho booking này
                $seatCount = rand(1, 4);

                // ghế còn trống (chưa bị booking khác trong cùng showtime lấy mất)
                $availableSeats = array_values(array_diff($seatIds, $bookedSeatIds));

                if (count($availableSeats) == 0) {
                    break; // hết ghế trống thì dừng
                }

                // pick ghế ngẫu nhiên
                $chosenSeats = collect($availableSeats)
                    ->shuffle()
                    ->take($seatCount)
                    ->all();

                $totalPrice = count($chosenSeats) * $showtime->price;

                // tạo booking
                $booking = Booking::create([
                    'showtime_id'    => $showtime->id,
                    'customer_name'  => $faker->name(),
                    'customer_email' => $faker->safeEmail(),
                    'customer_phone' => $faker->phoneNumber(),
                    'total_price'    => $totalPrice,
                    'status'         => 'confirmed',
                ]);

                // tạo record booking_seats
                foreach ($chosenSeats as $seatId) {
                    BookingSeat::create([
                        'booking_id' => $booking->id,
                        'seat_id'    => $seatId,
                        'price'      => $showtime->price,
                    ]);

                    $bookedSeatIds[] = $seatId; // đánh dấu ghế đã bị đặt trong suất này
                }
            }
        }
    }
}
