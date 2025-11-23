{{-- resources/views/frontend/pages/contact.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-10 lg:py-16">
            {{-- Breadcrumb / Title --}}
            <div class="mb-8">
                <p class="text-sm text-slate-400 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-emerald-400 transition">Trang chủ</a>
                    <span class="mx-2">/</span>
                    <span>Liên hệ</span>
                </p>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight">
                    Liên hệ với chúng tôi
                </h1>
                <p class="mt-2 text-slate-400 max-w-2xl">
                    Nếu bạn có bất kỳ câu hỏi, góp ý hay cần hỗ trợ,
                    có thể liên hệ trực tiếp qua thông tin bên dưới hoặc gửi form.
                </p>
            </div>

            {{-- Info + Form --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                {{-- Thông tin liên hệ --}}
                <div class="lg:col-span-1">
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-6 shadow-lg shadow-emerald-500/5">
                        <h2 class="text-xl font-semibold mb-2">Thông tin liên hệ</h2>
                        <p class="text-sm text-slate-400">
                            Bạn có thể liên hệ trực tiếp qua email, số điện thoại
                            hoặc gửi form bên cạnh, tao sẽ phản hồi sớm nhất có thể.
                        </p>

                        <div class="space-y-4 pt-2">
                            <div class="flex gap-3 items-start">
                                <div class="mt-1">
                                    📧
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Email</p>
                                    <a href="mailto:huynguyenznskt@gmail.com"
                                       class="text-sm md:text-base font-medium text-emerald-400 hover:underline break-all">
                                        huynguyenznskt@gmail.com
                                    </a>
                                </div>
                            </div>

                            <div class="flex gap-3 items-start">
                                <div class="mt-1">
                                    📱
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Số điện thoại</p>
                                    <a href="tel:0825485710"
                                       class="text-sm md:text-base font-medium hover:text-emerald-400 transition">
                                        0825 485 710
                                    </a>
                                </div>
                            </div>

                            <div class="flex gap-3 items-start">
                                <div class="mt-1">
                                    📍
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-400">Địa chỉ</p>
                                    <p class="text-sm md:text-base">
                                        Hà Nội, Việt Nam
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{-- (Có thể chỉnh lại địa chỉ cụ thể sau) --}}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-800">
                            <p class="text-xs text-slate-500">
                                Thời gian phản hồi: trong giờ hành chính,
                                nhưng nếu on thì có thể trả lời nhanh hơn 😎
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form liên hệ --}}
                <div class="lg:col-span-2">
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-lg shadow-emerald-500/5">
                        <h2 class="text-xl font-semibold mb-4">Gửi tin nhắn</h2>

                        {{-- Nếu sau này mày muốn xử lý form thật, đổi action + method về route của mày --}}
                        <form action="#" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" for="name">
                                        Họ và tên
                                    </label>
                                    <input type="text" id="name" name="name"
                                           class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="Nhập họ tên">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1" for="email">
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email"
                                           class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="example@gmail.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" for="phone">
                                        Số điện thoại
                                    </label>
                                    <input type="text" id="phone" name="phone"
                                           class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="SĐT liên hệ">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1" for="subject">
                                        Tiêu đề
                                    </label>
                                    <input type="text" id="subject" name="subject"
                                           class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="Tiêu đề tin nhắn">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1" for="message">
                                    Nội dung
                                </label>
                                <textarea id="message" name="message" rows="5"
                                          class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                          placeholder="Note..."></textarea>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <p class="text-xs text-slate-500">
                                    Bằng việc gửi tin nhắn, bạn đồng ý cho chúng tôi liên hệ lại qua email/SĐT.
                                </p>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium bg-gradient-to-r from-emerald-500 to-cyan-500 text-slate-950 shadow-lg hover:shadow-emerald-500/30 hover:opacity-90 transition">
                                    <span>Gửi tin nhắn</span>
                                    <span>✉️</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-3">Bản đồ</h2>
                <div class="rounded-2xl overflow-hidden border border-slate-800 shadow-lg shadow-emerald-500/5">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.848796118761!2d105.78471531533287!3d21.039272792807414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab3e1c8f1a6f%3A0x9e4b9a2e4c9a!2zSMOgIE7hu5lpLCBIw6AgTuG7mWksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
                        width="100%"
                        height="380"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Nếu có địa chỉ cụ thể (tên đường, số nhà), tao chỉnh lại link map chuẩn theo địa chỉ đó sau.
                </p>
            </div>
        </div>
    </div>
@endsection
