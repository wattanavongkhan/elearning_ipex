@extends('layouts.layout_admin')

@section('content')
<div class="container-fluid">
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5">
        <div class="overflow-x-auto">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-xl font-bold text-black-800">จัดการหมวดหมู่</h3>
                    <p class="text-gray-500 text-sm">รวมหมวดหมู่ทั้งหมดในระบบที่คุณดูแลอยู่</p>
                </div>
                <a href="{{ route('categories.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
                    <i class="fas fa-plus"></i> เพิ่มหมวดหมู่ใหม่
                </a>
            </div>

            <table class="w-full text-left border-collapse" id="categories_tb">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">No.</th>
                        <th class="p-4 font-semibold">ชื่อหมวดหมู่</th>
                        <th class="p-4 font-semibold">รายละเอียด</th>
                        <th class="p-4 font-semibold">วันที่สร้าง</th>
                        <th class="p-4 font-semibold text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">{{ $category->category_name }}</td>
                        <td class="p-4">{{ Str::limit($category->description, 50) }}</td>
                        <td class="p-4">{{ $category->created_at->format('M d, Y') }}</td>
                        <td class="p-4 flex text-center gap-3">
                            <a href="{{ route('categories.edit', $category->id) }}"
                                class="bg-yellow-50 text-yellow-500 hover:bg-yellow-100 hover:text-yellow-700 transition px-3 py-1.5 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition px-3 py-1.5 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            No categories found. <a href="{{ route('categories.create') }}"
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
@section('scripts')
<script>
    $(document).ready(function () {
        if ('{{$categories}}'.trim() !== '[]') {
            $('#categories_tb').DataTable();
        }
    });

</script>
@endsection
