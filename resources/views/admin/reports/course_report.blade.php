@extends('layouts.layout_admin')
@section('content')

<div class="flex flex-wrap justify-end gap-6 mb-3 font-kanit">

    <div
        class="bg-white p-6 rounded-[1rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-green-100 transition-all duration-500 hover:-translate-y-1">

        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-green-100 to-teal-100 rounded-full blur-3xl group-hover:from-green-200 group-hover:to-teal-200 transition-colors opacity-70">
        </div>

        <div
            class="size-16 bg-gradient-to-br from-green-400 to-teal-700 text-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-lg shadow-green-200/50 border-4 border-white group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 relative z-10">
            <i class="fas fa-user-graduate"></i>
        </div>

        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                ผู้เรียนสะสม
                <span class="size-1.5 bg-gradient-to-r from-green-400 to-teal-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($stats['total_students']) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คน</span>
            </div>
        </div>

        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-green-400 via-teal-600 to-green-400 rounded-full group-hover:h-2 transition-all duration-300">
        </div>
    </div>

    <div
        class="bg-white p-6 rounded-[1rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-blue-100 transition-all duration-500 hover:-translate-y-1">

        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-blue-100 to-violet-100 rounded-full blur-3xl group-hover:from-blue-200 group-hover:to-violet-200 transition-colors">
        </div>
        <div
            class="size-16 bg-gradient-to-br from-blue-500 to-violet-600 text-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-lg shadow-blue-100 border-4 border-white group-hover:scale-110 transition-transform duration-700">
            <i class="fas fa-book"></i>
        </div>
        <div class="relative z-8">
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                คอร์สทั้งหมด
                <span class="size-1.5 bg-gradient-to-r from-blue-400 to-violet-400 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($stats['total']) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คอร์ส</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-violet-500 to-blue-400 rounded-full group-hover:h-1.5 transition-all duration-300">
        </div>
    </div>

</div>

<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-gray-100 overflow-hidden p-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">รายงานคอร์สเรียน
                </h3>
                <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Course Summary Report
                </p>
            </div>
        </div>
        <form action="{{ route('admin.reports.student') }}" method="get" enctype="multipart/form-data">
            @csrf
            <div id="event-fields" class="md:col-span-2 grid grid-cols-2 md:grid-cols-2 gap-6">
                <div class="space-y-2 w-100">
                    <label class="text-sm font-bold text-slate-500">หมวดหมู่</label>
                    <select name="category_id" id="category_id"
                        class="w-full border-none rounded-2xl focus:ring-2 focus:ring-blue-500 select2">
                        <option value="">- Select item -</option>
                        @foreach (App\Models\Category::all() as $item)
                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 w-100">
                    <label class="text-sm font-bold text-slate-500">หลักสูตร</label>
                    <select name="course_id" id="course_id"
                        class="w-full border-none rounded-2xl focus:ring-2 focus:ring-blue-500 select2">
                        <option value="">- Select item -</option>
                        @foreach (App\Models\Course::all() as $item)
                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-xl transition-all active:scale-95">
                    <i class="fa fa-search" aria-hidden="true"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden p-5 mt-3">
    <table class="w-full text-left" id="course_tb">
        <thead class="bg-slate-50/50 uppercase text-[10px] tracking-widest">
            <tr>
                <th class="px-8 py-6">ข้อมูลหลักสูตร</th>
                <th class="px-8 py-6">หมวดหมู่</th>
                <th class="px-8 py-6 text-center">ผู้เข้าเรียน</th>
                <th class="px-8 py-6 text-center">สถานะ</th>
                <th class="px-8 py-6 text-center">วันที่สร้าง</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($courses as $course)
            <tr class="hover:bg-slate-50/30 transition-all group">
                <td class="px-8 py-5">
                    <div class="font-black text-slate-700 group-hover:text-blue-600 transition-colors">
                        {{ $course->title }}
                    </div>
                    <div class="text-[10px] text-slate-400">ผู้สอน: {{ $course->user->name }}</div>
                </td>
                <td class="px-8 py-5">
                    <span
                        class="text-xs text-yellow-500 bg-yellow-100 px-3 py-1 rounded-full border border-emerald-100">
                        {{ $course->category_name ?? 'ไม่มีหมวดหมู่' }}
                    </span>
                </td>
                <td class="px-8 py-5 text-center font-black text-blue-600">
                    {{ number_format($course->enrollments_count) }}
                </td>
                <td class="px-8 py-5 text-center">
                    <span
                        class="px-4 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[9px] font-black uppercase">Active</span>
                </td>
                <td class="px-8 py-5 text-center">
                    {{ $course->created_at->format('d/m/Y') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2();
        if ('{{$courses}}'.trim() !== '[]') {
            $('#course_tb').DataTable();
        }
    });

</script>
@endsection
