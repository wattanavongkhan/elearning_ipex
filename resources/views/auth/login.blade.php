@extends('layouts.layout_login')
@section('content')

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 bg-slate-900 text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-blue-500 rounded-xl mb-4 shadow-lg shadow-blue-500/30">
                <i class="fas fa-graduation-cap text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">I-PEX Elearning</h2>
            <p class="text-slate-400 text-sm mt-2">กรุณาเข้าสู่ระบบเพื่อจัดการคอร์สเรียนของคุณ</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">อีเมลผู้ใช้งาน</label>
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
                    <input type="password" name="password" required
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
            </div>

            {{-- <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-600">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                    จดจำฉันไว้
                </label>
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">ลืมรหัสผ่าน?</a>
            </div> --}}

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98]">
                เข้าสู่ระบบ
            </button>

            {{-- <p class="text-center text-sm text-gray-500">
                ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">สมัครสมาชิกที่นี่</a>
            </p> --}}
        </form>
    </div>
</div>

@endsection
