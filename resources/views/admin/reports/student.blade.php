@extends('layouts.layout_admin')
@section('content')
<div class="flex flex-wrap justify-end gap-6 mb-6 font-kanit">
    <div
        class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-orange-100 transition-all duration-500 hover:-translate-y-1">
        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-orange-100 to-amber-50 rounded-full blur-3xl group-hover:from-orange-200 transition-colors opacity-70">
        </div>
        <div
            class="size-16 bg-gradient-to-br from-orange-400 to-amber-600 text-white rounded-[1.25rem] flex items-center justify-center text-3xl shadow-lg shadow-orange-200/50 border-4 border-white group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 relative z-10">
            <i class="fas fa-running"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                กำลังเรียน
                <span class="size-1.5 bg-gradient-to-r from-orange-400 to-amber-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($student->where('status', 1)->count()) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คน</span>
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
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                เรียนจบแล้ว
                <span class="size-1.5 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format($student->where('status', '!=', 1)->count()) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คอร์ส</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-400 rounded-full group-hover:h-2 transition-all duration-300">
        </div>
    </div>

    <div
        class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-50 flex items-center gap-6 min-w-[280px] relative overflow-hidden group hover:shadow-xl hover:border-blue-100 transition-all duration-500 hover:-translate-y-1">
        <div
            class="absolute -right-8 -bottom-8 size-32 bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-3xl group-hover:from-blue-200 transition-colors opacity-70">
        </div>
        <div
            class="size-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-[1.25rem] flex items-center justify-center text-3xl shadow-lg shadow-blue-200/50 border-4 border-white group-hover:scale-110 group-hover:rotate-6 transition-transform duration-700 relative z-10">
            <i class="fas fa-users"></i>
        </div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                ผู้เรียนทั้งหมด
                <span class="size-1.5 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full animate-pulse"></span>
            </p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">
                    {{ number_format(count($student)) }}
                </h3>
                <span class="text-[11px] font-bold text-slate-400 uppercase">คอร์ส</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-400 rounded-full group-hover:h-2 transition-all duration-300">
        </div>
    </div>

</div>

<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-gray-100 overflow-hidden p-5">
        <div class="flex justify-between items-end mb-5">
            <div>
                <h4 class="text-xl font-black tracking-tighter">รายงานผู้เรียน</h4>
                <p class="text-sm text-slate-500">Paid Enrollment & Revenue Tracker</p>
            </div>
        </div>
        <form action="{{ route('admin.reports.student') }}" method="get" enctype="multipart/form-data">
            @csrf
            <div id="event-fields" class="md:col-span-4 grid grid-cols-4 md:grid-cols-4 gap-6">
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
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-500">ผู้เรียน</label>
                    <select name="student_id" id="student_id"
                        class="w-full border-none rounded-2xl focus:ring-2 focus:ring-blue-500 select2">
                        <option value="">- Select item -</option>
                        @foreach (App\Models\User::where('status','1')->get() as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-500">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" class="w-full border rounded-xl p-2">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-500">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" class="w-full border rounded-xl p-2">
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl transition-all active:scale-95">
                    ค้นหา
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden p-5 mt-4">
    <table class="w-full text-left" id="studentDetailTable">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-8 py-6">ผู้เรียน</th>
                <th class="px-8 py-6">หลักสูตร</th>
                <th class="px-8 py-6">วันที่ลงทะเบียน</th>
                {{--  <th>แบบทดสอบ</th>  --}}
                <th class="px-8 py-6 ">สถานะ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($student as $reg)
            <tr class="hover:bg-slate-50/50 transition-all group">
                <td class="px-8 py-5">
                    <div class="font-black text-slate-700 group-hover:text-blue-600 transition-colors">
                        {{ $reg->full_name_th }}</div>
                    <div class="text-[10px] text-slate-400">
                        {{ $reg->section }}</div>
                </td>
                <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                    {{ $reg->course_title }}
                </td>
                <td class="px-8 py-5 text-sm font-mono text-slate-400 tracking-tighter">
                    {{ $reg->created_at->format('d/m/Y H:i') }}
                </td>
                {{--  <td class="px-8 py-5 text-sm font-mono text-slate-400 tracking-tighter">
                     | {{ $reg->score != null ? $reg->score : 'ยังไม่ทำแบบทดสอบ' }}
                </td>  --}}
                <td class="px-8">
                    @if($reg->status == 1)
                    <span
                        class="px-4 py-1.5 bg-orange-50 text-orange-600 border border-orange-100 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">In
                        Progress</span>
                    @else
                    <span
                        class="px-4 py-1.5 bg-green-50 text-green-600 border border-green-100 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">Complete</span>
                    @endif
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
        if ('{{$student}}'.trim() !== '[]') {
            $('#studentDetailTable').DataTable();
        }
    });

</script>
@endsection
