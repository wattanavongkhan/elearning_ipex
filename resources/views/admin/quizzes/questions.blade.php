@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-6">
        <nav class="text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.questions.index', $quiz->id) }}" class="hover:text-blue-600 transition">จัดการคำถาม</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">เพิ่มข้อสอบใหม่</span>
        </nav>
        <h3 class="text-2xl font-bold text-gray-800">เพิ่มข้อสอบสำหรับ: <span class="text-blue-600">{{ $quiz->title }}</span></h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">โจทย์คำถาม</label>
                    <textarea name="question_text" rows="3" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="ระบุโจทย์คำถาม..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach(['A', 'B', 'C', 'D'] as $choice)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-gray-500">ตัวเลือก {{ $choice }}</span>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="correct_answer" value="{{ $choice }}" required class="w-4 h-4 text-blue-600">
                                <span class="ml-2 text-xs font-bold text-blue-600 uppercase">เฉลยข้อนี้</span>
                            </label>
                        </div>
                        <input type="text" name="options[{{ $choice }}]" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 outline-none" placeholder="ระบุคำตอบข้อ {{ $choice }}">
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('admin.questions.index', $quiz->id) }}" class="px-6 py-3 text-gray-500 font-bold">ยกเลิก</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg transition-all active:scale-95">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection