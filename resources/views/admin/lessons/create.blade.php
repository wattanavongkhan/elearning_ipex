@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-5">
        <nav class="text-sm text-gray-500 mb-1">
            <a href="{{ route('lessons.index', $course->id) }}" class="hover:text-blue-600 transition">จัดการบทเรียน</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">เพิ่มบทเรียน</span>
        </nav>
        <h3 class="text-xl font-bold text-gray-700">เพิ่มบทเรียนใหม่</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid grid-cols-1 gap-y-6">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อบทเรียน <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="เช่น บทที่ 1: แนะนำพื้นฐาน Laravel">
                </div>

                <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                    <h4 class="text-sm font-bold text-blue-700 mb-4 flex items-center">
                        <i class="fas fa-video mr-2"></i> แหล่งที่มาของวิดีโอ (เลือกอย่างใดอย่างหนึ่ง)
                    </h4>

                    <div class="grid grid-cols-1 gap-y-4">
                        <div>
                            <label for="video_url" class="block text-xs font-semibold text-gray-600 mb-1">Link วิดีโอ (Vimeo)</label>
                            <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                                placeholder="https://www.youtube.com/watch?v=...">
                        </div>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-200"></span></div>
                            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-gray-400 font-bold">หรือ</span></div>
                        </div>

                        <div>
                            <label for="video_file" class="block text-xs font-semibold text-gray-600 mb-1">อัปโหลดไฟล์วิดีโอ (MP4)</label>
                            <input type="file" name="video_file" id="video_file" accept="video/mp4"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">เนื้อหาเพิ่มเติม / คำอธิบาย</label>
                    <textarea name="content" id="content" rows="4"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="รายละเอียดหรือลิงก์ดาวน์โหลดเอกสารประกอบการเรียน...">{{ old('content') }}</textarea>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_free" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">เปิดให้ทดลองเรียนฟรี (Preview)</span>
                    </label>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <a href="{{ route('lessons.index', $course->id) }}" class="text-gray-500 font-semibold px-6 py-3 hover:text-gray-700 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i class="fas fa-save mr-2"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
