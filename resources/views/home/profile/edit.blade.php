@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24 font-kanit">

    <div class="mt-3">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-500 text-white rounded-2xl shadow-lg flex items-center animate-bounce">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif

           <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-200">
                    <h2 class="text-xl font-black text-slate-800 mb-6 flex items-center">
                        <span
                            class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3 text-sm">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        ข้อมูลพื้นฐาน
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">ชื่อ-นามสกุล</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">อีเมล</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-5">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">ที่อยู่</label>
                            <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-5">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">จังหวัด</label>
                            <select name="province"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                                <option value="">-- เลือกจังหวัด --</option>
                                @foreach (App\Models\Province::get() as $item)
                                <option value="{{$item->PROVINCE_ID}}"
                                    {{ old('province', $user->province ?? '') == $item->PROVINCE_ID ? 'selected' : '' }}>
                                    {{$item->PROVINCE_NAME}}</option>
                                @endforeach
                            </select>
                            @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">อำเภอ</label>
                            <select name="amphur"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                                <option value="">-- เลือกอำเภอ --</option>
                                @foreach (App\Models\Amphur::get() as $item)
                                <option value="{{$item->AMPHUR_ID}}"
                                    {{ old('amphur', $user->amphur ?? '') == $item->AMPHUR_ID ? 'selected' : '' }}>
                                    {{$item->AMPHUR_NAME}}</option>
                                @endforeach
                            </select>
                            @error('amphur') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-5">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">ตำบล</label>
                            <select name="district"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                                <option value="">-- เลือกตำบล --</option>
                                @foreach (App\Models\District::get() as $item)
                                <option value="{{$item->DISTRICT_ID}}"
                                    {{ old('district', $user->district ?? '') == $item->DISTRICT_ID ? 'selected' : '' }}>
                                    {{$item->DISTRICT_NAME}}</option>
                                @endforeach
                            </select>
                            @error('district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">รหัสไปรษณีย์</label>
                            <input type="tel" name="zipcode" value="{{ old('zipcode', $user->zipcode ?? '') }}"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        </div>
                    </div>

                    <div class="space-y-2 mt-5">
                        <label class="text-sm font-bold text-slate-500 ml-1">รูปโปรไฟล์</label>
                        <div class="flex items-center gap-4">
                            @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                                class="w-20 h-20 rounded-2xl object-cover">
                            @else
                            <div class="w-20 h-20 rounded-2xl bg-slate-200 flex items-center justify-center">
                                <i class="fas fa-user text-slate-400 text-2xl"></i>
                            </div>
                            @endif
                            <input type="file" name="profile_image" accept="image/*"
                                class="flex-1 px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        </div>
                        @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <h2 class="text-xl font-black text-slate-800 mb-6 mt-5 flex items-center">
                        <span
                            class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3 text-sm">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        เปลี่ยนรหัสผ่าน
                    </h2>

                    <p class="text-slate-400 text-sm mb-6 bg-slate-50 p-4 rounded-xl border-l-4 border-amber-400">
                        * หากไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นว่างช่องด้านล่างนี้ไว้
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">รหัสผ่านใหม่</label>
                            <input type="password" name="password"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-500 ml-1">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-700">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-3">
                    <button type="submit"
                        class="px-10 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-200 transition-all transform active:scale-95">
                        <i class="fas fa-save mr-2"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
