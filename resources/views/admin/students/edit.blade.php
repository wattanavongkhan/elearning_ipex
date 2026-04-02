@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="space-y-4 p-6">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">แก้ไขผู้ใช้งาน</h3>
                    <p class="text-sm text-gray-500">ข้อมูลผู้ใช้งานใหม่</p>
                </div>
                <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-800 transition ">
                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                </a>
            </div>

            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ชื่อ-นามสกุล <b class="text-red-500">*</b></label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}"
                            placeholder="ระบุชื่อจริง-นามสกุล" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Email <b class="text-red-500">*</b></label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                            placeholder="ระบุอีเมล" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">เบอร์โทรศัพท์ <b class="text-red-500">*</b></label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                            placeholder="ระบุเบอร์โทรศัพท์" class="w-full border rounded-lg p-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ที่อยู่ <b class="text-red-500">*</b></label>
                        <input type="text" name="address" value="{{ old('address', $student->address) }}"
                            placeholder="ระบุที่อยู่" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">จังหวัด <b class="text-red-500">*</b></label>
                        <select name="province" id="province" class="w-full border rounded-lg p-2" required>
                            <option value="">-- เลือกจังหวัด --</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->PROVINCE_ID }}"
                                {{ old('province', $student->province) == $province->PROVINCE_ID ? 'selected' : '' }}>
                                {{ $province->PROVINCE_NAME }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">อำเภอ {{$student->amphur}} <b
                                class="text-red-500">*</b></label>
                        <select name="amphurs" id="amphurs" class="w-full border rounded-lg p-2"
                            onchange="getDistricts()" required>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">แขวง/ตำบล <b class="text-red-500">*</b></label>
                        <select name="district" id="district" class="w-full border rounded-lg p-2" required
                            value="{{ old('district', $student->district) }}">
                            <option value="">-- เลือกแขวง/ตำบล --</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">รหัสไปรษณีย์ <b class="text-red-500">*</b></label>
                        <input type="text" name="zipcode" value="{{ old('zipcode', $student->zipcode) }}"
                            placeholder="ระบุรหัสไปรษณีย์" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ตำแหน่ง <b class="text-red-500">*</b></label>
                        <select name="status" id="status" class="w-full border rounded-lg p-2" required>
                            <option value="">-- เลือกตำแหน่ง --</option>
                            <option value="2" {{ old('status', $student->status) == '2' ? 'selected' : '' }}>Student
                            </option>
                            <option value="1" {{ old('status', $student->status) == '1' ? 'selected' : '' }}>Teacher
                            </option>
                        </select>
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">สถานะ <b class="text-red-500">*</b></label>
                        <select name="status_activate" id="status_activate" class="w-full border rounded-lg p-2"
                            required>
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="1"
                                {{ old('status_activate', $student->status_activate) == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0"
                                {{ old('status_activate', $student->status_activate) == '0' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block uppercase font-bold mb-2">รหัสผ่าน <b class="text-red-500">*</b></label>
                        <input type="password" name="password" value="{{ old('password', $student->password_member) }}"
                            placeholder="ระบุรหัสผ่าน" class="w-full border rounded-lg p-2" onkeyup="checkPassword()"
                            required>
                    </div>
                    <div>
                        <label class="block uppercase font-bold mb-2">ยืนยันรหัสผ่านอีกครั้ง <b
                                class="text-red-500">*</b></label>
                        <input type="password" name="password_confirmation" placeholder="ระบุรหัสผ่านเดิม"
                            value="{{ old('password_confirmation', $student->password_member) }}"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                </div>

                <input type="text" name="id" id="id" value="{{ $student->id }}" hidden>
                @error('password') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('students.index') }}"
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
@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2-ajax').select2();
        getAmphurs();

    });

    function getAmphurs() {
        const provinceCode = document.getElementById('province').value;
        const amphursSelect = document.getElementById('amphurs');

        if (provinceCode) {
            fetch(`/amphurs/${provinceCode}`)
                .then(response => response.json())
                .then(data => {
                    amphursSelect.innerHTML = '<option value="">-- เลือกเขต/อำเภอ --</option>';
                    data.forEach(amphur => {
                        const option = document.createElement('option');
                        option.value = amphur.AMPHUR_ID;
                        option.textContent = amphur.AMPHUR_NAME;

                        if (option.value == "{{ old('amphurs', $student->amphur) }}") {
                            option.selected = true;
                        }
                        amphursSelect.appendChild(option);
                    });

                    if (amphursSelect.value) {
                        getDistricts(defaultDistrictId = "{{ old('district', $student->district) }}");
                    }
                });
        }
        $('#amphurs').val("{{ old('amphurs', $student->amphur) }}");
        // getDistricts();
    }

    function getDistricts() {
        const amphurCode = document.getElementById('amphurs').value;
        const districtSelect = document.getElementById('district');
        // Clear existing options
        districtSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
        console.log('Selected amphur code:', amphurCode);
        if (amphurCode) {
            fetch(`/districts/${amphurCode}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.DISTRICT_ID;
                        option.textContent = district.DISTRICT_NAME;

                        if (option.value == "{{ old('district', $student->district) }}") {
                            option.selected = true;
                        }
                        districtSelect.appendChild(option);
                    });
                });
        }
    }

    function checkPassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        const password = passwordInput.value;
        const errorMessage = document.getElementById('password-error');

        // ตรวจสอบความยาวของรหัสผ่าน
        if (password.length < 8) {
            errorMessage.textContent = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
            return false;
        }

        // ตรวจสอบว่ามีตัวอักษรพิมพ์ใหญ่หรือไม่
        if (!/[A-Z]/.test(password)) {
            errorMessage.textContent = 'รหัสผ่านต้องมีตัวอักษรพิมพ์ใหญ่';
            return false;
        }

        // ตรวจสอบว่ามีตัวอักษรพิมพ์เล็กหรือไม่
        if (!/[a-z]/.test(password)) {
            errorMessage.textContent = 'รหัสผ่านต้องมีตัวอักษรพิมพ์เล็ก';
            return false;
        }

        // ตรวจสอบว่ามีตัวเลขหรือไม่
        if (!/\d/.test(password)) {
            errorMessage.textContent = 'รหัสผ่านต้องมีตัวเลข';
            return false;
        }

        // ถ้าผ่านการตรวจสอบทั้งหมด ให้ลบข้อความแสดงข้อผิดพลาด
        errorMessage.textContent = '';
        return true;
    }

</script>
@endsection
