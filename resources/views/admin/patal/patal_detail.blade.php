@extends('layouts.layout_admin')
@section('content')
<el-dialog>
    <dialog id="dialog" aria-labelledby="dialog-title"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent font-kanit">
        <el-dialog-backdrop
            class="fixed inset-0 bg-slate-900/60 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in backdrop-blur-sm">
        </el-dialog-backdrop>
        <div tabindex="0"
            class="flex min-h-full items-end justify-center text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel
                class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form action="{{ route('admin.patals_detail.store') }}" method="POST" enctype="multipart/form-data"
                    id="patal-form">
                    @csrf
                    <div class="bg-white px-6 pt-8 pb-6 sm:p-6 sm:pb-8">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-5 sm:text-left w-full">
                                <h3 id="dialog-title" class="text-xl font-black text-slate-800 tracking-tight">
                                    รายละเอียด</h3>
                                <p class="text-sm text-slate-400 mt-1 mb-6 font-medium">
                                    กรอกข้อมูลระบบที่คุณต้องการสร้างทางลัดหน้าแรก</p>

                                <div class="space-y-5 mt-5">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ชื่อรายการระบบ</label>
                                        <input type="text" name="title" required
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700"
                                            placeholder="เช่น ระบบลางาน, Web Center">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ลิงก์ปลายทาง
                                            (URL)</label>
                                        <input type="url" name="url" required
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700 font-mono text-sm"
                                            placeholder="https://example.com">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">การแสดงผล</label>
                                        <select name="status"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-medium">
                                            <option value="1">แสดงผล</option>
                                            <option value="0">ปิด</option>
                                        </select>
                                    </div>
                                    <input type="text" name="id" hidden>
                                    <input type="text" name="patal_id" value="{{$id}}" hidden>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ลำดับแสดงผล</label>
                                        <select name="seq_no"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl outline-none font-medium">
                                            @for($i = 0; $i <20; $i++) <option value="{{$i}}">
                                                {{$i}}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50/50 px-8 py-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                            <i class="fas fa-save mr-2"></i> Save
                        </button>
                        <button type="button" command="close" commandfor="dialog"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                            <i class="fa fa-repeat" aria-hidden="true"></i> Cancel
                        </button>
                    </div>
                </form>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-[1rem] shadow-sm border border-gray-100 overflow-hidden p-7">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
            <div>
                <h3 class="text-xl font-black text-gray-800 tracking-tight">สร้างทางลัดส่วนย่อย : <b
                        class="text-gray-500">{{ $title->title }}</b></h3>
                <nav class="text-sm text-gray-500 mb-1 font-kanit">
                    <p>จัดการส่วนย่อย </p>
                </nav>
            </div>
            <button onclick="create(0)" type="button"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center font-bold">
                <i class="fas fa-plus mr-2"></i> เพิ่มรายการ
            </button>
        </div>
        <table class="w-full text-left border-collapse font-kanit mt-3" id="patalDetailTable">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-40 text-center">
                        ลำดับ</th>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-400">ไอคอน</th>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-400">ชื่อรายการ /
                        ลิงก์ปลายทาง</th>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-400 text-center">
                        ลำดับแสดง</th>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-400 text-center">
                        สถานะ</th>
                    <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-gray-400 w-44 text-center">
                        จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($patals as $patal)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4 text-sm text-gray-400 font-mono text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        <div
                            class="size-12 rounded-2xl bg-green-50 flex items-center justify-center border border-slate-100 text-green-400">
                            <i class="fa fa-database bg-green-100" aria-hidden="true"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                            {{ $patal->title }}</div>
                        <div class="text-[11px] text-gray-400 font-medium truncate max-w-xs">{{ $patal->url }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
                            {{ $patal->seq_no }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($patal->status)
                        <span
                            class="inline-flex items-center gap-1.5 text-green-600 text-xs font-bold bg-green-50 px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> เปิดใช้งาน
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1.5 text-gray-400 text-xs font-bold bg-gray-50 px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> ปิดชั่วคราว
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="create({{$patal->id}})"
                                class="p-2.5 bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all rounded-xl shadow-sm border border-amber-100">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('patals_detail.destroy') }}" method="POST"
                                onsubmit="return confirm('คุณต้องการลบทางลัดนี้ใช่หรือไม่?')">
                                @csrf @method('POST')
                                <input name="id" value="{{$patal->id}}">
                                <button type="submit"
                                    class="p-2.5 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all rounded-xl shadow-sm border border-red-100">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-folder-open text-5xl text-gray-100 mb-4"></i>
                            <p class="text-gray-400 font-bold">ยังไม่มีข้อมูลทางลัดในระบบ</p>
                            <span class="text-xs text-gray-300">คลิกปุ่มด้านบนเพื่อเพิ่มรายการแรกของคุณ</span>
                        </div>
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
        title: 'ดำเนินการสำเร็จ!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2563eb',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-[2rem]',
            confirmButton: 'rounded-xl px-10 py-3 font-kanit'
        }
    });

</script>
@endif
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        if ('{{$patals}}'.trim() !== '[]') {
            $('#patalDetailTable').DataTable();
        }
    });

    function create(id) {
        $('#patal-form')[0].reset();
        $('input[name="id"]').val('');

        $('#preview-icon').attr('src', '').addClass('hidden');
        document.getElementById('dialog').showModal();
        if (id > 0) {
            get_partal(id);
        }
    }

    function get_partal(id) {
        let url = "{{ route('admin.patals.detail.show', ':id') }}".replace(':id', id);
        $.get(url, function (res) {
            $('input[name="id"]').val(res.id);
            $('input[name="title"]').val(res.title);
            $('input[name="url"]').val(res.url);
            $('select[name="status"]').val(res.status);
            $('select[name="seq_no"]').val(res.seq_no);
        });
    }

</script>
@endsection
