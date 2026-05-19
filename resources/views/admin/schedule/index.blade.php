@extends('layouts.layout_admin')
@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 overflow-hidden p-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="font-black text-black-500 text-xl">
                    <p>จัดการปฏิทิน</p>
                </h1>
                <span class="text-gray-800 text-sm tracking-tight">กิจกรรมและตารางเวลา</span>
            </div>
            <a href="{{ route('schedule.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <i class="fas fa-plus"></i> เพิ่มปฏิทินใหม่
            </a>
        </div>

        <table class="w-full text-left border-collapse" id="schedule_tb">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">Purpose</th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest">Start Date</th>
                    </th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-center">
                        End Date
                    </th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-center">Status
                    </th>
                    <th class="px-6 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest text-right">จัดการ
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($calendarEvents as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-5">
                        {{ $item->purpose }}
                    </td>
                    <td class="px-6 py-5">
                        {{ $item->start_date}}
                    </td>
                    <td class="px-6 py-5">
                        {{ $item->end_date}}
                    </td>
                    <td class="px-6 py-5 text-center">
                        @if($item->status == '0')
                        <span
                            class="text-green-500 bg-green-50 px-3 py-1 rounded-full text-[10px] font-black">Active</span>
                        @else
                        <span class="text-slate-400 bg-slate-100 px-3 py-1 rounded-full text-[10px] font-black">In
                            Active</span>
                        @endif
                    </td>

                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('schedule.edit', $item->id) }}"
                                class="w-8 h-8 bg-yellow-100 text-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('schedule.destroy', $item->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')">
                                @csrf @method('DELETE')
                                <button
                                    class="w-8 h-8 bg-red-100 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
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
    </div>
</div>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ',
        text: '{{ session('
        success ') }}',
        confirmButtonText: 'ตกลง'
    });

</script>
@endif

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        if ('{{$calendarEvents}}'.trim() !== '[]') {
            $('#schedule_tb').DataTable();
        }
    });

</script>
@endsection
