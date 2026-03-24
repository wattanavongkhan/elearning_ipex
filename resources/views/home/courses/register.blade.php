@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen py-16">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-slate-900 mb-4">สรุปการสั่งซื้อ</h1>
                <p class="text-slate-500 italic">อีกเพียงก้าวเดียว เพื่อเริ่มต้นการเรียนรู้ที่ยอดเยี่ยม</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start font-kanit">
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-blue-500"></i> รายละเอียดการลงทะเบียน
                        </h3>
                        <div class="flex gap-6 pb-6">
                            <img src="{{ asset('storage/'.$course->thumbnail) }}"
                                class="w-24 h-24 rounded-2xl object-cover border border-slate-100 shadow-sm">
                            <div>
                                <h4 class="font-bold text-slate-800 leading-tight mb-2">{{ $course->title }}</h4>
                                <p class="text-xs text-slate-400 italic">พร้อมให้คุณเรียนรู้ได้ทันที</p>
                                <div
                                    class="inline-flex items-center gap-2 bg-green-50 text-green-600 px-4 py-1.5 rounded-full mt-3">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                    <span class="text-sm font-black uppercase tracking-wider">Free Enrollment</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-900 p-8 rounded-[2.5rem] text-white relative overflow-hidden">
                        <i class="fas fa-star absolute -right-10 -bottom-10 text-[150px] opacity-10"></i>
                        <h4 class="text-xl font-bold mb-2">เข้าเรียนได้ตลอดชีพ</h4>
                        <p class="text-indigo-200 text-sm italic">ไม่มีค่าใช้จ่ายแอบแฝง
                            พัฒนาทักษะของคุณได้ทุกที่ทุกเวลาตามนโยบาย DX ของบริษัท</p>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div
                        class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/5 border border-slate-100 sticky top-24">
                        <h3 class="font-black text-slate-800 mb-8 uppercase tracking-widest text-sm text-slate-400">
                            สรุปรายการ</h3>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-slate-500 text-sm font-bold">
                                <span>ประเภทการสมัคร</span>
                                <span class="text-blue-600">สิทธิพนักงาน (I-PEX)</span>
                            </div>
                            <div class="flex justify-between text-slate-500 text-sm">
                                <span>ค่าธรรมเนียมคอร์ส</span>
                                <span
                                    class="line-through text-slate-300">฿{{ number_format($course->price ?? 990) }}</span>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex justify-between items-end">
                                <span class="text-slate-800 font-black">ยอดรวมสุทธิ</span>
                                <div class="text-right">
                                    <span
                                        class="block text-[10px] font-black text-green-500 uppercase">ไม่มีค่าใช้จ่าย</span>
                                    <span class="text-4xl font-black text-slate-900 leading-none">FREE</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('courses.register.submit', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                                ลงชื่อเข้าเรียนฟรี <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>

                        <p class="text-[10px] text-slate-400 text-center mt-6 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i> ระบบจะเปิดให้เข้าเรียนโดยอัตโนมัติทันทีที่กดยืนยัน
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
