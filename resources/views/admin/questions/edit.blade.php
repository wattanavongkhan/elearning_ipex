@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">แก้ไขคำถามใน: {{ $quiz->title }}</h3>
            <p class="text-sm text-gray-500">แก้ไขเนื้อหาคำถามและตัวเลือก</p>
        </div>
        <a href="{{ route('admin.questions.index', $quiz->id) }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.questions.update', [$quiz->id, $question->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block font-bold text-gray-700 mb-2">โจทย์คำถาม</label>
                <textarea name="question_text" rows="3" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition" required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @foreach(['A', 'B', 'C', 'D'] as $choice)
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ตัวเลือก {{ $choice }}</label>
                    <input type="text" 
                           name="options[{{ $choice }}]" 
                           value="{{ old('options.'.$choice, $question->options[$choice] ?? '') }}" 
                           class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition" 
                           required>
                </div>
                @endforeach
            </div>

            <div class="mb-8 p-4 bg-slate-50 rounded-xl">
                <label class="block font-bold text-gray-700 mb-3">เฉลยข้อที่ถูกต้อง</label>
                <div class="flex gap-4">
                    @foreach(['A', 'B', 'C', 'D'] as $choice)
                    <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-blue-500 transition">
                        <input type="radio" name="correct_answer" value="{{ $choice }}" 
                               {{ old('correct_answer', $question->correct_answer) == $choice ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600">
                        <span class="font-bold text-gray-700">ข้อ {{ $choice }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all active:scale-95">
                    <i class="fas fa-save mr-1"></i> บันทึกการแก้ไข
                </button>
                <a href="{{ route('admin.questions.index', $quiz->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-8 py-3 rounded-xl font-bold transition-all text-center">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection