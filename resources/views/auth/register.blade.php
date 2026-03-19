{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
@csrf

<!-- Name -->
<div>
    <x-label for="name" :value="__('Name')" />

    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
</div>

<!-- Email Address -->
<div class="mt-4">
    <x-label for="email" :value="__('Email')" />

    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
</div>

<!-- Password -->
<div class="mt-4">
    <x-label for="password" :value="__('Password')" />

    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
        autocomplete="new-password" />
</div>

<!-- Confirm Password -->
<div class="mt-4">
    <x-label for="password_confirmation" :value="__('Confirm Password')" />

    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
        required />
</div>

<div class="flex items-center justify-end mt-4">
    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
        {{ __('Already registered?') }}
    </a>

    <x-button class="ml-4">
        {{ __('Register') }}
    </x-button>
</div>
</form>
</x-auth-card>
</x-guest-layout> --}}
@extends('layouts.layout_login')

@section('content')


<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-5 bg-slate-900 text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-xl mb-4 shadow-lg shadow-blue-500/30">
                <i class="fas fa-user-plus text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">สร้างบัญชีใหม่</h2>
            <p class="text-slate-400 text-sm mt-2">เริ่มก้าวแรกสู่การเป็นมืออาชีพไปกับเรา</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="p-5 space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">ชื่อ-สกุล ผู้ใช้งาน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input type="text" name="phone" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••" oninput="checkPasswordStandard(this.value)">
                </div>
                <div class="mt-2 text-sm">
                    <p id="length-check" class="text-red-500"><i class="fas fa-times"></i> อย่างน้อย 8 ตัวอักษร</p>
                    <p id="uppercase-check" class="text-red-500"><i class="fas fa-times"></i> มีตัวอักษรพิมพ์ใหญ่</p>
                    <p id="lowercase-check" class="text-red-500"><i class="fas fa-times"></i> มีตัวอักษรพิมพ์เล็ก</p>
                    <p id="number-check" class="text-red-500"><i class="fas fa-times"></i> มีตัวเลข</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">ยืนยันรหัสผ่าน</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password_confirmation" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                ลงทะเบียนตอนนี้
            </button>

            <p class="text-center text-sm text-gray-500">
                เป็นสมาชิกอยู่แล้ว? <a href="{{ route('login') }}"
                    class="text-blue-600 font-semibold hover:underline">เข้าสู่ระบบ</a>
            </p>
        </form>
    </div>
</div>

@endsection
