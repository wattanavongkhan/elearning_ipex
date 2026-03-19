@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24 font-kanit">
    <div class="bg-white border-b border-slate-100 pt-20 pb-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-gradient-to-tr from-blue-600 to-indigo-400 p-1 shadow-2xl shadow-blue-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=fff&color=2563eb&size=128" 
                             class="w-full h-full rounded-[2.3rem] object-cover border-4 border-white">
                    </div>
                    <button class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>

                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                        <h1 class="text-3xl font-black text-slate-900">{{ $user->name }}</h1>
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg w-max mx-auto md:mx-0 uppercase tracking-widest">Premium Member</span>
                    </div>
                    <p class="text-slate-400 font-medium mb-6">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <div class="px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-1">คอร์สทั้งหมด</p>
                            <p class="text-xl font-black text-slate-900">{{ $totalCourses }}</p>
                        </div>
                        <div class="px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-1">เรียนจบแล้ว</p>
                            <p class="text-xl font-black text-slate-900">{{ $completedCourses }}</p>
                        </div>
                        <div class="px-6 py-3 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-1">ใบประกาศฯ</p>
                            <p class="text-xl font-black text-slate-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="md:self-start">
                    <a href="#" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition-all">
                        <i class="fas fa-cog"></i> ตั้งค่าโปรไฟล์
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 mt-16">
        <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
            <i class="fas fa-play-circle text-blue-600"></i>
            คอร์สเรียนของคุณ
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($enrollments as $item)
            @php
                // คำนวณ Progress ของคอร์สนี้
                $allLessons = $item->course->lessons;
                $totalLessons = $allLessons->count();
                $doneLessons = $allLessons->whereIn('id', $completedLessonIds ?? [])->count();
                $progressPercent = $totalLessons > 0 ? round(($doneLessons / $totalLessons) * 100) : 0;
            @endphp

            <div class="group bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-900/5 transition-all duration-500">
                <div class="flex flex-col sm:flex-row gap-6">
                    <div class="w-full sm:w-40 aspect-video sm:aspect-square rounded-3xl overflow-hidden shadow-inner flex-shrink-0">
                        <img src="{{ asset('storage/'.$item->course->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-2">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-black text-slate-800 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ $item->course->title }}
                                </h3>
                                @if($progressPercent == 100)
                                    <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-md tracking-tighter">COMPLETED</span>
                                @elseif($item->status == 'pending_payment')
                                    <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-2 py-1 rounded-md">WAITING</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mb-6 italic">เริ่มเรียนเมื่อ: {{ $item->created_at->format('d M Y') }}</p>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">ความคืบหน้า ({{ $doneLessons }}/{{ $totalLessons }} บท)</span>
                                <span class="text-sm font-black text-blue-600">{{ $progressPercent }}%</span>
                            </div>
                            <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden p-[2px] shadow-inner">
                                <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-400 rounded-full transition-all duration-1000 ease-out" 
                                     style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>

                        @if($item->status == '1')
                            <a href="{{ route('courses.learn', $item->course->id) }}" class="mt-6 flex items-center justify-center gap-2 w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-blue-600 transition-all shadow-lg shadow-slate-100">
                                {{ $progressPercent > 0 ? 'เรียนต่อจากเดิม' : 'เริ่มเรียนบทแรก' }} 
                                <i class="fas {{ $progressPercent > 0 ? 'fa-play' : 'fa-arrow-right' }} text-[10px]"></i>
                            </a>
                        @else
                            <div class="mt-6 py-4 bg-slate-50 text-slate-400 text-center rounded-2xl text-xs font-bold border border-slate-100">
                                รอการอนุมัติเพื่อเข้าเรียน
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="lg:col-span-2 text-center py-20 bg-white rounded-[3rem] border border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-book-open text-3xl text-slate-200"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">ยังไม่มีคอร์สเรียน</h3>
                <p class="text-slate-400 mb-8 font-medium text-sm">เริ่มค้นหาบทเรียนที่ใช่ แล้วก้าวไปข้างหน้าพร้อมกับเรา</p>
                <a href="{{ route('courses.all') }}" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">
                    ไปที่หน้าคอร์สเรียน
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Shine effect สำหรับ Progress Bar */
    .h-full {
        position: relative;
        overflow: hidden;
    }
    .h-full::after {
        content: "";
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: progressShine 4s infinite;
    }
    @keyframes progressShine {
        to { left: 100%; }
    }
</style>
@endsection