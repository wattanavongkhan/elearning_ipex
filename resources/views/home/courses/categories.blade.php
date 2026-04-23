@extends('layouts.layout_public')

@section('content')
<style>
    /* 1. เพิ่ม Smooth Font และการเรนเดอร์ให้ชัดขึ้น */
    .font-kanit {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* 2. ยกระดับ Card ให้ดูพรีเมียม */
    .group {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        position: relative;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); /* นุ่มนวลกว่าปกติ */
    }

    .group:hover {
        border-color: rgba(37, 99, 235, 0.2);
        background: #ffffff;
        /* เงาสามชั้นเพื่อให้ดูมีน้ำหนัก (Layered Shadows) */
        box-shadow: 
            0 10px 15px -3px rgba(0, 0, 0, 0.02),
            0 20px 40px -4px rgba(37, 99, 235, 0.1),
            inset 0 0 0 1px rgba(37, 99, 235, 0.05);
    }

    /* 3. ลูกเล่นที่ Icon Box (Inner Glow) */
    .group .w-16 {
        box-shadow: 
            inset 0 2px 4px 0 rgba(0, 0, 0, 0.05),
            0 4px 6px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.4s ease;
    }

    .group:hover .w-16 {
        transform: scale(1.1) rotate(-8deg);
        box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.3);
    }

    /* 4. แอนิเมชันสำหรับ 'สำรวจบทเรียน' (Arrow Bounce) */
    .group:hover .fa-arrow-right {
        animation: arrowRight 0.8s infinite;
    }

    @keyframes arrowRight {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(5px); }
    }

    /* 5. เอฟเฟกต์แสงสะท้อนบน Card (Shine Effect) */
    .group::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.4),
            transparent
        );
        transition: 0.5s;
        z-index: 1;
    }

    .group:hover::before {
        left: 100%;
    }

    /* 6. ตัวนับจำนวนคอร์ส (Badge Style) */
    .courses-count-badge {
        background: rgba(37, 99, 235, 0.05);
        border: 1px solid rgba(37, 99, 235, 0.1);
        padding: 4px 12px;
        border-radius: 99px;
        display: inline-block;
    }
</style>

<div class="bg-slate-50 min-h-screen pb-24 font-kanit">
    
    <section class="relative pt-20 pb-16 overflow-hidden bg-white">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50 rounded-full blur-[120px] opacity-60"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-5xl font-black text-slate-900 mb-6 tracking-tight">
                เรียนตาม <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">ความสนใจ</span>
            </h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto leading-relaxed">
                ไม่ว่าคุณจะอยากเป็นโปรแกรมเมอร์ นักออกแบบ หรือนักการตลาด 
                เรามีหมวดหมู่ที่ครอบคลุมทุกความต้องการของคุณ
            </p>
        </div>
    </section>

    <div class="container mx-auto px-6 mt-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            @foreach($categories as $cat)
            <a href="{{ route('courses.all', ['category_id' => $cat->id]) }}" 
               class="group relative bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-50 rounded-full group-hover:bg-blue-50 transition-colors duration-500"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-50 rounded-2xl flex items-center justify-center mb-8 group-hover:from-blue-600 group-hover:to-blue-400 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-layer-group text-2xl text-slate-400 group-hover:text-white transition-colors"></i>
                    </div>

                    <h3 class="text-2xl font-black text-slate-800 mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $cat->category_name }}
                    </h3>
                    
                    <p class="text-slate-400 font-bold text-sm mb-6">
                        {{ $cat->courses_count }} คอร์สเรียน
                    </p>

                    <div class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all transform translate-x-[-10px] group-hover:translate-x-0">
                        สำรวจบทเรียน <i class="fas fa-arrow-right text-[10px]"></i>
                    </div>
                </div>
            </a>
            @endforeach

        </div>

        <div class="mt-24 bg-slate-900 rounded-[3.5rem] p-12 relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-80 h-80 bg-blue-600/20 rounded-full blur-[100px]"></div>
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 relative z-10">
                <div class="text-center lg:text-left">
                    <h2 class="text-3xl font-black text-white mb-4">หาสิ่งที่ใช่ไม่เจอ?</h2>
                    <p class="text-slate-400 leading-relaxed">
                        ลองค้นหาด้วยคำค้นหา หรือติดต่อเจ้าหน้าที่เพื่อขอคำแนะนำในการเลือกคอร์สเรียนที่เหมาะกับคุณ
                    </p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('courses.index') }}" class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                        ดูคอร์สทั้งหมด
                    </a>
                    <a href="#" class="px-8 py-4 bg-white/10 text-white rounded-2xl font-black border border-white/10 hover:bg-white/20 transition-all">
                        คุยกับพี่แอดมิน
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* เพิ่มลูกเล่นเวลาเอาเมาส์วาง (Custom Shadow) */
    .group:hover {
        border-color: rgba(37, 99, 235, 0.1);
    }
</style>
@endsection