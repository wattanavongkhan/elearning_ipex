@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-5">
        <nav class="text-sm text-gray-500 mb-1">
            <a href="{{ route('privatefiles.index') }}" class="hover:text-blue-600 transition">จัดการไฟล์ส่วนตัว</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">เพิ่มไฟล์ใหม่</span>
        </nav>
        <h3 class="text-xl font-bold text-gray-700">เพิ่มไฟล์ใหม่</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('privatefiles.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-3 gap-y-4">
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">ประเภทไฟล์</label>
                    <select name="file_type" id="file_type" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="col-span-2 pl-3">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อไฟล์ <b
                            class="text-red-500">PDF.,Excel</b> <span class="text-red-500">*</span></label>
                    <input type="file" name="file_name" id="file_name" value="{{ old('file_name') }}" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <a href="{{ route('privatefiles.index') }}"
                    class="text-gray-500 font-semibold px-6 py-3 hover:text-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i class="fas fa-save mr-2"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
