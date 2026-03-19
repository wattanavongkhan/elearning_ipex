@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="text-sm text-gray-500 mb-1">
                <a href="{{ route('courses.index') }}" class="hover:text-blue-600 transition">จัดการคอร์สเรียน</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">จัดการแบบทดสอบ</span>
            </nav>
            <h3 class="text-2xl font-bold text-gray-800">แบบทดสอบของคอร์ส: <span
                    class="text-blue-600">{{ $course->title }}</span></h3>
        </div>

        <a href="{{ route('quizzes.create', $course->id) }}"
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg shadow-orange-100 transition-all active:scale-95">
            <i class="fas fa-plus-circle"></i> สร้างชุดข้อสอบใหม่
        </a>
    </div>

    @if(session('success'))
    <div
        class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">ชื่อชุดข้อสอบ</th>
                        <th class="p-4 font-semibold">ประเภท</th>
                        <th class="p-4 font-semibold text-center">จำนวนข้อ</th>
                        <th class="p-4 font-semibold text-center">จัดการคำถาม</th>
                        <th class="p-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($quizzes as $quiz)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <span class="font-bold text-gray-800">{{ $quiz->title }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($quiz->type == 'pre-test')
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">Pre-Test</span>
                            @else
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 uppercase">Post-Test</span>
                            @endif
                        </td>
                        <td class="p-4 text-center font-semibold">
                            {{ $quiz->questions_count }} ข้อ
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('admin.questions.index', $quiz->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-bold text-xs">
                                <i class="fas fa-list-ol"></i> จัดการข้อสอบ ({{ $quiz->questions_count }})
                            </a>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}"
                                    class="p-2 text-yellow-500 hover:bg-yellow-100 rounded-lg transition shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
                                    onsubmit="return confirm('ยืนยันการลบชุดข้อสอบนี้?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-500 hover:bg-red-100 rounded-lg transition shadow-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-clipboard-check text-4xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400 font-medium">ยังไม่มีชุดข้อสอบในคอร์สนี้</p>
                                <a href="{{ route('quizzes.create', $course->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg shadow-blue-100 transition-all active:scale-95">
                                    <i class="fas fa-plus"></i> สร้างชุดข้อสอบ
                                </a>
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
