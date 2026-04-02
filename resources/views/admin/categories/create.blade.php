@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">เพิ่มหมวดหมู่</h3>
                <p class="text-sm text-gray-500">รายละเอียดหมวดหมู่</p>
            </div>
        </div>

        <div class="space-y-4">
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
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
                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('categories.index') }}"
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
</div>
@endsection
