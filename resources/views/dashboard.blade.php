@extends('layouts.layout_public')
@section('content')
<el-dialog>
    <dialog id="dialog" aria-labelledby="dialog-title"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-slate-950/80 backdrop:backdrop-blur-sm">
        <el-dialog-backdrop
            class="fixed inset-0 bg-slate-900/40 transition-opacity data-closed:opacity-0 data-enter:duration-500 data-enter:ease-out data-leave:duration-300 data-leave:ease-in">
        </el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-center justify-center p-4 text-center focus:outline-none sm:p-6">

            <el-dialog-panel class="relative transform overflow-hidden rounded-[2.5rem] bg-slate-900/90 border border-white/10 text-left shadow-2xl transition-all 
                data-closed:translate-y-8 data-closed:opacity-0 data-enter:duration-500 data-enter:ease-out data-leave:duration-300 data-leave:ease-in 
                w-full max-w-7xl">

                <div class="p-8 sm:p-12">
                    <h3 id="dialog-title"
                        class="text-2xl font-bold text-white mb-8 tracking-tight flex items-center gap-3">
                        <i class="fas fa-th-large text-blue-400"></i>
                        เลือกระบบงานที่ต้องการเข้าใช้งาน
                    </h3>

                    <div id="system-1" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                    </div>
                </div>

                <div class="bg-white/5 px-8 py-6 flex justify-end border-t border-white/5">
                    <button type="button" command="close" commandfor="dialog"
                        class="px-6 py-2.5 rounded-2xl bg-white/10 text-sm font-semibold text-white hover:bg-white/20 transition-colors border border-white/10">
                        ปิดหน้าต่าง
                    </button>
                </div>

            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<div class="bg-white">
    <section class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 overflow-hidden bg-slate-50 p-5">
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
                    <h3 class="text-3xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-10"
                        style="font-size: 35px">
                        อัปเกรดทักษะ <br> <span
                            class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-500"
                            style="font-size: 60px">สร้างอนาคตใหม่</span>
                    </h3>
                    <p class="text-xl text-slate-500 mb-10 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        รวมคอร์สออนไลน์คุณภาพจากผู้เชี่ยวชาญระดับแนวหน้า ให้คุณเก่งขึ้นได้ในทุกๆ วัน
                    </p>
                    <div class="max-w-xl mx-auto lg:mx-0 mb-10">
                        <form action="{{ route('courses.all') }}" class="relative group">
                            <input type="text" placeholder="ค้นหาคอร์สที่คุณสนใจ... (เช่น Laravel, Design)"
                                class="w-full bg-white border-2 border-transparent shadow-2xl shadow-blue-100 rounded-2xl py-5 pl-14 pr-6 focus:border-blue-500 outline-none transition-all text-slate-700"
                                name="search">
                            <i
                                class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-xl group-focus-within:text-blue-600 transition-colors"></i>
                            <button type="submit"
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
                        <img src="{{ URL::asset('images/backgroud/heard_bg.jpg') }}"
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

    <section class="py-20 bg-white font-kanit">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-4">
                    <span class="w-12 h-1.5 bg-blue-600 rounded-full"></span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-950 tracking-tight">
                        ข่าวกิจกรรมและประกาศล่าสุด
                    </h2>
                </div>

                <div class="flex">
                    <a href="{{route('activities.all')}}"
                        class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-2xl font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-200 active:scale-95">
                        ดูทั้งหมด <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($activities as $activity)
                <a href="{{ route('activities.show', $activity->slug) }}" class="group block cursor-pointer">
                    <div
                        class="rounded-3xl overflow-hidden mb-6 aspect-video relative shadow-inner bg-slate-100 border border-slate-50">
                        @if($activity->thumbnail)
                        <img src="{{ asset('storage/' . $activity->thumbnail) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="{{ $activity->title }}">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                        @endif

                        <div class="absolute top-4 left-4 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider shadow-lg
                            {{ $activity->category == 'Activity' ? 'bg-indigo-600 text-white' : '' }}
                            {{ $activity->category == 'News' ? 'bg-blue-500 text-white' : '' }}">
                            {{ $activity->category }}
                        </div>
                    </div>

                    <div class="px-2">
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-2">
                            <i class="fas fa-clock mr-1 text-slate-300"></i>
                            {{ $activity->created_at->format('d M Y') }}
                        </p>
                        <h4
                            class="font-black text-slate-900 text-xl mb-3 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                            {{ $activity->title }}
                        </h4>

                        <p class="text-slate-500 text-sm font-medium leading-relaxed line-clamp-2">
                            {{ $activity->short_description ?? Str::limit(strip_tags($activity->content), 120) }}
                        </p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
                    <i class="fas fa-folder-open text-5xl text-slate-300 mb-5 block"></i>
                    <p class="text-xl font-bold text-slate-700">ยังไม่มีข่าวสารหรือกิจกรรมใหม่</p>
                    <p class="text-slate-500 mt-2">โปรดติดตามประกาศจากบริษัทเร็วๆ นี้</p>
                </div>
                @endforelse
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
                            <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.1em]">{{
                                $course->category_name }}</span>
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


    <section class="py-24 container mx-auto font-kanit px-6" id="navigation">
        <div
            class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 rounded-[3.5rem] p-6 md:p-10 relative overflow-hidden shadow-[0_35px_60px_-15px_rgba(59,130,246,0.3)] border border-white/10">
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-sky-400/30 rounded-full blur-[100px] animate-pulse">
            </div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-indigo-400/20 rounded-full blur-[100px]"></div>

            <div class="relative z-10">
                <div class="mb-12 text-center lg:text-left">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-blue-100 text-[10px] font-bold mb-4 backdrop-blur-md">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-300"></span>
                        </span>
                        <span class="tracking-widest uppercase">Smart Factory Shortcuts</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-white">
                        WEB CENTER I-PEX</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                    @forelse($patals->take(20) as $link)
                    <a @if($link->id != 16 && $link->id != 18)
                        href="{{ $link->url }}" target="_blank"
                        @else
                        href="javascript:void(0)"
                        onclick="openModal('{{ $link->id }}')"
                        data-command="show-modal"
                        @endif
                        class="group flex flex-col p-6 bg-white/10 hover:bg-white/20 backdrop-blur-xl rounded-[2.2rem]
                        border border-white/10 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl
                        hover:shadow-blue-950/40 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div
                            class="size-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white shadow-lg mb-5 group-hover:scale-110 transition-transform relative z-10">
                            @if($link->icon)
                            <img src="{{ asset('storage/' . $link->icon) }}"
                                class="w-full h-full object-contain filter drop-shadow-md" alt="{{ $link->title }}"
                                onerror="this.src='{{ asset('assets/img/default-icon.png') }}'">
                            @else
                            <i class="fas fa-link text-white/50 text-xl"></i>
                            @endif
                        </div>
                        <div class="flex flex-col relative z-10">
                            <span
                                class="text-white font-bold text-base tracking-tight mb-2 group-hover:text-sky-300 transition-colors line-clamp-1">
                                {{ $link->title }}
                            </span>
                            <div
                                class="flex items-center gap-2 text-blue-200/50 text-[9px] font-bold uppercase tracking-widest">
                                <span>คลิกเพื่อเข้าใช้งาน</span>
                                <i
                                    class="fas fa-chevron-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center py-20 bg-white/5 rounded-[2.5rem] border border-white/5">
                        <p class="text-blue-200/50 text-lg italic font-light">ยังไม่มีรายการที่เปิดใช้งาน</p>
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div
                    class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[10px] text-blue-200/30 font-bold uppercase tracking-[0.4em]">Managed by ICT
                        Department</p>
                    <div class="flex gap-2">
                        <div class="h-1 w-8 rounded-full bg-white/10"></div>
                        <div class="h-1 w-8 rounded-full bg-white/10"></div>
                        <div class="h-1 w-8 rounded-full bg-sky-400/40"></div>
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

