@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen py-16">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-black text-slate-900 mb-4">สรุปการสั่งซื้อ</h1>
                <p class="text-slate-500 italic">อีกเพียงก้าวเดียว เพื่อเริ่มต้นการเรียนรู้ที่ยอดเยี่ยม</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-shopping-bag text-blue-500"></i> รายการคอร์ส
                        </h3>
                        <div class="flex gap-6 pb-6 border-b border-slate-50">
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" class="w-24 h-24 rounded-2xl object-cover border border-slate-100 shadow-sm">
                            <div>
                                <h4 class="font-bold text-slate-800 leading-tight mb-2">{{ $course->title }}</h4>
                                <p class="text-xs text-slate-400 italic">โดย ผู้เชี่ยวชาญระดับแนวหน้า</p>
                                <p class="text-lg font-black text-blue-600 mt-2">฿{{ number_format($course->price ?? 990) }}</p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 block">คุณมีรหัสส่วนลดไหม?</label>
                            <div class="flex gap-2">
                                <input type="text" placeholder="กรอกรหัสส่วนลด..." class="flex-1 bg-slate-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                                <button class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-blue-600 transition-colors">ใช้ส่วนลด</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-900 p-8 rounded-[2.5rem] text-white relative overflow-hidden">
                        <i class="fas fa-shield-alt absolute -right-10 -bottom-10 text-[150px] opacity-10"></i>
                        <h4 class="text-xl font-bold mb-2">รับประกันความพึงพอใจ</h4>
                        <p class="text-blue-200 text-sm">หากคุณไม่พอใจในคอร์สเรียน สามารถติดต่อขอคืนเงินได้ภายใน 7 วัน ตามนโยบายของทางบริษัท</p>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-blue-900/5 border border-slate-100 sticky top-24">
                        <h3 class="font-black text-slate-800 mb-8">รวมยอดชำระ</h3>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-slate-500 text-sm">
                                <span>ราคาปกติ</span>
                                <span class="line-through">฿{{ number_format($course->price * 1.2 ?? 1188) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 text-sm">
                                <span>ราคาขาย</span>
                                <span>฿{{ number_format($course->price ?? 990) }}</span>
                            </div>
                            <div class="flex justify-between text-blue-600 font-bold text-sm">
                                <span>ส่วนลด</span>
                                <span>- ฿0</span>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex justify-between items-end">
                                <span class="text-slate-800 font-black">ยอดสุทธิ</span>
                                <span class="text-3xl font-black text-slate-900">฿{{ number_format($course->price ?? 990) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('courses.register.submit', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-5 bg-green-500 text-white rounded-2xl font-black shadow-xl shadow-green-100 hover:bg-green-600 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                                ยืนยันการลงทะเบียน <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                        
                        <p class="text-[10px] text-slate-400 text-center mt-6 leading-relaxed">
                            โดยการคลิกปุ่มด้านบน แสดงว่าคุณยอมรับ <a href="#" class="underline">ข้อตกลงและเงื่อนไข</a> การใช้บริการของเรา
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection