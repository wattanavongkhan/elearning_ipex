@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">เพิ่มหมวดหมู่</h3>
            <p class="text-sm text-gray-500">เพิ่มหมวดหมู่ใหม่</p>
        </div>
        <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="space-y-4">
           <form action="{{ route('categories.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-2">ชื่อหมวดหมู่</label>
                    <input type="text" name="category_name" class="w-full border rounded-lg p-2" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">คำอธิบาย</label>
                    <textarea name="description" class="w-full border rounded-lg p-2"></textarea>
                </div>
                <div>
                    <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">รูปหน้าปกคอร์ส</label>
                    <input type="file" name="thumbnail" id="thumbnail"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition shadow-sm border border-gray-100 p-1 rounded-xl">
                    <p class="text-[10px] text-gray-400 mt-2 italic">* แนะนำขนาด 1280x720 px (JPG, PNG)</p>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
