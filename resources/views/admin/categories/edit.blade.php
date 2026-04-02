@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">แก้ไขหมวดหมู่</h3>
                <p class="text-sm text-gray-500">รายละเอียดข้อมูลหมวดหมู่</p>
            </div>
        </div>
        <div class="space-y-4">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block font-bold mb-2">ชื่อหมวดหมู่</label>
                    <input type="text" name="category_name" class="w-full border rounded-lg p-2"
                        value="{{ $category->category_name }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-bold mb-2">คำอธิบาย</label>
                    <textarea name="description"
                        class="w-full border rounded-lg p-2">{{ $category->description }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-gray-700 mb-2">รูปภาพหน้าปก (ถ้ามี)</label>
                    @if($category->thumbnail)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $category->thumbnail) }}"
                            class="w-40 h-24 object-cover rounded-lg shadow">
                        <p class="text-xs text-gray-400 mt-1">รูปปัจจุบัน</p>
                    </div>
                    @endif
                    <input type="file" name="thumbnail"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">

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
