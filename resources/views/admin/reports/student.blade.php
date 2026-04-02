@extends('layouts.layout_admin')
@section('content')
<div class="flex justify-between items-end mb-5">
    <div>
        <h4 class="text-2xl font-black tracking-tighter">รายงานผู้เรียน</h4>
        <p class="">Paid Enrollment & Revenue Tracker</p>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white p-6 rounded-[1rem] shadow-sm border border-slate-100 flex items-center justify-between group">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">กำลังเรียน</p>
            <h3 class="text-2xl font-black text-yellow-600">{{ number_format($student->where('status', 1)->count()) }}
                <span class="text-sm font-normal text-slate-400 ml-1">คน</span></h3>
        </div>
        <div
            class="size-12 bg-yellow-50 text-bg-yellow-500 rounded-2xl flex items-center justify-center text-xl shadow-inner">
            <i class="fas fa-book-reader text-yellow-500"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-[1rem] shadow-sm border border-slate-100 flex items-center justify-between group">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">เรียนจบแล้ว</p>
            <h3 class="text-2xl font-black text-green-600">
                {{ number_format($student->where('status', '!=', 1)->count()) }} <span
                    class="text-sm font-normal text-slate-400 ml-1">คน</span></h3>
        </div>
        <div
            class="size-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
            <i class="fas fa-graduation-cap"></i>
        </div>
    </div>
    <div class="bg-white p-6 rounded-[1rem] shadow-sm border border-slate-100 flex items-center justify-between group">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ผู้เรียนทั้งหมด</p>
            <h3 class="text-2xl font-black text-slate-800">{{ number_format(count($student)) }} <span
                    class="text-sm font-normal text-slate-400 ml-1">คน</span></h3>
        </div>
        <div
            class="size-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-all">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.reports.student') }}" method="get" enctype="multipart/form-data" class="p-8">
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

<div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden p-3 mt-4">
    <table class="w-full text-left" id="studentDetailTable">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-8 py-6">ผู้เรียน</th>
                <th class="px-8 py-6">หลักสูตร</th>
                <th class="px-8 py-6">วันที่ลงทะเบียน</th>
                <th class="px-8 py-6 text-center">สถานะ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($student as $reg)
            <tr class="hover:bg-slate-50/50 transition-all group">
                <td class="px-8 py-5">
                    <div class="font-black text-slate-700 group-hover:text-blue-600 transition-colors">
                        {{ $reg->student_name }}</div>
                    <div class="text-[10px] text-slate-400">
                        {{ $reg->student_email }}</div>
                </td>
                <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                    {{ $reg->title }}
                </td>
                <td class="px-8 py-5 text-sm font-mono text-slate-400 tracking-tighter">
                    {{ $reg->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-8 py-5 text-center">
                    @if($reg->status == 1)
                    <span
                        class="px-4 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[9px] font-black uppercase tracking-widest">In
                        progress</span>
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
