@extends('layouts.layout_admin') {{-- อ้างอิงจากไฟล์ Layout หลักที่เราสร้างไว้ --}}

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
                    <h3 class="text-xl  text-black-800">จัดการคอร์สเรียน</h3>
                    <p class="text-gray-500 text-sm">รวมคอร์สเรียนทั้งหมดในระบบที่คุณดูแลอยู่</p>
                </div>
                <a href="{{ route('courses.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
                    <i class="fas fa-plus"></i> เพิ่มคอร์สใหม่
                </a>
            </div>
            <table class="w-full text-left border-collapse" id="courses_tb">
                <thead class="bg-gray-50 text-gray-500 text-lg uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">ชื่อคอร์ส</th>
                        <th class="p-4 font-semibold">ราคา</th>
                        <th class="p-4 font-semibold">ผู้สร้าง</th>
                        <th class="p-4 font-semibold">สถานะ</th>
                        <th class="p-4 font-semibold flex justify-center ">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($courses as $course)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                    @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail"
                                        class="w-full h-full object-cover rounded-lg">
                                    @else
                                    <i class="fas fa-book-open text-2xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $course->title }}</p>
                                    <p class="text-xs text-gray-400">{{ Str::limit($course->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-gray-700">
                            ฿{{ number_format($course->price, 2) }}
                        </td>
                        <td class="p-4 italic">
                            {{ $course->user->name ?? 'N/A' }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                Active
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('quizzes.index', ['course' => $course->id]) }}"
                                    class="text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center bg-orange-50 px-3 py-1 rounded-lg transition">
                                    <i class="fas fa-file-alt mr-2"></i> แบบทดสอบ
                                </a>
                                <a href="{{ route('lessons.index', $course->id) }}"
                                    class="text-sm font-semibold text-purple-600 hover:text-purple-700 flex items-center bg-purple-50 px-3 py-1 rounded-lg transition">
                                    <i class="fas fa-cog mr-2"></i> จัดการบทเรียน
                                </a>
                                <a href="{{ route('courses.edit', $course->id) }}"
                                    class="p-2 text-yellow-500 hover:bg-yellow-100 rounded-lg transition bg-yellow-100"
                                    title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบคอร์สนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 bg-red-100 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-4xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400">ยังไม่มีคอร์สเรียนในระบบ</p>
                                <a href="{{ route('courses.create') }}"
                                    class="mt-2 text-blue-500 hover:underline">คลิกเพื่อเพิ่มคอร์สแรกของคุณ</a>
                            </div>
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
        if ('{{$courses}}'.trim() !== '[]') {
            $('#courses_tb').DataTable();
        }
    });

</script>
@endsection
