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
            <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-2">โจทย์คำถาม</label>
                    <textarea name="question_text" class="w-full border rounded-lg p-2" required></textarea>
                </div>

                <div class="bg-blue-50/50 rounded-[1rem] p-8 border-2 border-dashed border-blue-100 group">
                    <label
                        class="block text-[11px] font-black text-blue-400 uppercase tracking-widest mb-4 ml-1">Question
                        Image (Optional)</label>
                    <div class="flex items-center gap-6">
                        <div
                            class="size-20 bg-white rounded-2xl flex items-center justify-center text-blue-200 border border-blue-50 shadow-inner group-hover:text-blue-500 transition-colors">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="question_image" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-2 font-bold italic">* รองรับไฟล์ JPG, PNG
                                (ขนาดไม่เกิน 2MB)</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 mt-5">
                    @foreach(['A', 'B', 'C', 'D'] as $choice)
                    <div>
                        <label class="block text-sm font-bold">ตัวเลือก {{ $choice }}</label>
                        <input type="text" name="options[{{ $choice }}]" class="w-full border rounded-lg p-2" required>
                        <input type="file" name="option_images[{{ $choice }}]" accept="image/*"
                            class="block w-full text-[9px] text-slate-400 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-slate-200 file:text-slate-600 cursor-pointer mt-3">
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
