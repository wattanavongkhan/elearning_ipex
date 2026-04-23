@extends('layouts.layout_admin') {{-- อ้างอิงจากไฟล์ Layout หลักที่เราสร้างไว้ --}}

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
            @csrf
            <div class="mb-5">
                <nav class="text-sm text-gray-500">
                    <h3 class="text-xl font-bold text-gray-700">สร้างคอร์สเรียนใหม่</h3>
                </nav>
                <a href="#" class="hover:text-blue-600 transition">เพิ่มคอร์สใหม่</a>

            </div>
            <div class="grid grid-cols-1 gap-y-6 mt-2">

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">ประเภทคอร์ส <span
                            class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-3 border @error('category_id') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white" required>
                        <option value="">-- กรุณาเลือกประเภทคอร์ส --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อคอร์สเรียน <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 border @error('title') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="เช่น Mastering Laravel 8 for Beginners">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Course Slug (URL)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" readonly
                        class="w-full px-4 py-3 border border-gray-100 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed outline-none"
                        placeholder="ระบบจะสร้างให้อัตโนมัติ...">
                    <p class="text-[10px] text-blue-500 mt-1 italic">ตัวอย่าง: elearning.test/courses/your-course-name
                    </p>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">รายละเอียดคอร์ส
                        <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5"
                        class="w-full px-4 py-3 border @error('description') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="อธิบายเนื้อหาที่นักเรียนจะได้รับ...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="benefits" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i> ประโยชน์ที่ได้รับ
                        </label>
                        <textarea name="benefits" id="benefits" rows="4"
                            class="w-full px-4 py-3 border @error('benefits') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น - เข้าใจพื้นฐาน Laravel&#10;- สร้างระบบ Login ได้เอง">{{ old('benefits') }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">* ขึ้นบรรทัดใหม่เพื่อแยกรายการ</p>
                    </div>

                    <div>
                        <label for="target_audience" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-users text-blue-500 mr-1"></i> คอร์สนี้เหมาะกับใคร
                        </label>
                        <textarea name="target_audience" id="target_audience" rows="4"
                            class="w-full px-4 py-3 border @error('target_audience') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น - นักศึกษาฝึกงาน&#10;- โปรแกรมเมอร์ระดับต้น">{{ old('target_audience') }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">* ขึ้นบรรทัดใหม่เพื่อแยกรายการ</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">ราคา (บาท) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">฿</span>
                            <input type="number" name="price" id="price" value="{{ old('price', 0) }}"
                                class="w-full pl-10 pr-4 py-3 border @error('price') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                placeholder="0.00">
                        </div>
                        @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="thumbnail"
                            class="block text-sm font-semibold text-gray-700 mb-2">รูปหน้าปกคอร์ส</label>
                        <input type="file" name="thumbnail" id="thumbnail"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition shadow-sm border border-gray-100 p-1 rounded-xl">
                        <p class="text-[10px] text-gray-400 mt-2 italic">* แนะนำขนาด 1280x720 px (JPG, PNG)</p>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <a href="{{ route('courses.index') }}"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                    <i class="fa fa-repeat" aria-hidden="true"></i> Cancel
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                    <i class="fas fa-save mr-2"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('title').addEventListener('input', function () {
        let title = this.value;
        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '') // ลบอักขระพิเศษ
            .replace(/\s+/g, '-') // เปลี่ยนช่องว่างเป็นขีด
            .replace(/-+/g, '-'); // ลบขีดซ้ำ
        document.getElementById('slug').value = slug;
    });

</script>
@endsection
