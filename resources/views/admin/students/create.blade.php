@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">เพิ่มผู้ใช้งาน</h3>
            <p class="text-sm text-gray-500">ข้อมูลผู้ใช้งานใหม่</p>
        </div>
        <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left"></i> ย้อนกลับ
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="space-y-4 p-6">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ชื่อ-นามสกุล <b class="text-red-500">*</b></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="ระบุชื่อจริง-นามสกุล"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Email <b class="text-red-500">*</b></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="ระบุอีเมล"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">เบอร์โทรศัพท์ <b class="text-red-500">*</b></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="ระบุเบอร์โทรศัพท์"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ที่อยู่ <b class="text-red-500">*</b></label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="ระบุที่อยู่"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">จังหวัด <b class="text-red-500">*</b></label>
                        <select name="province" id="province" class="w-full border rounded-lg p-2 select2-ajax"
                            onchange="getAmphurs()" required>
                            <option value="">-- เลือกจังหวัด --</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name_in_thai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">เขต/อำเภอ <b class="text-red-500">*</b></label>
                        <select name="amphurs" id="amphurs" class="w-full border rounded-lg p-2"
                            onchange="getDistricts()" required>
                            <option value="">-- เลือกเขต/อำเภอ --</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="mb-4">
                        <label class="block font-bold mb-2">รหัสไปรษณีย์ <b class="text-red-500">*</b></label>
                        <input type="text" name="zipcode" value="{{ old('zipcode') }}"
                            placeholder="ระบุรหัสไปรษณีย์" class="w-full border rounded-lg p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">ตำแหน่ง <b class="text-red-500">*</b></label>
                        <select name="status" id="status" class="w-full border rounded-lg p-2" required>
                            <option value="">-- เลือกตำแหน่ง --</option>
                            <option value="2">Student</option>
                            <option value="1">Teacher</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Status <b class="text-red-500">*</b></label>
                        <select name="status_active" id="status_active" class="w-full border rounded-lg p-2" required>
                            <option value="">-- เลือกสถานะ --</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block uppercase font-bold mb-2">รหัสผ่าน <b class="text-red-500">*</b></label>
                        <input type="password" name="password" value="{{ old('password') }}" placeholder="ระบุรหัสผ่าน"
                            class="w-full border rounded-lg p-2" onkeyup="checkPassword()" required>
                    </div>
                    <div>
                        <label class="block uppercase font-bold mb-2">ยืนยันรหัสผ่านอีกครั้ง <b
                                class="text-red-500">*</b></label>
                        <input type="password" name="password_confirmation" placeholder="ระบุรหัสผ่านเดิม"
                            class="w-full border rounded-lg p-2" required>
                    </div>
                </div>
                <input type="text" name="id" id="id" value="{{ old('id') }}">
                
                @error('password') <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p> @enderror
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('students.index') }}"
                        class="text-gray-500 font-semibold px-6 py-3 hover:text-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-10 py-3 rounded-xl shadow-lg transition-all active:scale-95">
                        <i class="fas fa-save mr-2"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
    $(document).ready(function () {
        $('.select2-ajax').select2();
    });

    function getAmphurs() {
        const provinceCode = document.getElementById('province').value;
        const amphursSelect = document.getElementById('amphurs');
        // Clear existing options
        amphursSelect.innerHTML = '<option value="">-- เลือกเขต/อำเภอ --</option>';
        if (provinceCode) {
            // Fetch amphurs based on province code
            fetch(`/amphurs/${provinceCode}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(amphur => {
                        const option = document.createElement('option');
                        option.value = amphur.AMPHUR_CODE;
                        option.textContent = amphur.AMPHUR_NAME;
                        amphursSelect.appendChild(option);
                    });
                });
        }
    }

    function getDistricts() {
        const amphurCode = document.getElementById('amphurs').value;

        // Clear existing options
        districtSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
        console.log('Selected amphur code:', amphurCode);
        if (amphurCode) {
            // Fetch districts based on amphur code
            fetch(`/districts/${amphurCode}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name_in_thai;
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
