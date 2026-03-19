@extends('layouts.layout_public')

@section('content')

<div class="bg-white font-kanit">

    <section class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-slate-50">
        <div
            class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-3xl opacity-70">
        </div>
        <div
            class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[400px] h-[400px] bg-purple-50 rounded-full blur-3xl opacity-50">
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm text-blue-600 text-xs font-bold mb-8 border border-blue-50">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-soft-glow absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-40"></span>
                            <span
                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 animate-breathe"></span>
                        </span>
                        <span class="tracking-wide uppercase">NEW: คอร์ส AI For Business เปิดตัวแล้ว!</span>
                    </div>

                    <h3 class="text-3xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-10" style="font-size: 35px">
                        อัปเกรดทักษะ <br> <span
                            class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-500"style="font-size: 60px">สร้างอนาคตใหม่</span>
                    </h3>
                    <p class="text-xl text-slate-500 mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        รวมคอร์สออนไลน์คุณภาพจากผู้เชี่ยวชาญระดับแนวหน้า ให้คุณเก่งขึ้นได้ในทุกๆ วัน
                    </p>

                    <div class="max-w-xl mx-auto lg:mx-0 mb-10">
                        <form action="#" class="relative group">
                            <input type="text" placeholder="ค้นหาคอร์สที่คุณสนใจ... (เช่น Laravel, Design)"
                                class="w-full bg-white border-2 border-transparent shadow-2xl shadow-blue-100 rounded-2xl py-5 pl-14 pr-6 focus:border-blue-500 outline-none transition-all text-slate-700">
                            <i
                                class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-xl group-focus-within:text-blue-600 transition-colors"></i>
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-200">ค้นหา</button>
                        </form>
                    </div>

                    <div class="flex items-center gap-6 justify-center lg:justify-start">
                        <div class="flex -space-x-3">
                            @for($i=1; $i<=4; $i++) <img class="w-12 h-12 rounded-full border-4 border-white shadow-sm"
                                src="https://i.pravatar.cc/100?u={{$i}}" alt="">
                                @endfor
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-black text-slate-800 tracking-tight">+15,000 นักเรียน</p>
                            <div class="flex text-yellow-400 text-xs mt-0.5">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 relative">
                    <div
                        class="relative z-10 rounded-[3rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] border-[16px] border-white">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1471&auto=format&fit=crop"
                            class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/20 to-transparent"></div>
                    </div>
                    <div
                        class="absolute -bottom-6 -right-6 bg-white p-6 rounded-[2rem] shadow-2xl z-20 hidden lg:block border border-slate-50 animate-float">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 text-xl animate-breathe">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase">Trending</p>
                                <p class="text-lg font-black text-slate-800">Hot Course</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex items-center gap-4 mb-10">
                <span class="w-12 h-1 bg-blue-600 rounded-full"></span>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">ข่าวกิจกรรมและโปรโมชั่น</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group cursor-pointer">
                    <div class="rounded-3xl overflow-hidden mb-5 aspect-video relative">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute top-4 left-4 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                            สัมมนาฟรี</div>
                    </div>
                    <h4 class="font-bold text-slate-800 text-xl mb-2 group-hover:text-blue-600 transition">
                        เจาะลึกเทรนด์การพัฒนาเว็บปี 2026</h4>
                    <p class="text-slate-500 text-sm">พบกับกูรูระดับโลกที่จะมาแชร์ประสบการณ์ในวันอาทิตย์ที่ 15
                        มีนาคมนี้...</p>
                </div>
                <div class="group cursor-pointer">
                    <div class="rounded-3xl overflow-hidden mb-5 aspect-video relative">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute top-4 left-4 bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                            Promotion</div>
                    </div>
                    <h4 class="font-bold text-slate-800 text-xl mb-2 group-hover:text-blue-600 transition">Summer Sale
                        ลดแรง 50%!</h4>
                    <p class="text-slate-500 text-sm">คอร์สหมวดธุรกิจและภาษา ลดทันทีครึ่งราคา เมื่อกรอกโค้ด SUMMER2026
                    </p>
                </div>
                <div class="group cursor-pointer">
                    <div class="rounded-3xl overflow-hidden mb-5 aspect-video relative">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=800&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div
                            class="absolute top-4 left-4 bg-purple-600 text-white text-[10px] font-bold px-3 py-1 rounded-full">
                            New Course</div>
                    </div>
                    <h4 class="font-bold text-slate-800 text-xl mb-2 group-hover:text-blue-600 transition">
                        เปิดตัวคอร์สถ่ายภาพมืออาชีพ</h4>
                    <p class="text-slate-500 text-sm">เรียนรู้การใช้กล้องและเทคนิคการจัดแสงตั้งแต่พื้นฐานจนรับงานได้จริง
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="courses" class="py-24 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">คอร์สเรียนยอดนิยม</h2>
                    <p class="text-slate-500">เลือกเรียนจากคอร์สที่ได้รับการรีวิวดีที่สุดจากนักเรียนของเรา</p>
                </div>
                <div class="flex bg-white p-2 rounded-[1.5rem] shadow-sm border border-slate-100">
                    <button
                        class="px-8 py-2.5 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all">คอร์สทั้งหมด</button>
                    <button
                        class="px-8 py-2.5 text-slate-500 font-bold hover:text-blue-600 transition-colors">มาใหม่</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @forelse($featuredCourses as $course)
                <div
                    class="group bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] transition-all duration-500 relative">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('storage/'.$course->thumbnail) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-5 left-5">
                            <span
                                class="px-4 py-1.5 bg-white/95 backdrop-blur-md text-slate-800 text-[10px] font-black rounded-xl shadow-sm border border-slate-100">🔥
                                TRENDING</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <span
                                class="text-[10px] font-black text-blue-500 uppercase tracking-[0.1em]">{{ $course->category_name }}</span>
                        </div>
                        <h3
                            class="font-bold text-slate-800 text-lg leading-snug h-14 line-clamp-2 mb-6 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('courses.show', $course->id) }}">{{ $course->title }}</a>
                        </h3>
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <div>
                                {{-- <p class="text-xs text-slate-400 line-through mb-1">฿3,500</p> --}}
                                <p class="text-2xl font-black text-blue-600 tracking-tight">
                                    ฿{{ number_format($course->price ?? 990) }}</p>
                            </div>
                            <a href="{{ route('courses.show', $course->id) }}"
                                class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                                <i class="fas fa-plus text-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <i class="fas fa-book-open text-6xl text-slate-200 mb-5"></i>
                    <p class="text-slate-400 font-bold">ยังไม่มีคอร์สเรียนในขณะนี้</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-24 container mx-auto px-6">
        <div
            class="bg-gradient-to-br from-blue-700 to-indigo-900 rounded-[3.5rem] p-10 md:p-20 relative overflow-hidden shadow-2xl shadow-blue-200">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-blue-400/20 rounded-full blur-3xl"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-16 relative z-10">
                <div class="text-center lg:text-left">
                    <h4 class="text-4xl lg:text-5xl font-black text-white mb-6 leading-tight" style="font-size: 28px">เริ่มต้นการเรียนรู้ 
                        ที่ไม่มีวันสิ้นสุด</h4>
                    <p class="text-blue-100 text-lg mb-10 opacity-80">รับสิทธิพิเศษ Code ส่วนลด 20%
                        และเข้าถึงคอร์สเรียนฟรีกว่า 50 คอร์ส เพียงแค่สมัครสมาชิกวันนี้</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}"
                            class="bg-white text-blue-900 px-10 py-5 rounded-2xl font-black hover:bg-blue-50 transition-all text-center">สมัครสมาชิกฟรี</a>
                        <a href="#"
                            class="border-2 border-white/30 text-white px-10 py-5 rounded-2xl font-black hover:bg-white/10 transition-all text-center">ดูคอร์สฟรี</a>
                    </div>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <div class="bg-white/5 backdrop-blur-xl rounded-[3rem] p-10 border border-white/10 w-full max-w-sm">
                        <div class="text-center mb-8 text-white">
                            <p class="text-sm font-bold opacity-60 uppercase mb-2">Weekly Stats</p>
                            <p class="text-3xl font-black">+1,200 นักเรียนใหม่</p>
                        </div>
                        <div class="space-y-4">
                            @foreach(['Business', 'Design', 'Coding'] as $item)
                            <div
                                class="flex items-center justify-between bg-white/10 p-4 rounded-2xl border border-white/5">
                                <span class="text-white font-bold">{{$item}}</span>
                                <span class="text-blue-300 font-black">↗ 24%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<style>
    .font-kanit {
        font-family: 'Kanit', sans-serif;
    }

    /* 1. Breathing Effect: กระพริบช้าๆ แบบเนียนๆ */
    @keyframes breathe {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(0.9);
        }
    }

    .animate-breathe {
        animation: breathe 3.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* 2. Soft Glow: วงรัศมีเรืองแสงแบบค่อยเป็นค่อยไป */
    @keyframes soft-glow {
        0% {
            transform: scale(0.9);
            opacity: 0.8;
        }

        100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }

    .animate-soft-glow {
        animation: soft-glow 3s ease-out infinite;
    }

    /* 3. Float: ขยับขึ้นลงแบบนุ่มนวล */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-12px);
        }
    }

    .animate-float {
        animation: float 5s ease-in-out infinite;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

</style>
@endsection
