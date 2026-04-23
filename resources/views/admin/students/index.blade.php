@extends('layouts.layout_admin')
@section('content')
<dialog id="permission-modal" class="rounded-3xl shadow-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50">
    <div class="bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-black text-slate-800 uppercase tracking-tighter">จัดการสิทธิ์ผู้ใช้งาน</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="permission-form" method="POST" action="{{ route('admin.users.update-role') }}">
            @csrf
            <input type="hidden" name="emp_id" id="modal-emp-id">
            
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <div class="size-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl font-black">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p id="modal-emp-name" class="font-bold text-slate-800">-</p>
                        <p id="modal-emp-code" class="text-xs font-bold text-blue-600 uppercase tracking-widest">-</p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">เลือกสิทธิ์การใช้งาน</label>
                    <div class="grid grid-cols-1 gap-2">
                        <label class="flex items-center p-3 rounded-xl border-2 border-slate-50 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 transition-all">
                            <input type="radio" name="role_name" value="2" class="hidden" required>
                            <span class="font-bold text-slate-700">User</span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border-2 border-slate-50 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 transition-all">
                            <input type="radio" name="role_name" value="1" class="hidden">
                            <span class="font-bold text-slate-700">Administrator</span>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border-2 border-slate-50 cursor-pointer hover:bg-slate-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 transition-all">
                            <input type="radio" name="role_name" value="3" class="hidden">
                            <span class="font-bold text-slate-700">Supper Administrator</span>
                        </label>
                        <input type="text" name="emp_id" id="emp_id" class="hidden">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 active:scale-95 transition-all">
                    Save
                </button>
            </div>
        </form>
    </div>
</dialog>

<div class="container-fluid">
    @if(session('success'))
    <div
        class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-5 text-sm">
        <div class="overflow-x-auto">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">จัดการผู้ใช้งาน</h3>
                    <p class="text-gray-500 text-sm">รวมผู้ใช้งานทั้งหมดในระบบที่คุณดูแลอยู่</p>
                </div>
                {{--  <a href="{{ route('students.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg
                transition-all active:scale-95">
                <i class="fas fa-plus"></i> เพิ่มผู้ใช้งาน
                </a> --}}
            </div>
            <table class="w-full text-left border-collapse" id="students_tb">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4">ลำดับ</th>
                        <th class="p-4">รหัสพนักงาน</th>
                        <th class="p-4">ชื่อ-นามสกุล</th>
                        <th class="p-4">ตำแหน่ง</th>
                        <th class="p-4">สถานะในระบบ</th>
                        <th class="p-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">{{ $student->em_code }}</td>
                        <td class="p-4">{{ $student->full_name_eng }}</td>
                        <td class="p-4">{{ $student->position }}</td>

                        <td class="p-4">
                            @if($student->role_name == null)
                            <span
                                class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs font-medium">User</span>
                            @else
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-medium">{{ $student->role_name }}</span>
                            @endif

                        </td>
                        <td class="p-4 flex items-center justify-center gap-3">
                            <button onclick="get_student({{ $student->id }})"
                                class="w-8 h-8 bg-yellow-100 text-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            No students found. <a href="" class="text-blue-600 hover:underline">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        if ('{{$students}}'.trim() !== '[]') {
            $('#students_tb').DataTable({
                responsive: true,
                language: {
                    search: "ค้นหา:",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ"
                }
            });
        }
    });

    // ฟังก์ชันเปิด Modal และโหลดข้อมูลพนักงาน
    function get_student(id) {
        $('#emp_id').val(id);
        const modal = document.getElementById('permission-modal');
        
        // ค้นหาข้อมูลจากแถวในตาราง (หรือจะใช้ AJAX ดึงจาก Database ก็ได้)
        // ในตัวอย่างนี้ขอใช้ AJAX เบื้องต้นเพื่อให้ข้อมูลสดใหม่ที่สุด
        $.get(`/admin/get-student/${id}`, function(data) {
            $('#modal-emp-id').val(data.id);
            $('#modal-emp-name').text(data.full_name_eng);
            $('#modal-emp-code').text('EM-CODE: ' + data.em_code);
            
            // เลือก Radio button ตามสิทธิ์ที่มีอยู่เดิม
            $(`input[name="role_name"][value="${data.role_id || '2'}"]`).prop('checked', true);
            
            modal.showModal(); // เปิด Modal แบบ Backdrop
        });
    }

    function closeModal() {
        document.getElementById('permission-modal').close();
    }
</script>
@endsection