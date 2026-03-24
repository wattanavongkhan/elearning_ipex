@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-800">แก้ไขคอร์ส: {{ $course->title }}</h3>
        <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i> ยกเลิก
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
        <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-2">
                <div class="md:col-span-10">
                    <label class="block font-bold text-gray-700 mb-2">ชื่อคอร์สเรียน</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}"
                        class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                </div>

                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-2">สถานะ</label>
                    <select class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none" name="status" id="status" >
                        <option value="0">รอดำเนินการ</option>
                        <option value="1">ส่งอนุมัติ</option>
                        @if(Auth::user()->status=="3")
                        <option value="2">อนุมัติ</option>
                        <option value="3">ไม่อนุมัติ</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block font-bold text-gray-700 mb-2">รายละเอียดคอร์ส</label>
                <textarea name="description" rows="5"
                    class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $course->description) }}</textarea>
            </div>
            <div class="mb-8">
                <label class="block font-bold text-gray-700 mb-2">รูปภาพหน้าปก (ถ้ามี)</label>
                @if($course->thumbnail)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                        class="w-40 h-24 object-cover rounded-lg shadow">
                    <p class="text-xs text-gray-400 mt-1">รูปปัจจุบัน</p>
                </div>
                @endif
                <input type="file" name="thumbnail"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div class="mb-6">
                <label class="block font-bold text-gray-700 mb-2">ไฟล์ประกอบ (ถ้ามี)</label>
                <div class="flex gap-2">
                    <select name="file_id" id="file_id"
                        class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-เลือกไฟล์ประกอบ-</option>
                        @foreach ($privatefile as $file)
                        <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="addCodeInput(this)"
                        class="bg-green-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-green-600">Add</button>
                </div>

                <div class="mt-4">
                    <div id="file_show"></div>
                </div>
            </div>
            <div class="flex gap-4 border-t pt-6 text-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        showFile("{{ $course->id}}");
    });


    function addCodeInput(button) {
        const fileId = document.getElementById('file_id').value;
        if (!fileId) {
            alert('กรุณาเลือกไฟล์ประกอบก่อน');
            return;
        }

        $.ajax({
            method: "POST",
            url: '{{ url("courses/add_file")}}',
            data: {
                _token: "{{csrf_token()}}",
                course_id: "{{ $course->id}}",
                file_id: fileId
            },
            success: function (response) {
                showFile("{{ $course->id}}");
            }
        });

        showFile("{{ $course->id}}");
    }

    function showFile(courseId) {
        // Prepare AJAX GET
        fetch(`/courses/files/${courseId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const file = data.file;
                let html = '';
                data.forEach(f => {
                    html += `
                            <div class="flex items-center gap-3 bg-blend-color bg-green-100 p-2 rounded-lg hover:bg-gray-200 transition mb-2">
                                <p class="font-bold text-gray-800">${f.private_file_name}</p>
                                <button type="button" onclick="removeFile(${f.id})" class="ml-auto text-red-500 hover:bg-red-100 rounded-lg transition bg-red-100 p-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                });

                document.getElementById('file_show').innerHTML = html;

            })
            .catch(error => {
                // alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            });

    }

    function removeFile(fileId) {
        if (!fileId) {
            alert('ไม่พบไฟล์ที่ต้องการลบ');
            return;
        }

        $.ajax({
            method: "POST",
            url: '{{ url("courses/remove_file")}}',
            data: {
                _token: "{{csrf_token()}}",
                file_id: fileId,
            },
            success: function (response) {
                showFile("{{ $course->id}}");
            }
        });
    }

</script>
@endsection
