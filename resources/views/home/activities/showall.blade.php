@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-20 font-kanit">

    <div class="bg-blue-600 py-20 mb-12">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">ข่าวสารและกิจกรรมทั้งหมด</h1>
            <p class="text-blue-100 text-lg font-medium">ติดตามความเคลื่อนไหวล่าสุดจาก I-PEX (Thailand)</p>
        </div>
    </div>

    <div class="container mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            @php $currentCat = request('category', 'All'); @endphp
            <a href="{{ route('activities.all') }}"
               class="px-8 py-3 rounded-2xl font-bold transition-all {{ $currentCat == 'All' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
                ทั้งหมด
            </a>
            <a href="{{ route('activities.all', ['category' => 'News']) }}"
               class="px-8 py-3 rounded-2xl font-bold transition-all {{ $currentCat == 'News' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
                ข่าวสาร
            </a>
            <a href="{{ route('activities.all', ['category' => 'Activity']) }}"
               class="px-8 py-3 rounded-2xl font-bold transition-all {{ $currentCat == 'Activity' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100' }}">
                กิจกรรม
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            @forelse($activities as $activity)
            <a href="{{ route('activities.show', $activity->slug) }}" class="group block bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-100">
                <div class="aspect-video relative overflow-hidden">
                    @if($activity->thumbnail)
                        <img src="{{ asset('storage/' . $activity->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-wider
                        {{ $activity->category == 'Activity' ? 'bg-indigo-600 text-white' : 'bg-blue-500 text-white' }}">
                        {{ $activity->category }}
                    </div>
                </div>
                <div class="p-8">
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-3">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $activity->created_at->format('d M Y') }}
                    </p>
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {{ $activity->title }}
                    </h3>
                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                        {{ $activity->short_description ?? Str::limit(strip_tags($activity->content), 120) }}
                    </p>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" class="w-24 h-24 mx-auto mb-6 opacity-20">
                <p class="text-xl font-bold text-slate-400">ไม่พบข้อมูลในหมวดหมู่ที่คุณเลือก</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16 flex justify-center">
            {{ $activities->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
