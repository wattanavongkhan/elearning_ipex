@extends('layouts.layout_admin') {{-- อ้างอิงจากไฟล์ Layout หลักที่เราสร้างไว้ --}}

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="mb-5">
        <nav class="text-sm text-gray-500 mb-1">
            <a href="{{ route('lecturer.index') }}" class="hover:text-blue-600 transition">จัดการข้อมูลอาจารย์</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-medium">เพิ่มข้อมูลอาจารย์ใหม่</span>
        </nav>
        <h3 class="text-xl font-bold text-gray-700">สร้างข้อมูลอาจารย์ใหม่</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('lecturer.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-1 gap-y-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ-สกุล <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น Mastering Laravel 8 for Beginners">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น example@example.com">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">เบอร์โทรศัพท์ <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น 0812345678">
                        @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">ที่อยู่ <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                            class="w-full px-4 py-3 border @error('address') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น 123 ถนนสุขุมวิท แขวงบางนา เขตบางนา กรุงเทพฯ">
                        @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">จังหวัด <span
                                class="text-red-500">*</span></label>
                        <select
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            name="province" id="province" onchange="fetchDistricts(this.value)">
                            <option value="">-- เลือกจังหวัด --</option>
                            @foreach($privance as $item)
                            <option value="{{ $item->code }}">{{ $item->name_in_thai }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">อำเภอ <span
                                class="text-red-500">*</span></label>
                        <select
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition "
                            name="district" id="district" onchange="fetchSubdistricts(this.value)">
                            <option value="">-- เลือกอำเภอ --</option>
                        </select>
                    </div>
                    <div>
                        <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">ตำบล <span
                                class="text-red-500">*</span></label>
                        <select
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition "
                            name="subdistrict" id="subdistrict" onchange="fetchZipcode(this.value)">
                            <option value="">-- เลือกตำบล --</option>
                        </select>
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">รหัสไปรษณีย์ <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode') }}"
                            class="w-full px-4 py-3 border @error('zipcode') border-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="เช่น 10110">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">รูปโปรไฟล์</label>
                        <input type="file" name="thumbnail" id="thumbnail"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition shadow-sm border border-gray-100 p-1 rounded-xl">
                        <p class="text-[10px] text-gray-400 mt-2 italic">* JPG, PNG</p>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                <a href="{{ route('courses.index') }}"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold px-10 py-3 rounded-xl shadow-lg shadow-red-200 transition-all active:scale-95">
                   <i class="fas fa-times mr-2"></i> Cancel
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
@section('scripts')
<script>
    function fetchDistricts(provinceCode) {
        $.ajax({
            url: '/districts/' + provinceCode,
            method: 'GET',
            success: function (data) {
                $('#district').empty();
                $('#district').append('<option value="">-- เลือกอำเภอ --</option>');
                $.each(data, function (key, value) {
                    $('#district').append('<option value="' + value.id + '">' + value.name_in_thai +
                        '</option>');
                });
            },
            error: function (xhr, status, error) {
                console.log('Error fetching districts:', error);
            }
        });
    }

    function fetchSubdistricts(districtCode) {
        $.ajax({
            url: '/subdistricts/' + districtCode,
            method: 'GET',
            success: function (data) {
                $('#subdistrict').empty();
                $('#subdistrict').append('<option value="">-- เลือกตำบล --</option>');
                $.each(data, function (key, value) {
                    $('#subdistrict').append('<option value="' + value.code + '">' + value
                        .name_in_thai + '</option>');
                });
            },
            error: function (xhr, status, error) {
                console.log('Error fetching subdistricts:', error);
            }
        });
    }

    function fetchZipcode(subdistrictCode) {
        $.ajax({
            url: '/zipcode/' + subdistrictCode,
            method: 'GET',
            success: function (data) {

                $('#zipcode').val(data.zip_code);
            },
            error: function (xhr, status, error) {
                console.log('Error fetching zipcode:', error);
            }
        });
    }

</script>
@endsection
