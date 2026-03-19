@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-6">
        <nav class="text-sm text-gray-500 mb-2">
            <a href="{{ route('courses.index') }}" class="hover:text-blue-600 transition">จัดการคอร์สเรียน</a>
            <span class="mx-2">/</span>
            <a href="{{ route('quizzes.index', $course->id) }}" class="hover:text-blue-600 transition">จัดการแบบทดสอบ</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">สร้างชุดข้อสอบใหม่</span>
        </nav>
        <h3 class="text-2xl font-bold text-gray-800">สร้างชุดข้อสอบสำหรับ: <span class="text-blue-600">{{ $course->title }}</span></h3>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('quizzes.store', $course->id) }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 gap-y-8">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        ชื่อชุดข้อสอบ <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="เช่น แบบทดสอบก่อนเรียน บทที่ 1">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">ประเภทของข้อสอบ <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative flex p-4 cursor-pointer rounded-2xl border border-gray-100 bg-gray-50 hover:bg-blue-50/50 transition focus-within:ring-2 focus-within:ring-blue-500">
                            <input type="radio" name="type" value="pre-test" class="sr-only peer" {{ old('type') == 'pre-test' ? 'checked' : '' }} required>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center peer-checked:bg-blue-600 peer-checked:text-white transition">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Pre-test</p>
                                    <p class="text-xs text-gray-500">แบบทดสอบก่อนเรียนเพื่อวัดพื้นฐาน</p>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-600 rounded-2xl pointer-events-none"></div>
                        </label>

                        <label class="relative flex p-4 cursor-pointer rounded-2xl border border-gray-100 bg-gray-50 hover:bg-purple-50/50 transition focus-within:ring-2 focus-within:ring-purple-500">
                            <input type="radio" name="type" value="post-test" class="sr-only peer" {{ old('type') == 'post-test' ? 'checked' : '' }}>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center peer-checked:bg-purple-600 peer-checked:text-white transition">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Post-test</p>
                                    <p class="text-xs text-gray-500">แบบทดสอบหลังเรียนเพื่อประเมินผล</p>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-purple-600 rounded-2xl pointer-events-none"></div>
                        </label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <a href="{{ route('quizzes.index', $course->id) }}" class="text-gray-500 font-semibold px-6 py-3 hover:text-gray-700 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i class="fas fa-check-circle mr-2"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection