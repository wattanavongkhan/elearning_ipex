@extends('layouts.layout_admin')

@section('content')
<div class="font-kanit">
    <div class="container">
        <div class="flex flex-col md:row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">จัดการข่าวสารและกิจกรรม</h1>
                <p class="text-slate-500 font-medium">ศูนย์รวมข้อมูลประชาสัมพันธ์และกิจกรรม DX ภายในองค์กร</p>
            </div>
            <a href="{{ route('admin.activities.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2 text-lg"></i> เพิ่มข้อมูลใหม่
            </a>
        </div>

        <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
            <a href="{{ route('admin.activities.index') }}"
               class="px-6 py-2 rounded-xl font-bold transition-all {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-white text-slate-500 border border-slate-100' }}">
                ทั้งหมด
            </a>
        </div>

        <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">ข้อมูล</th>
                        <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">ประเภท/วันที่จัด</th>
                        <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-center">ยอดเข้าชม</th>
                        <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-center">สถานะ</th>
                        <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activities as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" class="w-14 h-14 rounded-2xl object-cover shadow-sm border border-slate-100">
                                    @if($item->is_featured)
                                        <span class="absolute -top-2 -left-2 bg-amber-400 text-white w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm" title="ปักหมุด">
                                            <i class="fas fa-star text-[10px]"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="max-w-xs">
                                    <div class="font-black text-slate-800 line-clamp-1">{{ $item->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $item->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            @if($item->category == 'Activity')
                                <div class="inline-flex items-center bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase mb-1">
                                    <i class="fas fa-calendar-day mr-1"></i> Activity
                                </div>
                                <div class="text-xs font-bold text-slate-600">{{ $item->event_date ? $item->event_date->format('d M Y') : '-' }}</div>
                            @else
                                <div class="inline-flex items-center bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase">
                                    <i class="fas fa-newspaper mr-1"></i> News
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-black text-slate-700"><i class="fas fa-eye text-slate-300 mr-1"></i> {{ number_format($item->view_count) }}</span>
                        </td>

                        <td class="px-6 py-5 text-center">
                            @if($item->status == 1)
                                <span class="text-green-500 bg-green-50 px-3 py-1 rounded-full text-[10px] font-black">PUBLISHED</span>
                            @else
                                <span class="text-slate-400 bg-slate-100 px-3 py-1 rounded-full text-[10px] font-black">DRAFT</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.activities.edit', $item->id) }}" class="w-10 h-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.activities.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')">
                                    @csrf @method('DELETE')
                                    <button class="w-10 h-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <i class="fas fa-folder-open text-4xl text-slate-200 mb-4 block"></i>
                            <span class="text-slate-400 font-medium">ไม่พบข้อมูลในหมวดหมู่นี้</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
