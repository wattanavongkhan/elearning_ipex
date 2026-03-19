@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="text-sm text-gray-500 mb-1">
                <a href="{{ route('courses.index') }}" class="hover:text-blue-600">จัดการคอร์ส</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">จัดการบทเรียน</span>
            </nav>
            <h3 class="text-xl font-bold text-gray-700">บทเรียนของคอร์ส: <span class="text-blue-600">{{ $course->title }}</span></h3>
        </div>
        <a href="{{ route('lessons.create', $course->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i> เพิ่มบทเรียนใหม่
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-20">ลำดับ</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">ชื่อบทเรียน</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-32">ประเภทวิดีโอ</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-24 text-center">ดูฟรี</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 w-40 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($lessons as $lesson)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $lesson->position }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-800">{{ $lesson->title }}</div>
                        <div class="text-xs text-gray-400 mt-0.5 italic">{{ $lesson->slug }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($lesson->video_url)
                            <span class="px-2 py-1 text-[10px] bg-red-50 text-red-600 rounded-lg border border-red-100">YouTube</span>
                        @elseif($lesson->video_path)
                            <span class="px-2 py-1 text-[10px] bg-blue-50 text-blue-600 rounded-lg border border-blue-100">Storage</span>
                        @else
                            <span class="px-2 py-1 text-[10px] bg-gray-50 text-gray-400 rounded-lg border border-gray-100">ไม่มีวิดีโอ</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        {!! $lesson->is_free 
                            ? '<i class="fas fa-check-circle text-green-500"></i>' 
                            : '<i class="fas fa-lock text-gray-300"></i>' 
                        !!}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('lessons.edit', $lesson->id) }}" class="bg-yellow-50 text-yellow-500 hover:bg-yellow-100 hover:text-yellow-700 transition px-3 py-1.5 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบบทเรียนนี้?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">ยังไม่มีบทเรียนในคอร์สนี้</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection