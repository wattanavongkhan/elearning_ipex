@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">


    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">แก้ไขคอร์ส : {{ $course->title }}</h3>
                <p class="text-sm text-gray-500">รายละเอียดคอร์ส</p>
            </div>

        </div>
        <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-2">
                <div class="md:col-span-12">
                    <label class="block font-bold text-gray-700 mb-2">ชื่อคอร์สเรียน</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}"
                        class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
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
