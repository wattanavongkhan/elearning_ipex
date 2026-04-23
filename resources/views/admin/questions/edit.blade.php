@extends('layouts.layout_admin')
@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">แก้ไขคำถามใน : {{ $quiz->title }}</h3>
                <p class="text-sm text-gray-500">เนื้อหาคำถามและตัวเลือก</p>
            </div>
        </div>
        <form action="{{ route('admin.questions.update', [$quiz->id, $question->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-bold text-gray-700 mb-2">โจทย์คำถาม</label>
                <textarea name="question_text" rows="3"
                    class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition"
                    required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>
            <div class="bg-blue-50/50 rounded-[1rem] p-8 border-2 border-dashed border-blue-100 group">

                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-widest mb-4 ml-1">Question
                    Image (Optional)</label>
                <div class="flex items-center gap-6">
                    @if($question->question_image)
                    <div class="mb-3">
                        <img src="{{ asset($question->question_image) }}"
                            class="w-40 h-24 object-cover rounded-lg shadow">
                    </div>
                    @else
                    <div
                        class="size-20 bg-white rounded-2xl flex items-center justify-center text-blue-200 border border-blue-50 shadow-inner group-hover:text-blue-500 transition-colors">
                        <i class="fas fa-image text-3xl"></i>
                    </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="question_image" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold italic">* รองรับไฟล์ JPG, PNG
                            (ขนาดไม่เกิน 2MB)</p>
                    </div>
                </div>
                <br>
                <input type="checkbox" name="no_image" id="no_images" class="form-control mr-2">
                <label for="no_images">ไม่มีรูปภาพ</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 mt-5">
                @foreach(['A', 'B', 'C', 'D'] as $choice)
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ตัวเลือก {{ $choice }}</label>
                    <input type="text" name="options[{{ $choice }}]"
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
                    <label
                        class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-lg border border-gray-200 hover:border-blue-500 transition">
                        <input type="radio" name="correct_answer" value="{{ $choice }}"
                            {{ old('correct_answer', $question->correct_answer) == $choice ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600">
                        <span class="font-bold text-gray-700">ข้อ {{ $choice }}</span>
                    </label>
                    @endforeach
                </div>
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
@endsection
