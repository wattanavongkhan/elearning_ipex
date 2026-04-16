@extends('layouts.layout_admin')

@section('content')
<div class="bg-slate-10 max-h-screen font-kanit">
    <div class="container mx-auto max-w-12xl">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('admin.activities.index') }}"
                    class="text-blue-600 font-bold flex items-center mb-2 hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> กลับหน้าจัดการ
                </a>
                <h3 class="text-2xl font-black text-slate-800">สร้างกิจกรรมใหม่</h3>
            </div>
        </div>

        <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div
                        class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 sticky top-10 text-center">
                        <label class="block text-sm font-black text-slate-700 mb-4 uppercase">รูปภาพหน้าปก</label>
                        <div
                            class="relative group cursor-pointer aspect-video bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center">
                            <img id="preview" src="#" class="hidden w-full h-full object-cover">
                            <div id="placeholder" class="text-slate-400">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <p class="text-[10px] font-bold">คลิกเพื่ออัพโหลด</p>
                            </div>
                            <input type="file" name="image" id="thumbnail"
                                class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                        </div>
                        @error('thumbnail') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-[1rem] shadow-sm border border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-bold text-slate-500">ประเภทข้อมูล (Category)</label>
                                <select name="category" id="category-select"
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                                    <option value="News">ข่าวสารประชาสัมพันธ์ (News)</option>
                                    <option value="Activity">กิจกรรมองค์กร (Activity)</option>
                                </select>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-bold text-slate-500">หัวข้อข่าว/กิจกรรม</label>
                                <input type="text" name="title" required
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-medium shadow-inner">
                            </div>

                            <div id="event-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-500">วันที่จัดงาน</label>
                                    <input type="date" name="event_date"
                                        class="w-full px-5 py-4 bg-indigo-50/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-500">สถานที่</label>
                                    <input type="text" name="location" placeholder="เช่น Hall 1 หรือ ห้องประชุมชั้น 3"
                                        class="w-full px-5 py-4 bg-indigo-50/50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-bold text-slate-500">คำโปรยสั้นๆ (Short Description)</label>
                                <textarea name="short_description" rows="2"
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-bold text-slate-500">รายละเอียดเนื้อหา (Content)</label>
                                <textarea name="content" rows="6" id="editor"
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-2xl md:col-span-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1"
                                        class="rounded text-blue-600 mr-2">
                                    <span class="text-sm font-bold text-slate-600">ปักหมุดข่าวเด่น</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="status" value="1" checked
                                        class="rounded text-green-600 mr-2">
                                    <span class="text-sm font-bold text-slate-600">เผยแพร่ทันที</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                                <i class="fas fa-save mr-2"></i> Save
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. สลับการแสดงผลฟิลด์กิจกรรม
    const categorySelect = document.getElementById('category-select');
    const eventFields = document.getElementById('event-fields');

    categorySelect.addEventListener('change', function () {
        if (this.value === 'Activity') {
            eventFields.classList.remove('hidden');
        } else {
            eventFields.classList.add('hidden');
        }
    });

    // 2. Preview รูปภาพก่อนอัพโหลด
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('preview').classList.remove('hidden');
                document.getElementById('placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endsection
