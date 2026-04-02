@extends('layouts.layout_admin')
@section('content')
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5">
        <div class="overflow-x-auto">

            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">จัดการผู้ใช้งาน</h3>
                    <p class="text-gray-500 text-sm">รวมผู้ใช้งานทั้งหมดในระบบที่คุณดูแลอยู่</p>
                </div>
                <a href="{{ route('students.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
                    <i class="fas fa-plus"></i> เพิ่มผู้ใช้งาน
                </a>
            </div>

            <table class="w-full text-left border-collapse" id="students_tb">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">No.</th>
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Email</th>
                        <th class="p-4 font-semibold">Position</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Created At</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">{{ $student->name }}</td>
                        <td class="p-4">{{ $student->email }}</td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $student->status == '0' ? 
                             'bg-green-100 text-green-800' : ($student->status == '1' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $student->status == '0' ? 'Admin' : ($student->status == '1' ? 'Teacher' : 'Student') }}
                            </span>

                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $student->status_activate == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $student->status_activate == '1' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4">{{ $student->created_at->format('M d, Y') }}</td>
                        <td class="p-4 flex items-center justify-center gap-3">
                            <a href="{{ route('students.edit', $student->id) }}"
                                class="w-8 h-8 bg-yellow-100 text-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-600 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-pen text-xs"></i>
                            </a>

                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                onsubmit="return confirm('ยืนยันการลบนักเรียนนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 bg-red-100 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
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
        $('.select2').select2();
        if ('{{$students}}'.trim() !== '[]') {
            $('#students_tb').DataTable();
        }
    });

</script>
@endsection
