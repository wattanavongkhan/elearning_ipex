@extends('layouts.layout_admin') {{-- อ้างอิงจากไฟล์ Layout หลักที่เราสร้างไว้ --}}

@section('content')
<div class="container-fluid">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">จัดการไฟล์ส่วนตัว</h3>
            <p class="text-gray-500 text-sm">รวมไฟล์ส่วนตัวทั้งหมดในระบบที่คุณดูแลอยู่</p>
        </div>
        <a href="{{ route('privatefiles.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-lg transition-all active:scale-95">
            <i class="fas fa-plus"></i> เพิ่มไฟล์ใหม่
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
                        <th class="p-4 font-semibold">ชื่อไฟล์</th>
                        <th class="p-4 font-semibold">ขนาดไฟล์</th>
                        <th class="p-4 font-semibold">ผู้อัปโหลด</th>
                        <th class="p-4 font-semibold">วันที่อัปโหลด</th>
                        <th class="p-4 font-semibold text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                    @forelse($privatefiles as $file)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                        class="flex items-center gap-3 bg-blend-color bg-green-100 p-2 rounded-lg hover:bg-gray-200 transition">
                                        <p class="font-bold text-gray-800">{{ $file->file_name }}</p>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-gray-700">
                            {{ number_format($file->size_file / 1024, 2) }} KB
                        </td>
                        <td class="p-4 italic">
                            {{ $file->user_name ?? 'N/A' }}
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                {{ $file->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('privatefiles.destroy', $file->id) }}" method="POST"
                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบไฟล์นี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-500 hover:bg-red-100 rounded-lg transition bg-red-100"
                                        title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-4xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400">ยังไม่มีไฟล์ส่วนตัวในระบบ</p>
                                <a href="{{ route('privatefiles.create') }}"
                                    class="mt-2 text-blue-500 hover:underline">คลิกเพื่อเพิ่มไฟล์แรกของคุณ</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($privatefiles->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-100">
            {{ $privatefiles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
