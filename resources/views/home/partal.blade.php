@extends('layouts.layout_public')
@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center font-kanit">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-2 uppercase italic">I-PEX <span class="text-blue-600">Digital Portal</span></h1>
        <p class="text-slate-400 font-medium">ยินดีต้อนรับคุณ {{ Auth::user()->name }} | กรุณาเลือกตัวเลือกที่ต้องการเข้าใช้งาน</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 container mx-auto px-12">
        
        <a href="{{ url('/elearning') }}" class="group bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 text-center relative overflow-hidden">
            <div class="size-20 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fas fa-graduation-cap text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2 uppercase">E-Learning</h3>
            <p class="text-sm text-slate-400 font-medium leading-relaxed">คอร์สเรียนออนไลน์สำหรับพนักงานและ Citizen Developer</p>
            <div class="absolute -right-4 -bottom-4 size-24 bg-blue-50 rounded-full blur-2xl opacity-0 group-hover:opacity-60 transition-opacity"></div>
        </a>

        <a href="{{ url('/it-assets') }}" class="group bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 text-center relative overflow-hidden">
            <div class="size-20 bg-emerald-50 text-emerald-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fas fa-laptop-medical text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2 uppercase">IT Asset</h3>
            <p class="text-sm text-slate-400 font-medium leading-relaxed">ระบบจัดการทรัพย์สินและแจ้งซ่อมอุปกรณ์คอมพิวเตอร์</p>
            <div class="absolute -right-4 -bottom-4 size-24 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-60 transition-opacity"></div>
        </a>

        <a href="{{ url('/bi-hub') }}" class="group bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 text-center relative overflow-hidden">
            <div class="size-20 bg-amber-50 text-amber-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i class="fas fa-chart-line text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2 uppercase">BI Analytics</h3>
            <p class="text-sm text-slate-400 font-medium leading-relaxed">ศูนย์รวมรายงาน Power BI แยกตามแผนกแบบ Real-time</p>
            <div class="absolute -right-4 -bottom-4 size-24 bg-amber-50 rounded-full blur-2xl opacity-0 group-hover:opacity-60 transition-opacity"></div>
        </a>

    </div>
</div>
@endsection