@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">เพิ่มคำถามใน : {{ $quiz->title }}</h3>
                <p class="text-sm text-gray-500">เพิ่มเนื้อหาคำถามและตัวเลือก</p>
            </div>
        </div>
        <div class="space-y-3 mt-3">
            <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST">
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
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.questions.index', $quiz->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
