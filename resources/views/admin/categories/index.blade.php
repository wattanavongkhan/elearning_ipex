@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">จัดการหมวดหมู่</h3>
            <p class="text-gray-500 text-sm">รวมหมวดหมู่ทั้งหมดในระบบที่คุณดูแลอยู่</p>
        </div>
        <a href="{{ route('categories.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
            <i class="fas fa-plus"></i> เพิ่มหมวดหมู่ใหม่
        </a>
    </div>
    @if(session('success'))
    <div
        class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">No.</th>
                        <th class="p-4 font-semibold">Category Name</th>
                        <th class="p-4 font-semibold">Description</th>
                        <th class="p-4 font-semibold">Created At</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">{{ $category->category_name }}</td>
                        <td class="p-4">{{ Str::limit($category->description, 50) }}</td>
                        <td class="p-4">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="p-4 flex items-center justify-center gap-3">
                            <a href="{{ route('categories.edit', $category->id) }}" class="bg-yellow-200 text-yellow-500 hover:bg-yellow-600 rounded-lg p-2 transition"
                                title="แก้ไขหมวดหมู่นี้">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-400 text-white hover:bg-red-600 rounded-lg p-2 transition"
                                    title="ลบหมวดหมู่นี้">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            No categories found. <a href=""
                                class="text-blue-600 hover:underline">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
</div>
@endsection
