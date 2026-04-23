@extends('layouts.layout_public')
@section('content')
@if(session('success'))
<div id="success-overlay" class="fixed inset-0 z-[100] flex items-center justify-center px-6">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-[3rem] p-10 shadow-2xl relative z-10 max-w-sm w-full text-center animate-bounce-in">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl animate-soft-pulse">
            <i class="fas fa-check"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-900 mb-2">ลงทะเบียนสำเร็จ!</h3>
        <p class="text-slate-500 mb-8">{{ session('success') }}</p>
        <button onclick="document.getElementById('success-overlay').remove()" 
                class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all">
            ตกลง
        </button>
    </div>
</div>
@endif

<div class="bg-slate-50 min-h-screen pb-20">
    <div class="bg-white border-b border-slate-100">
        <div class="container mx-auto px-6 py-8">
            <nav class="flex text-sm text-slate-400 mb-6 gap-2 items-center">
                <a href="/" class="hover:text-blue-600">หน้าหลัก</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="#courses" class="hover:text-blue-600">คอร์สทั้งหมด</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-slate-900 font-bold truncate">{{ $course->title }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-12">
                <div class="lg:w-2/3">
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white group">
                        <img src="{{ asset('storage/'.$course->thumbnail) }}"
                            class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                            <button
                                class="w-20 h-20 bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center text-white text-2xl hover:scale-110 transition-all border border-white/50">
                                <i class="fas fa-play ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div
                        class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/5 border border-slate-50 sticky top-24">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">TRENDING</span>
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                    class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                        <h1 class="text-3xl font-black text-slate-900 mb-4 leading-tight">{{ $course->title }}</h1>
                        <p class="text-slate-500 text-sm mb-6 line-clamp-3">{{ $course->description }}</p>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3 text-slate-600 text-sm font-semibold">
                                <i class="far fa-clock text-blue-500"></i>{{ $course->duration ?? 'ไม่ระบุ' }} ชั่วโมง
                            </div>
                            <div class="flex items-center gap-3 text-slate-600 text-sm font-semibold">
                                <i class="far fa-file-alt text-blue-500"></i> จำนวน {{ $lessons->count() }} บทเรียน
                            </div>
                            <div class="flex items-center gap-3 text-slate-600 text-sm font-semibold">
                                <i class="fas fa-certificate text-blue-500"></i> สามารถเรียนรู้ได้ตลอดชีพ
                            </div>
                        </div>

                        <div class="flex items-end justify-between mb-8">
                            <div>
                                {{--  <p class="text-4xl font-black text-blue-600">฿{{ number_format($course->price ?? 990) }}  --}}
                                </p>
                            </div>
                        </div>

                        @if($alreadyEnrolled)
                        <a href="{{ route('profile.index') }}"
                            class="block w-full text-center py-5 bg-green-600 text-white rounded-2xl font-black shadow-xl shadow-green-200 hover:bg-green-700 hover:-translate-y-1 transition-all active:scale-95">
                            เข้าเรียนต่อเลย
                        </a>
                        @else
                        <a href="{{ route('courses.register.view', $course->id) }}"
                            class="block w-full text-center py-5 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95">
                            ลงทะเบียนเรียน
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-16">
        <div class="lg:w-2/3">
            <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <span
                    class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center text-xs italic">!</span>
                รายละเอียดคอร์สเรียน
            </h2>
            <div class="prose prose-slate max-w-none text-slate-600 leading-loose">
                {!! $course->description ?? 'ยังไม่มีข้อมูลรายละเอียด' !!}
            </div>

            <h2 class="text-2xl font-black text-slate-900 mt-16 mb-8">เนื้อหาในหลักสูตร</h2>
            <div class="space-y-4">
                @foreach ($lessons as $i => $lesson)

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-blue-200 transition-colors cursor-pointer">
                    <div class="flex items-center gap-4">
                        <span class="text-slate-300 font-black italic">0{{$i+1}}</span>
                        <p class="font-bold text-slate-700">บทนำและพื้นฐานเบื้องต้น ตอนที่ {{$lesson->title}}</p>
                    </div>
                    <i class="fas fa-lock text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
