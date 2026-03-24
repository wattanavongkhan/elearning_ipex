@extends('layouts.layout_public')

@section('content')
<article class="py-10 bg-slate-10 min-h-screen font-kanit">
    <div class="container mx-auto px-3 max-w-7xl">
        <nav class="mb-8">
            <a href="{{ url('/') }}" class="text-blue-600 font-bold hover:underline">
                <i class="fas fa-chevron-left mr-2"></i> กลับหน้าหลัก
            </a>
        </nav>

        <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">

            <div class="aspect-video w-full relative bg-slate-200">
                @if($activity->thumbnail)
                    <img src="{{ asset('storage/' . $activity->thumbnail) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <i class="fas fa-image text-7xl"></i>
                    </div>
                @endif

                <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur px-6 py-2 rounded-2xl shadow-lg">
                    <span class="text-blue-700 font-black text-xs uppercase tracking-widest">
                        {{ $activity->category }}
                    </span>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <div class="flex flex-wrap items-center gap-6 mb-6 text-slate-400 text-sm font-medium">
                    <span><i class="far fa-calendar-alt mr-2"></i> {{ $activity->created_at->format('d M Y') }}</span>
                    <span><i class="far fa-eye mr-2"></i> {{ number_format($activity->view_count) }} views</span>
                </div>

                <h3 class="text-3xl md:text-3xl font-black text-slate-900 leading-tight mb-8">
                    {{ $activity->title }}
                </h3>

                @if($activity->category == 'Activity')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10 p-6 bg-blue-50 rounded-3xl border border-blue-100">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-tighter">วันที่จัดงาน</p>
                            <p class="text-slate-800 font-black">{{ $activity->event_date ? $activity->event_date->format('d F Y') : 'โปรดสอบถามเพิ่มเติม' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shrink-0 shadow-md">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">สถานที่</p>
                            <p class="text-slate-800 font-black">{{ $activity->location ?? 'ออนไลน์ / ระบุภายหลัง' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed font-medium text-lg">
                    {!! nl2br($activity->content) !!}
                </div>

                <div class="mt-16 pt-8 border-t border-slate-100 flex justify-between items-center">
                    <div class="flex gap-2">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Share:</span>
                        <a href="#" class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-slate-400 hover:text-blue-400 transition-colors"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection
