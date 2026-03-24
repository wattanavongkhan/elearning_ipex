@extends('layouts.layout_admin')

@section('content')
<div class="container mx-auto px-6 max-w-12xl">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.activities.index') }}"
                class="text-blue-600 font-bold flex items-center mb-2 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> กลับหน้าจัดการ
            </a>
            <h1 class="text-3xl font-black text-slate-800">แก้ไขข้อมูล: {{ $activity->title }}</h1>
        </div>
    </div>

    <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 sticky top-10 text-center">
                    <label class="block text-sm font-black text-slate-700 mb-4 uppercase">รูปภาพหน้าปก</label>

                    <div
                        class="relative group cursor-pointer aspect-video bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center">
                        <img id="preview" src="{{ asset('storage/' . $activity->thumbnail) }}"
                            class="w-full h-full object-cover">

                        <div id="placeholder" class="text-slate-400 hidden">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <p class="text-[10px] font-bold">คลิกเพื่อเปลี่ยนรูป</p>
                        </div>
                        <input type="file" name="image" id="thumbnail" class="absolute inset-0 opacity-0 cursor-pointer"
                            onchange="previewImage(this)">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-3 font-medium">คลิกที่รูปเพื่อเลือกไฟล์ใหม่</p>
                    @error('image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-500">ประเภทข้อมูล</label>
                            <select name="category" id="category-select"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                                <option value="News" {{ $activity->category == 'News' ? 'selected' : '' }}>
                                    ข่าวสารประชาสัมพันธ์</option>
                                <option value="Activity" {{ $activity->category == 'Activity' ? 'selected' : '' }}>
                                    กิจกรรมองค์กร</option>
                                <option value="Announcement"
                                    {{ $activity->category == 'Announcement' ? 'selected' : '' }}>ประกาศด่วน</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-500">หัวข้อ</label>
                            <input type="text" name="title" value="{{ old('title', $activity->title) }}" required
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-medium">
                        </div>

                        <div id="event-fields"
                            class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 {{ $activity->category != 'Activity' ? 'hidden' : '' }}">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-500 text-indigo-600">วันที่จัดงาน</label>
                                <input type="date" name="event_date"
                                    value="{{ $activity->event_date ? $activity->event_date->format('Y-m-d') : '' }}"
                                    class="w-full px-5 py-4 bg-indigo-50/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-500 text-indigo-600">สถานที่</label>
                                <input type="text" name="location" value="{{ old('location', $activity->location) }}"
                                    class="w-full px-5 py-4 bg-indigo-50/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-500">เนื้อหาหลัก</label>
                            <textarea name="content" rows="8"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">{{ old('content', $activity->content) }}</textarea>
                        </div>

                        <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-2xl md:col-span-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1"
                                    {{ $activity->is_featured ? 'checked' : '' }} class="rounded text-blue-600 mr-2">
                                <span class="text-sm font-bold text-slate-600">ปักหมุดข่าวเด่น</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" {{ $activity->status ? 'checked' : '' }}
                                    class="rounded text-green-600 mr-2">
                                <span class="text-sm font-bold text-slate-600">เปิดการแสดงผล</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                            <i class="fas fa-sync-alt mr-2"></i> อัปเดตข้อมูล
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Logic การสลับช่องกรอกข้อมูลเหมือนหน้า Create
    document.getElementById('category-select').addEventListener('change', function () {
        document.getElementById('event-fields').classList.toggle('hidden', this.value !== 'Activity');
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endsection
