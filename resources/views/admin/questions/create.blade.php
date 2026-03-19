@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
        <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">เพิ่มคำถามใน: {{ $quiz->title }}</h3>
            <p class="text-sm text-gray-500">เพิ่มเนื้อหาคำถามและตัวเลือก</p>
        </div>
        <a href="{{ route('admin.questions.index', $quiz->id) }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="space-y-4">
            <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-2">โจทย์คำถาม</label>
                    <textarea name="question_text" class="w-full border rounded-lg p-2" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    @foreach(['A', 'B', 'C', 'D'] as $choice)
                    <div>
                        <label class="block text-sm font-bold">ตัวเลือก {{ $choice }}</label>
                        <input type="text" name="options[{{ $choice }}]" class="w-full border rounded-lg p-2" required>
                    </div>
                    @endforeach
                </div>

                <div class="mb-6">
                    <label class="block font-bold mb-2">เฉลยข้อที่ถูกต้อง</label>
                    <select name="correct_answer" class="w-full border rounded-lg p-2">
                        <option value="A">ข้อ A</option>
                        <option value="B">ข้อ B</option>
                        <option value="C">ข้อ C</option>
                        <option value="D">ข้อ D</option>
                    </select>
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold">
                    บันทึกคำถาม
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