@section('scripts')
<script>
    function openModal(id) {

        document.getElementById('dialog').showModal();
        let url = "{{ route('admin.patals.detail.show', ':id') }}".replace(':id', id);

        $.get(url, function (res) {

            res.forEach((item, index) => {
                // สร้าง Template String
                const card = `
        <a href="${item.url}" target="_blank" 
           class="group flex flex-col p-6 bg-white/5 hover:bg-white/10 backdrop-blur-xl rounded-[2.2rem]
                  border border-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl
                  hover:shadow-blue-500/20 relative overflow-hidden opacity-0 translate-y-4" 
           style="transition-delay: ${index * 100}ms;"> <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div class="size-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white shadow-lg mb-5 group-hover:scale-110 transition-transform duration-500 relative z-10">
                <i class="fas fa-link text-white text-xl"></i>
            </div>

            <div class="flex flex-col relative z-10">
                <span class="text-white font-bold text-base tracking-tight mb-2 group-hover:text-sky-300 transition-colors line-clamp-1">
                    ${item.title}
                </span>
                <div class="flex items-center gap-2 text-blue-200/50 text-[9px] font-bold uppercase tracking-widest">
                    <span>คลิกเพื่อเข้าใช้งาน</span>
                    <i class="fas fa-chevron-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    `;

                const $card = $(card);
                $('#system-1').append($card);

                // ใช้ setTimeout เพื่อกระตุ้น Animation หลังจาก Append เข้าไปใน DOM แล้ว
                setTimeout(() => {
                    $card.removeClass('opacity-0 translate-y-4').addClass(
                        'opacity-100 translate-y-0');
                }, 50);
            });
        });
    }

</script>
@endsection
