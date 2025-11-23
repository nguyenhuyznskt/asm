{{-- resources/views/frontend/pages/about.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Giới thiệu')

@section('content')
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-10 lg:py-16">
            {{-- Breadcrumb / Title --}}
            <div class="mb-8">
                <p class="text-sm text-slate-400 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Trang chủ</a>
                    <span class="mx-2">/</span>
                    <span>Giới thiệu</span>
                </p>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
                    Giới thiệu về rạp phim Laravel Cinema
                </h1>
                <p class="mt-2 text-slate-400 max-w-2xl">
                    Laravel Cinema là hệ thống đặt vé xem phim online, giúp mày lựa chọn suất chiếu,
                    ghế ngồi và thanh toán một cách nhanh chóng, tiện lợi và hiện đại.
                </p>
            </div>

            {{-- Intro + Highlight --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-lg shadow-emerald-500/5 space-y-4">
                        <h2 class="text-2xl font-semibold">Trải nghiệm điện ảnh hiện đại</h2>
                        <p class="text-sm md:text-base text-slate-300">
                            Rạp được thiết kế theo phong cách hiện đại, màn hình lớn chuẩn,
                            hệ thống âm thanh sống động, ghế ngồi êm ái. Toàn bộ quy trình từ
                            chọn phim, chọn rạp, chọn ghế đến xuất vé đều được số hóa.
                        </p>
                        <p class="text-sm md:text-base text-slate-300">
                            Website đặt vé giúp mày:
                        </p>
                        <ul class="list-disc list-inside text-sm md:text-base text-slate-300 space-y-1">
                            <li>Chọn rạp, chọn suất chiếu nhanh như Momo.</li>
                            <li>Xem thông tin phim, trailer, đánh giá chi tiết.</li>
                            <li>Chọn ghế trực quan trên sơ đồ phòng chiếu.</li>
                            <li>Lưu lịch sử đặt vé, xem lại vé bất kỳ lúc nào.</li>
                        </ul>
                        <p class="text-sm md:text-base text-slate-300">
                            Mục tiêu là mang đến cho mày trải nghiệm đặt vé nhẹ nhàng, ít thao tác
                            nhưng vẫn đầy đủ thông tin.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="relative overflow-hidden rounded-2xl border border-slate-800 shadow-lg shadow-emerald-500/10">
                        <img
                            src="https://images.pexels.com/photos/799152/pexels-photo-799152.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Không gian phòng chiếu"
                            class="w-full h-64 md:h-72 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <p class="text-xs uppercase tracking-[0.16em] text-slate-300">Không gian rạp</p>
                            <p class="text-lg font-semibold">Phòng chiếu hiện đại, màn hình lớn</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-3 text-center">
                            <div class="text-xl">🎬</div>
                            <div class="mt-1 text-xs uppercase text-slate-400 tracking-wide">Số phòng chiếu</div>
                            <div class="text-lg font-semibold">05+</div>
                        </div>
                        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-3 text-center">
                            <div class="text-xl">🍿</div>
                            <div class="mt-1 text-xs uppercase text-slate-400 tracking-wide">Suất chiếu / ngày</div>
                            <div class="text-lg font-semibold">20+</div>
                        </div>
                        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-3 text-center">
                            <div class="text-xl">⭐</div>
                            <div class="mt-1 text-xs uppercase text-slate-400 tracking-wide">Trải nghiệm</div>
                            <div class="text-lg font-semibold">4.8/5</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Tầm nhìn / Ưu điểm --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-lg shadow-emerald-500/5">
                    <h3 class="text-lg font-semibold mb-2">Đặt vé online tiện lợi</h3>
                    <p class="text-sm text-slate-300">
                        Toàn bộ thao tác được tối ưu: chọn rạp, chọn giờ, chọn ghế,
                        xem giá vé, combo nước bắp… chỉ trong vài bước.
                    </p>
                </div>
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-lg shadow-emerald-500/5">
                    <h3 class="text-lg font-semibold mb-2">Giao diện trực quan</h3>
                    <p class="text-sm text-slate-300">
                        Giao diện tối, hiện đại, tập trung vào nội dung phim
                        và trải nghiệm chọn ghế/đặt vé thân thiện trên cả desktop và mobile.
                    </p>
                </div>
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 shadow-lg shadow-emerald-500/5">
                    <h3 class="text-lg font-semibold mb-2">Quản lý vé & tài khoản</h3>
                    <p class="text-sm text-slate-300">
                        Lịch sử đặt vé, thông tin vé, suất chiếu… được lưu lại theo tài khoản,
                        giúp mày dễ tra cứu khi đến rạp.
                    </p>
                </div>
            </div>

            {{-- Gallery ảnh rạp --}}
            <div class="mb-12">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold">Một vài hình ảnh về rạp</h2>
                    <p class="text-xs text-slate-400">
                        (Ảnh minh hoạ, mày có thể thay bằng ảnh thật sau)
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="group relative overflow-hidden rounded-2xl border border-slate-800">
                        <img
                            src="https://images.pexels.com/photos/799155/pexels-photo-799155.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Sảnh chờ rạp phim"
                            class="w-full h-56 object-cover group-hover:scale-105 transition">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent opacity-80"></div>
                        <div class="absolute bottom-3 left-3">
                            <p class="text-xs uppercase tracking-wide text-slate-300">Sảnh chờ</p>
                            <p class="text-sm font-semibold">Không gian check-in</p>
                        </div>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl border border-slate-800">
                        <img
                            src="https://images.pexels.com/photos/799137/pexels-photo-799137.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Quầy bắp nước"
                            class="w-full h-56 object-cover group-hover:scale-105 transition">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent opacity-80"></div>
                        <div class="absolute bottom-3 left-3">
                            <p class="text-xs uppercase tracking-wide text-slate-300">Quầy bắp nước</p>
                            <p class="text-sm font-semibold">Combo bắp nước đa dạng</p>
                        </div>
                    </div>

                    <div class="group relative overflow-hidden rounded-2xl border border-slate-800">
                        <img
                            src="https://images.pexels.com/photos/799142/pexels-photo-799142.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Phòng chiếu"
                            class="w-full h-56 object-cover group-hover:scale-105 transition">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent opacity-80"></div>
                        <div class="absolute bottom-3 left-3">
                            <p class="text-xs uppercase tracking-wide text-slate-300">Phòng chiếu</p>
                            <p class="text-sm font-semibold">Ghế ngồi êm, tầm nhìn đẹp</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Call to action --}}
            <div class="mt-4 bg-gradient-to-r from-emerald-500/10 via-cyan-500/10 to-purple-500/10 border border-slate-800 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-semibold mb-1">
                        Sẵn sàng đặt vé?
                    </h2>
                    <p class="text-sm md:text-base text-slate-200">
                        Khám phá phim đang chiếu, chọn rạp, chọn ghế và đặt vé ngay trên website.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('movies.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium bg-gradient-to-r from-emerald-500 to-cyan-500 text-slate-950 shadow-lg hover:shadow-emerald-500/30 hover:opacity-90 transition">
                        <span>Xem phim đang chiếu</span>
                        <span>🎬</span>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium border border-emerald-500/60 text-emerald-400 hover:bg-emerald-500/10 transition">
                        <span>Liên hệ rạp</span>
                        <span>📞</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
