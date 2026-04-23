@extends('layouts.layout_admin')
@section('content')
<div class="container">
<div class="flex flex-wrap justify-end gap-6 mb-6 font-kanit">
 <div
        class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-orange-100 transition-all duration-500 hover:-translate-y-1">
        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-orange-100 to-amber-50 rounded-full blur-3xl group-hover:from-orange-200 transition-colors opacity-70">
        </div>
        <div
            class="size-16 bg-gradient-to-br from-orange-400 to-amber-600 text-white rounded-[1.25rem] flex items-center justify-center text-3xl shadow-lg shadow-orange-200/50 border-4 border-white group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 relative z-10">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                คะแนนสูงสุด
                <span class="size-1.5 bg-gradient-to-r from-orange-400 to-amber-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($quizzes->max('score')) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คะแนน</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-400 via-amber-500 to-orange-400 rounded-full group-hover:h-2 transition-all duration-300">
        </div>
    </div>

    <div
        class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-emerald-100 transition-all duration-500 hover:-translate-y-1">
        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-emerald-100 to-teal-50 rounded-full blur-3xl group-hover:from-emerald-200 transition-colors opacity-70">
        </div>
        <div
            class="size-16 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-[1.25rem] flex items-center justify-center text-3xl shadow-lg shadow-emerald-200/50 border-4 border-white group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 relative z-10">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                จำนวนแบบทดสอบ
                 <span class="size-1.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full animate-pulse"></span>
                <span class="size-1.5 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($quizzes->count()) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">แบบทดสอบ</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-400 rounded-full group-hover:h-2 transition-all duration-300">
        </div>
    </div>


</div>

<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="font-black text-black-500 text-xl">
                    <p>แบบทดสอบ</p>
                </h1>
                <span class="text-gray-500 text-sm tracking-tight">สรุปผลการทำแบบทดสอบ</span>
            </div>
        </div>

        <table class="w-full text-left border-collapse" id="quiz_tb">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">แบบทดสอบ</th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">คะแนน</th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">วันที่ทำ</th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">ผู้ทำแบบทดสอบ</th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">ประเภท</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($quizzes as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-5">{{ $item->title }}</td>
                    <td class="px-6 py-5">{{ $item->score }}</td>
                    <td class="px-6 py-5">{{ $item->created_at }}</td>
                    <td class="px-6 py-5">{{ $item->full_name_th }}</td>
                    <td class="px-6 py-5">{{ $item->type }}</td>
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
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        if ('{{$quizzes}}'.trim() !== '[]') {
            $('#quiz_tb').DataTable();
        }
    });
</script>
@endsection